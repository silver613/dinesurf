<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanFrequenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_frequencies', function (Blueprint $table) {
            $table->id();
            $table->integer('plan_id');
            $table->decimal('price', 30, 2);
            $table->string('currency')->default('NGN');
            $table->integer('duration');
            $table->string('duration_text');
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
        Schema::dropIfExists('plan_frequencies');
    }
}
