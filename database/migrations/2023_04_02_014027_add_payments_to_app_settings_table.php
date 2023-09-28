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
        Schema::table('app_settings', function (Blueprint $table) {
            $table->decimal('events_percentage_fee', 3, 3)->default(0.02);
            $table->decimal('events_cap_fee', 30, 3)->default(100.00);
            $table->decimal('reservations_percentage_fee', 3, 3)->default(0);
            $table->decimal('reservations_cap_fee', 30, 3)->default(0);
            $table->decimal('orders_percentage_fee', 3, 3)->default(0.01);
            $table->decimal('orders_cap_fee', 30, 3)->default(200.00);
            $table->decimal('paystack_percentage_fee', 3, 3)->default(0.015);
            $table->decimal('paystack_flat_fee', 30, 3)->default(100.00);
            $table->decimal('paystack_wave_under', 30, 3)->default(2500.00);
            $table->decimal('paystack_cap_fee', 30, 3)->default(2000.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn('events_percentage_fee');
            $table->dropColumn('events_cap_fee');
            $table->dropColumn('reservations_percentage_fee');
            $table->dropColumn('reservations_cap_fee');
            $table->dropColumn('orders_percentage_fee');
            $table->dropColumn('orders_cap_fee');
            $table->dropColumn('paystack_percentage_fee');
            $table->dropColumn('paystack_flat_fee');
            $table->dropColumn('paystack_wave_under');
            $table->dropColumn('paystack_cap_fee');
        });
    }
};
