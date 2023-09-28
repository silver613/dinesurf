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
        Schema::table('vendors', function (Blueprint $table) {
            $table->decimal('min_reservation_deposit', 30, 2)->default(1000);
            $table->string('reservation_currency')->default('NGN');
            $table->boolean('reservation_deposit_required')->default(false);
            $table->boolean('accept_reservation_deposit')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('min_reservation_deposit');
            $table->dropColumn('reservation_currency');
            $table->dropColumn('reservation_deposit_required');
            $table->dropColumn('accept_reservation_deposit');
        });
    }
};
