<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');

            // The voucher code
            $table->string('code')->unique()->nullable();

            // The human readable voucher code name
            $table->string('name');

            // The description of the voucher - Not necessary
            $table->text('description')->nullable();

            // The number of uses currently
            $table->bigInteger('uses')->unsigned()->nullable();

            // The max uses this voucher has
            $table->bigInteger('max_uses')->unsigned()->nullable();

            // How many times a user can use this voucher.
            // $table->bigInteger('max_uses_user')->unsigned()->nullable();

            // The type can be: percentage, price
            $table->string('type');

            // The amount to discount
            $table->bigInteger('discount_amount');

            // The minimum quantity of product to be ordered before voucher can be applied
            $table->integer('quantity_required')->nullable();

            // Whether or not the voucher is a percentage or a fixed price.
            $table->boolean('is_fixed')->default(true);

            // status
            $table->boolean('status')->default(true);

            // When the voucher begins
            $table->dateTime('starts_at')->nullable();

            // When the voucher ends
            $table->dateTime('expires_at')->nullable();

            // You know what this is...
            $table->timestamps();

            // We like to horde data.
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
