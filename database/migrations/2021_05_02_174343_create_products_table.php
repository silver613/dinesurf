<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('image');
            $table->string('youtube_video_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 30, 2);
            $table->string('currency');
            $table->integer('vendor_id')->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->boolean('featured')->default(true);
            $table->boolean('show_reviews')->default(true);
            $table->boolean('free_shipping')->default(true);
            $table->boolean('status')->default(true);
            $table->integer('stock')->default(0);
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
        Schema::dropIfExists('products');
    }
}
