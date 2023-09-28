<?php

namespace Database\Factories;

use App\Models\MenuCategoryItem;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuCategoryItem>
 */
class MenuCategoryItemFactory extends Factory
{
    protected $model = MenuCategoryItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $vendor_id = Vendor::first()->id;

        return [
            'name' => $this->faker->name,
            'price' => 4000,
            'vendor_id' => $vendor_id,
        ];
    }
}
