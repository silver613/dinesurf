<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Order::class;

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
            'vendor_id' => $vendor_id,
            'email' => $this->faker->unique()->safeEmail,
            'table_number' => 6,
            'amount' => 400,
            'status' => $this->faker->randomElement(['open', 'completed', 'close', 'in-progress', 'pending']),
            'rating' => 4.5,
            'transaction_id' =>  rand(24803443, 23994432),
            'mira_id' =>   Str::random(10),
            'notes' =>  $this->faker->sentence(20),
            
        ];
    }
}
