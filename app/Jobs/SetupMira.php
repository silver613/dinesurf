<?php

namespace App\Jobs;

use App\Models\Vendor;
use App\Services\AllServices\Mira;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetupMira implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;

    public $tries = 5;

    public function __construct(
        public string $auth_id,
        public ?string $mira_id = null,
        public ?object $vendor = null,
        public string $type = 'setup',
        public ?object $menu = null,
        public ?object $category = null,
        public ?object $item = null,

    ) {
    }

    public function handle()
    {
        if ($this->type == 'setup') {
            Mira::setup($this->vendor);
        }

        if ($this->type == 'menu') {
            $this->handleMenu($this->menu);
        }

        if ($this->type == 'category') {
            $this->handleCategory($this->category);
        }

        if ($this->type == 'item') {
            $this->handleItem($this->item);
        }
    }

    public function handleMenu($menu)
    {
        $menu_id = Mira::createMenu($menu, $this->auth_id);
        $menu->mira_id = $menu_id;
        $menu->saveQuietly();
        Mira::setupCategories($menu, $this->auth_id);
    }

    public function handleCategory($category)
    {
        $category_id = Mira::createCategory($category, $this->mira_id, $this->auth_id);
        $category->mira_id = $category_id;
        $category->saveQuietly();
        Mira::setupItems($category, $this->auth_id);
    }

    public function handleItem($item)
    {
        $item_id = Mira::createItem($item, $this->mira_id, $this->auth_id);
        $item->mira_id = $item_id;
        $item->saveQuietly();
    }
}
