<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = [
            'feature 1',
            'feature 2',
            'feature 3',
            'feature 4',
        ];

        foreach ($models as $key => $value) {
            $model = new Feature();
            $model->name = $value;
            $model->save();
        }
    }
}
