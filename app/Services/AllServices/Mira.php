<?php

namespace App\Services\AllServices;

use App\Jobs\SetupMira;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuCategoryItem;
use Illuminate\Support\Facades\Http;

class Mira
{
    public static $url;
    public static $key;


    public function __construct()
    {
        self::$url = config('app.env') == 'production' ? config('services.mira.url') : config('services.mira.test_url');
        self::$key = config('app.env') == 'production' ? config('services.mira.key') : config('services.mira.test_key');
    }

    public static function integrate($vendor)
    {
        $firstchar = substr($vendor->phone_number, 0, 1);

        if ($firstchar != '+') {
            $phone = substr($vendor->phone_number, -10);
            $phone = "+234$phone";
        } else {
            $phone = $vendor->phone_number;
        }

        $account = Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
        ])
            ->post(self::$url . '/account/create', [
                'country' => $vendor->country->code,
                'business_email' => $vendor->email,
                'business_name' => $vendor->company_name,
                'business_phone_number' => $phone,
                'business_address' => $vendor->company_address,
                'business_logo' => $vendor->profile_photo_url,
            ])->throw()->json()['data']['account'];

        $vendor->mira_id = $account['id'];
        $vendor->saveQuietly();

        return $vendor;
    }

    public static function setup($vendor)
    {
        self::bankAccount($vendor);

        foreach ($vendor->menus as $key => $menu) {
            $menu->load('menuCategories');
            dispatch(new SetupMira(auth_id: $vendor->mira_id, type: 'menu', menu: $menu));
        }
        return "done";
    }

    public static function setupCategories($menu, $auth_id)
    {
        foreach ($menu->menuCategories as $key => $category) {
            $category->load('items');
            dispatch(new SetupMira(type: 'category', category: $category, mira_id: $menu->mira_id, auth_id: $auth_id));
        }
        return "done";
    }

    public static function setupItems($category, $auth_id)
    {
        foreach ($category->items as $key => $item) {
            dispatch(new SetupMira(type: 'item', item: $item, mira_id: $category->mira_id,  auth_id: $auth_id));
        }
        return "done";
    }

    public static function update($vendor)
    {
        $firstchar = substr($vendor->phone_number, 0, 1);

        if ($firstchar != '+') {
            $phone = substr($vendor->phone_number, -10);
            $phone = "+234$phone";
        } else {
            $phone = $vendor->phone_number;
        }

        Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $vendor->mira_id,
        ])
            ->patch(self::$url . '/account', [
                'business_phone_number' => $phone,
                'business_address' => $vendor->company_address,
                'business_logo' => $vendor->profile_photo_url,
            ])->throw()->json()['data']['account'];

        return 'mira account updated';
    }

    public static function bankAccount($vendor)
    {
        if (!$vendor->bank_code || !$vendor->account_number) {
            return;
        }
        print_r("\nSetting up bank account on mira...\n");
        Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $vendor->mira_id,
        ])
            ->post(self::$url . '/payouts/details', [
                'bank_code' => $vendor->bank_code,
                'account_number' => $vendor->account_number,
            ])->throw()->json();

        print_r("\nmira bank account created\n");

        return 'done';
    }

    // menu
    public static function createMenu(Menu $model, $auth_id)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->post(self::$url . '/menu', [
                'name' => $model->name,
                'description' => $model->description,
                'is_visible' => true,
            ])->throw()->json()["data"]["menu"]["id"];
    }

    public static function updateMenu(Menu $menu, $auth_id)
    {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->patch(self::$url . "/menu/$menu->mira_id", [
                'name' => $menu->name,
                'description' => $menu->description,
            ])->throw()->json();


        return 'menu updated!';
    }

    public static function deleteMenu(Menu $menu, $auth_id)
    {

        Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->delete(self::$url . "/menu/$menu->mira_id")
            ->throw()->json();

        return 'menu deleted!';
    }

    public static function createCategory(MenuCategory $model, $menu_id, $auth_id)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->post(self::$url . '/menu/categories', [
                'name' => $model->name,
                'description' => $model->description,
                'menu_id' => $menu_id,
            ])->throw()->json()["data"]["category"]['id'];
    }

    public static function updateCategory(MenuCategory $model, $auth_id)
    {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->patch(self::$url . "/menu/categories/$model->mira_id", [
                'name' => $model->name,
                'description' => $model->description,
                'menu_id' => $model->id,
            ])->throw()->json();

        return 'category updated!';
    }

    public static function deleteCategory(MenuCategory $model, $auth_id)
    {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->delete(self::$url . "/menu/$model->mira_id")
            ->throw()->json();

        return 'category deleted!';
    }

    public static function createItem(MenuCategoryItem $model, $category_id, $auth_id)
    {

        if($model->price == null) return;

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->post(self::$url . '/menu/items', [
                'name' => $model->name,
                'price' => $model->price,
                'stock' =>  50,
                'image_url' => $model->image_url,
                'description' => $model->description,
                'category_id' => $category_id,
            ])->throw()->json()["data"]["item"]['id'];
    }

    public static function updateItem(MenuCategoryItem $model, $auth_id)
    {
        if($model->price == null) return;
        Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->patch(self::$url . "/menu/items/$model->mira_id", [
                'name' => $model->name,
                'price' => $model->price,
                'stock' =>  $model->stock,
                'image_url' => $model->image_url,
                'description' => $model->description,
            ])->throw()->json();

        return 'item updated!';
    }

    public static function deleteItem(MenuCategoryItem $model, $auth_id)
    {
        Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$key,
            'x-child-id' => $auth_id,
        ])
            ->delete(self::$url . "/menu/items/$model->mira_id")
            ->throw()->json();
        return 'item deleted!';
    }
}

return new Mira;
