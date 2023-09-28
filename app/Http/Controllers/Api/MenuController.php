<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuCategoryItem;
use App\Models\Vendor;
use App\Services\AllServices\Admin;
use App\Services\AllServices\General;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function menus(Request $request)
    {
        if (! $request->vendor_id) {
            return  Helper::apiRes('vendor id is required please!', [], false, 400);
        }

        $vendor = Vendor::find($request->vendor_id);

        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date'];

        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::getMenus($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath('menus'),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function createMenu(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'vendor_id' => 'required|integer',
            ]);
            $menu = Admin::createMenu($request->vendor_id, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Menu Created Successfully', $menu);
    }

    public function singleMenu($id)
    {
        try {
            $menu = Menu::with('menuCategories')->where('id', $id)->first();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Menu Retrieved Successfully', $menu);
    }

    public function updateMenu(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $Findmenu = Menu::find($id);
            $menu = Admin::updateMenu($Findmenu, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Menu Updated Successfully', $menu);
    }

    public function deleteMenu($id)
    {
        try {
            $menu = Menu::find($id);
            $menu->delete();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Menu Deleted Successfully', $menu);
    }

    // categories

    public function getCategories(Request $request)
    {
        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'menu_id', 'vendor_id'];

        if (! $request->vendor_id) {
            return  Helper::apiRes('vendor id is required please!', [], false, 400);
        }
        $filters = request()->all($query_params);
        $filters = (object) $filters;
        $vendor = vendor::find($request->vendor_id);
        $query = General::getMenuCategories($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath($filters->menu_id),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function createCategory(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'vendor_id' => 'required|integer',
                'menu_ids' => 'required|array|min:1',
                'menu_ids.*' => 'required|integer|distinct|min:1',
            ]);

            $category = Admin::createMenuCategory($request->vendor_id, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Category Created Successfully', $category);
    }

    public function singleCategory($id)
    {
        try {
            $category = MenuCategory::with(['menus', 'items'])->where('id', $id)->first();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Category Retrieved Successfully', $category);
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'selectedMenus' => 'required|array|min:1',
                'selectedMenus.*' => 'required|integer|distinct|min:1',
                'menu_ids' => 'required|array|min:1',
                'menu_ids.*' => 'required|integer|distinct|min:1',
            ]);
            $Fcategory = MenuCategory::find($id);
            $category = Admin::updateMenuCategory($Fcategory, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Menu Updated Successfully', $category);
    }

    public function deleteCategory($id)
    {
        try {
            $category = menuCategory::find($id);
            $category->delete();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Category Deleted Successfully', $category);
    }

    // items

    public function getItems($vendor_id)
    {
        $query_params = ['search', 'field', 'direction', 'start_date', 'end_date', 'category_id', 'menu_id'];
        if (! $vendor_id) {
            return  Helper::apiRes('vendor id is required please!', [], false, 400);
        }

        $vendor = vendor::find($vendor_id);
        $filters = request()->all($query_params);
        $filters = (object) $filters;

        $query = General::getMenuCategoryItems($vendor, $filters);

        $models = $query->paginate()->withQueryString();

        return response()->json([
            'models' => $models->withPath($filters->menu_id),
            'filters' => request()->all($query_params),
            'vendor' => $vendor,
        ]);
    }

    public function createItem(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|integer|min:2',
                'vendor_id' => 'required|integer',
                'category_ids' => 'required|array|min:1',
                'category_ids.*' => 'required|integer|distinct|min:1',
            ]);

            $item = Admin::createMenuCategoryItem($request->vendor_id, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Item Created Successfully', $item);
    }

    public function singleItem($id)
    {
        try {
            $item = MenuCategoryItem::with(['categories'])->where('id', $id)->first();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Item Retrieved Successfully', $item);
    }

    public function updateItem(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|integer|min:2',
                'category_ids' => 'required|array|min:1',
                'category_ids.*' => 'required|integer|distinct|min:1',
                'selectedCategories' => 'required|array|min:1',
                'selectedCategories.*' => 'required|integer|distinct|min:1',
            ]);

            $Fitem = MenuCategoryItem::find($id);
            $item = Admin::updateMenuCategoryItem($Fitem, $request);
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Item Updated Successfully', $item);
    }

    public function deleteItem($id)
    {
        try {
            $item = MenuCategoryItem::find($id);
            $item->delete();
        } catch (\Throwable $th) {
            return Helper::apiRes($th->getMessage(), [], false, 400);
        }

        return Helper::apiRes('Item Deleted Successfully', $item);
    }
}
