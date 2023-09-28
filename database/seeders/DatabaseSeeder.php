<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(PermissionTableSeeder::class);
        // $this->call(RoleTableSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(AppSettingSeeder::class);
        // $this->call(CuisineSeeder::class);
        // $this->call(VendorSeeder::class);
        // $this->call(DaySeeder::class);
        // $this->call(FeatureSeeder::class);
        // $this->call(PlanSeeder::class);
        // $this->call(VoucherSeeder::class);
        // $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        // $this->call(CitySeeder::class);
        // $this->call(ReservationSeeder::class);
        // $this->call(GuestSeeder::class);
        // $this->call(MenuSeeder::class);
        // $this->call(TutorialSeeder::class);
        // $this->call(EventSeeder::class);
        // $this->call(BankSeeder::class);
        // $this->call(OrderSeeder::class);
    }
}
