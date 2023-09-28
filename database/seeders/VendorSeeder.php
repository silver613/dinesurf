<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
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
                'first_name' => 'providence',
                'last_name' => 'ifeosame',
                'company_name' => 'Burger King',
                'company_address' => 'A home for Burgers',
                'profile_photo_path' => 'vendor_images/jFMTd80zEEUTUzJ6eOG4YZ49J3DKAZR8a9BjWDup.png',
                'banner' => 'vendor_images/TaGNhLh1KuEnZCoYMTNNkDZGsMvVWi6eYJRGvL8y.jpg',
                'type' => 'restaurant',
                'phone_number' => '08187655140',
                'verified' => true,
                'email' => 'providenceifeosame@gmail.com',
                'password' => '$2y$10$otvjkheKS327eTDc33uU.uVwhgIGfsi9l34wYTnMxmKEpxMcrACpu',
                'party_size' => 9,
                'open_time' => '11:00:00',
                'close_time' => '22:45:00',
                'description' => 'A place where slim people become robust',
                'company_email' => 'eatasyoulike@choplife.com',
                'company_phone' => '2144190803',
                'cancellation_policy' => "Just let us know if you're not coming again",
                'cancellation_policy' => "Just let us know if you're not coming again",
                // "menu_link" => "2144190803",
                'whatsapp_number' => '2348187655140',
                'facebook_link' => 'https://web.facebook.com/burgerking',
                'instagram_link' => 'https://www.instagram.com/burgerking/',
                'linkedin_link' => 'https://www.linkedin.com/company/burger-king/',
                'twitter_link' => 'https://twitter.com/BurgerKing',
                // "longitude" => "2144190803",
                // "latitude" => "2144190803",
                'youtube_video' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/YqnjAKIB8tU?start=36" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',

            ],
        ];

        // foreach ($models as $key => $value) {
        $model = new Vendor();
        $model->first_name = 'providence';
        $model->last_name = 'ifeosame';
        $model->company_name = 'Burger King';
        $model->company_address = '2d Obasa Road, Ikeja, Nigeria';
        $model->profile_photo_path = 'vendor_images/jFMTd80zEEUTUzJ6eOG4YZ49J3DKAZR8a9BjWDup.png';
        $model->banner = 'vendor_images/TaGNhLh1KuEnZCoYMTNNkDZGsMvVWi6eYJRGvL8y.jpg';
        $model->type = 'restaurant';
        $model->phone_number = '08187655140';
        $model->verified = true;
        $model->email = 'providenceifeosame@gmail.com';
        $model->password = '$2y$10$otvjkheKS327eTDc33uU.uVwhgIGfsi9l34wYTnMxmKEpxMcrACpu';
        $model->party_size = 9;
        $model->open_time = '11:00:00';
        $model->close_time = '22:45:00';
        $model->description = 'A place where slim people become robust';
        $model->company_email = 'eatasyoulike@choplife.com';
        $model->company_phone = '2144190803';
        $model->cancellation_policy = "Just let us know if you're not coming again";
        $model->cancellation_policy = "Just let us know if you're not coming again";
        // "menu_link = "2144190803" ;
        $model->whatsapp_number = '2348187655140';
        $model->facebook_link = 'https://web.facebook.com/burgerking';
        $model->instagram_link = 'https://www.instagram.com/burgerking/';
        $model->linkedin_link = 'https://www.linkedin.com/company/burger-king/';
        $model->twitter_link = 'https://twitter.com/BurgerKing';
        $model->youtube_link = 'https://www.youtube.com/embed/YqnjAKIB8tU?start=36';
        $model->longitude = '3.3348542';
        $model->latitude = '6.6008024';
        $model->save();

        $model->cuisines()->attach(1);
        $model->cuisines()->attach(3);

        for ($i = 0; $i < 10; $i++) {
            $model = new Vendor();
            $model->first_name = 'providence';
            $model->last_name = 'ifeosame';
            $model->company_name = "Burger King $i";
            $model->company_address = '2d Obasa Road, Ikeja, Nigeria';
            $model->profile_photo_path = 'vendor_images/jFMTd80zEEUTUzJ6eOG4YZ49J3DKAZR8a9BjWDup.png';
            $model->banner = 'vendor_images/TaGNhLh1KuEnZCoYMTNNkDZGsMvVWi6eYJRGvL8y.jpg';
            $model->type = 'restaurant';
            $model->phone_number = '08187655140';
            $model->verified = true;
            $model->email = "providenceifeosame$i@gmail.com";
            $model->password = '$2y$10$otvjkheKS327eTDc33uU.uVwhgIGfsi9l34wYTnMxmKEpxMcrACpu';
            $model->party_size = 9;
            $model->open_time = '11:00:00';
            $model->close_time = '22:45:00';
            $model->description = 'A place where slim people become robust';
            $model->company_email = 'eatasyoulike@choplife.com';
            $model->company_phone = '2144190803';
            $model->cancellation_policy = "Just let us know if you're not coming again";
            $model->cancellation_policy = "Just let us know if you're not coming again";
            // "menu_link = "2144190803" ;
            $model->whatsapp_number = '2348187655140';
            $model->facebook_link = 'https://web.facebook.com/burgerking';
            $model->instagram_link = 'https://www.instagram.com/burgerking/';
            $model->linkedin_link = 'https://www.linkedin.com/company/burger-king/';
            $model->twitter_link = 'https://twitter.com/BurgerKing';
            $model->youtube_link = 'https://www.youtube.com/embed/YqnjAKIB8tU?start=36';
            $model->longitude = '3.3348542';
            $model->latitude = '6.6008024';
            $model->save();

            $model->cuisines()->attach(1);
            $model->cuisines()->attach(3);
        }
    }
}
