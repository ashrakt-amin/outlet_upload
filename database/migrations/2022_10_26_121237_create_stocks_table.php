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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->decimal('sale_price', 16, 0);
            $table->decimal('buy_price', 16, 0)          ->nullable();
            $table->tinyInteger('buy_discount')          ->nullable();
            $table->unsignedBigInteger('trader_id')      ->nullable();
            $table->unsignedBigInteger('color_id')       ->nullable();
            $table->unsignedBigInteger('size_id')        ->nullable();
            $table->unsignedBigInteger('weight_id')      ->nullable();
            $table->unsignedBigInteger('volume_id')      ->nullable();
            $table->unsignedBigInteger('season_id')      ->nullable();
            $table->bigInteger('stock');
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
        Schema::dropIfExists('stocks');
    }
};
