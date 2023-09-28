<?php

namespace Database\Seeders;

use App\Models\Tutorial;
use Illuminate\Database\Seeder;

class TutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = [
            'How to create menu!',
            'How to share menu link',
            'How to see new reservation on the dashboard',
            'Learn how to reset your password',
        ];

        foreach ($models as $key => $value) {
            $model = new Tutorial();
            $model->title = $value;
            $model->description = 'This video explain thorough '.$value;
            $model->video_url = 'https://www.youtube.com/embed/YqnjAKIB8tU?start=36';
            $model->save();
        }
    }
}
