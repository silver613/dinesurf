<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\OrderItem;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $order_id = Order::first()->id;

        return [
            'name' => $this->faker->name,
            'order_id' => $order_id,
            'price' => rand(2480, 2399),
            'quantity' => 1,
        ];
    }
}
