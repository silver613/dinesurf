<?php

namespace Database\Seeders;

use App\Models\Cuisine;
use Illuminate\Database\Seeder;

class CuisineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = [
            [
                'name' => 'African',
            ],
            [
                'name' => 'Chinese',
            ],
            [
                'name' => 'Mexican',
            ],
        ];

        foreach ($models as $key => $value) {
            $model = new Cuisine();
            $model->name = $value['name'];
            $model->save();
        }
    }
}
