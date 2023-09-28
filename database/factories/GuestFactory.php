<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vendor_id' => Vendor::all()->random()->id,
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => rand(2348000000000, 2348099999999),
            'general_notes' => $this->faker->sentence,
            'seating_preferences' => $this->faker->randomElement(
                [
                    'VIP',
                    'Backseat',
                    'Lounge',
                    'bar', 'seatout',
                    'lobby', 'garage',
                ]
            ),
            'food_drink_preferences' => $this->faker->randomElement(
                [
                    'rice',
                    'beans',
                    'milk',
                    'champagne', 'wine',
                    'fanta', 'coke',
                ]
            ),
        ];
    }
}
