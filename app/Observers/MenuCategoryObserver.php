<?php

namespace App\Observers;

use App\Models\MenuCategory;
use App\Models\MenuSetting;
use App\Services\AllServices\Mira;
use Illuminate\Support\Facades\Log;

class MenuCategoryObserver
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

    public function creating(MenuCategory $menuCategory)
    {
        if ($this->mira_id) {
            try {
                $menuCategory->mira_id = Mira::createCategory($menuCategory, $menuCategory->menu->mira_id, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }


    public function updating(MenuCategory $menuCategory)
    {
        if ($this->mira_id) {
            try {
                Mira::updateCategory($menuCategory, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }

    public function deleting(MenuCategory $menuCategory)
    {
        if ($this->mira_id) {
            try {
                Mira::deleteCategory($menuCategory, $this->mira_id);
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }
}
