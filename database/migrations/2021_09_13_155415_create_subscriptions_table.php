<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('plan_id');
            $table->integer('user_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->string('type')->default('vendor');
            $table->dateTime('plan_start')->nullable();
            $table->dateTime('plan_end')->nullable();
            $table->string('transaction_reference');
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
        Schema::dropIfExists('subscriptions');
    }
}
