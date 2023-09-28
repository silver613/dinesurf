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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->nullable();
            $table->decimal('amount', 30, 2)->default(0);
            $table->string('status')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('rating')->nullable();
            $table->foreignId('transaction_id')->nullable();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->text('client_id')->nullable();
            $table->text('external_id')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
