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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('client_id')   ->nullable();
            $table->unsignedBigInteger('trader_id')   ->nullable();
            $table->unsignedBigInteger('item_id')     ->nullable();
            $table->unsignedBigInteger('stock_id')    ->nullable();
            $table->tinyInteger('percentage_discount')->nullable();
            $table->integer('amount_discount')        ->nullable();
            $table->date('starting_date')             ->nullable();
            $table->date('expiring_date')             ->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
