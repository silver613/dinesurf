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
        Schema::table('day_vendor', function (Blueprint $table) {
            $table->boolean('open')->default(true);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('day_vendor', function (Blueprint $table) {
            $table->dropColumn('open');
            $table->dropColumn('open_time');
            $table->dropColumn('close_time');
        });
    }
};
