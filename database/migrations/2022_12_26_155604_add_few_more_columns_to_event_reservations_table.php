<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_reservations', function (Blueprint $table) {
            $table->boolean('isPaid');
            $table->string('payment_ref')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('transaction_ref')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_reservations', function (Blueprint $table) {
            //
        });
    }
};
