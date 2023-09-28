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
        Schema::table('reviews', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
            $table->string('name')->nullable();
            $table->text('avatar')->nullable();
            $table->string('trip_advisor_review_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('reviews', 'user_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('avatar');
            $table->dropColumn('trip_advisor_review_id');
        });
    }
};
