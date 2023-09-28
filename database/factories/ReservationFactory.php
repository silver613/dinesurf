<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $period = CarbonPeriod::create(Carbon::parse('30-05-2022')->toDateString(), Carbon::parse('30-07-2022')->toDateString());
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        $user_id = User::all()->random()->id;
        $vendor_id = Vendor::all()->random()->id;

        return [
            'user_id' => $user_id,
            'vendor_id' => $vendor_id,
            'party_size' => rand(1, 4),
            'phone' => rand(2348000000000, 2348099999999),
            'created_by_vendor' => $this->faker->randomElement([true, false]),
            'date' => $this->faker->randomElement($dates),
            'time' => $this->faker->randomElement(['09:00', '10:00', '12:00', '15:00', '18:00', '20:00']),
            'seating_preferences' => $this->faker->randomElement(
                [
                    'VIP',
                    'Backseat',
                    'Lounge',
                    'bar', 'seatout',
                    'lobby', 'garage',
                ]
            ),
        ];
    }
}
