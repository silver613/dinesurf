<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday', 'sunday',
        ];

        foreach ($models as $key => $value) {
            $model = new Day();
            $model->name = $value;
            $model->save();
        }
    }
}
