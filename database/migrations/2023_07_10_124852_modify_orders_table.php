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
        Schema::table('orders', function (Blueprint $table) {
            // Drop the 'client_id' column
            $table->dropColumn('client_id');

            // Change 'external_id' column to 'mira_id'
            $table->renameColumn('external_id', 'mira_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Recreate the 'client_id' column
            $table->text('client_id')->nullable();

            // Rename 'mira_id' column back to 'external_id'
            $table->renameColumn('mira_id', 'external_id');
        });

    }
};
