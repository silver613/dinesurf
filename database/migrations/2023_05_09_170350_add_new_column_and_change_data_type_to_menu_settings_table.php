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
        Schema::table('menu_settings', function (Blueprint $table) {
            //
            $table->string('page')->nullable();
            $table->string('pdf')->nullable();
            $table->string('bg_type')->nullable()->change();
            $table->string('bg')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_settings', function (Blueprint $table) {
            //
        });
    }
};
