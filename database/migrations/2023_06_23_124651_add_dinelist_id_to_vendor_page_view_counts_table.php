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
        Schema::table('vendor_page_view_counts', function (Blueprint $table) {
            $table->bigInteger('dinelist_id')->nullable();
            $table->bigInteger('vendor_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_page_view_counts', function (Blueprint $table) {
            //
            $table->dropColumn('dinelist_id');
        });
    }
};
