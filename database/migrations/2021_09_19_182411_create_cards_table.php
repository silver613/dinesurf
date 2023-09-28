<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->string('stripe_card_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('token')->nullable();
            $table->string('last_digits');
            $table->string('email');
            $table->string('exp_month');
            $table->string('exp_year');
            $table->string('card_type');
            $table->text('auth')->nullable();
            $table->boolean('primary')->default(false);
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
        Schema::dropIfExists('cards');
    }
}
