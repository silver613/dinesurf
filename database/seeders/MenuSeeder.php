<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MenuCategory::factory()->count(10)->create()
        ->each(function ($category) {
            $category->menus()->saveMany(\App\Models\Menu::factory()->count(5)->make());
            $category->items()->saveMany(\App\Models\MenuCategoryItem::factory()->count(5)->make());
        });
    }
}
