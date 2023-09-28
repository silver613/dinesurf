<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
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
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'capacity' => 100,
            'duration' => 'single',
            'price' => 3900,
            'image' => '',
            'occupancy_goal' => 30,
            'payment' => 'free',
            'slot' => 'per_couple',
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'start_time' => now()->format('H:i:s'),
            'end_time' => now()->format('H:i:s'),
        ];
    }
}
