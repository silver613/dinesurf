<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();

        // create admins
        $new_user = User::factory()->create([
            'name' => 'Providence Ifeosame',
            'first_name' => 'Providence',
            'last_name' => 'Ifeosame',
            'is_admin' => true,
            'email' => 'providence@reftek.co',
            'password' => bcrypt('favour007'),
        ]);

        $new_user->roles()->detach();

        $new_user->roles()->attach(1);

        // $count = 0;
        // for ($i = 0; $i < 100; $i++) {
        User::factory()->count(100)->create();
        //     $count += 100;
        //     print_r("\n".$i." loop, $count users seeded\n");
        // }
    }
}
