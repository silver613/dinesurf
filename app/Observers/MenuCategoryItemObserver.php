<?php

namespace App\Observers;

use App\Models\MenuCategoryItem;
use App\Models\MenuCategoryItemItem;
use App\Models\MenuSetting;
use App\Services\AllServices\Mira;
use Illuminate\Support\Facades\Log;

class MenuCategoryItemObserver
{
    public $mira_id;

    public function __construct()
    {
        $vendor = session('current_vendor');
        if ($vendor) {
            $menu_page = MenuSetting::where('vendor_id', $vendor->id)->value('page');
            $this->mira_id = $menu_page == 'mira-menu' ? $vendor->mira_id : null;
        }
    }

    public function creating(MenuCategoryItem $menuCategoryItem)
    {
        if ($this->mira_id) {
            try {
                $menuCategoryItem->mira_id = Mira::createItem($menuCategoryItem, $menuCategoryItem->category->mira_id, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }


    public function updating(MenuCategoryItem $menuCategoryItem)
    {
        if ($this->mira_id) {
            try {
                Mira::updateItem($menuCategoryItem, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }

    public function deleting(MenuCategoryItem $menuCategoryItem)
    {
        if ($this->mira_id) {
            try {
                Mira::deleteItem($menuCategoryItem, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }
}
