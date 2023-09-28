<?php

namespace App\Observers;

use App\Models\Menu;
use App\Models\MenuSetting;
use App\Services\AllServices\Mira;
use Illuminate\Support\Facades\Log;

class MenuObserver
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

    public function creating(Menu $menu)
    {
        if ($this->mira_id) {
            try {
                $menu->mira_id = Mira::createMenu($menu, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }


    public function updating(Menu $menu)
    {
        if ($this->mira_id) {
            try {
                Mira::updateMenu($menu, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }

    public function deleting(Menu $menu)
    {
        if ($this->mira_id) {
            try {
                Mira::deleteMenu($menu, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }
}
