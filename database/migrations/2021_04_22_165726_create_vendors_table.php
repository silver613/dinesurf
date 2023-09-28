<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name');
            $table->text('company_address');
            $table->text('profile_photo_path')->nullable();
            $table->text('banner')->nullable();
            $table->string('type');
            $table->string('phone_number')->nullable();
            $table->boolean('approved')->default(true);
            $table->boolean('open')->default(true);
            $table->boolean('verified')->default(false);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->json('cuisine')->nullable();
            $table->integer('party_size')->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->text('description')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->string('menu_link')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
