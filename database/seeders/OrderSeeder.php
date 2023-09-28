<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()
            ->has(
                OrderItem::factory()
                    ->count(rand(1, 5)),
                'items'
            )
            ->count(5)
            ->create();
    }
}
