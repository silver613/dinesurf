<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Reservation::truncate();

        // $count = 0;
        // for ($i = 0; $i < 100; $i++) {
        Reservation::factory()->count(100)->create();
        //     $count += 100;
        //     print_r("\n".$i." loop, $count reservations seeded\n");
        // }
    }
}
