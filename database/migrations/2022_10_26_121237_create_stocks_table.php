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
            $table->bigInteger('stock');
            $table->decimal('sale_price', 16, 2);
            $table->decimal('buy_price', 16, 2)     ->nullable();
            $table->decimal('buy_discount', 4, 2)   ->nullable();
            $table->unsignedBigInteger('trader_id') ->nullable();
            $table->boolean('available')            ->nullable();
            $table->boolean('approved')             ->default(0);
            $table->string('stock_code')              ->unique();
            $table->bigInteger('starting_stock')    ->nullable();
            $table->bigInteger('min_quantity')      ->nullable();
            $table->bigInteger('barcode')           ->nullable();
            $table->bigInteger('spare_barcode')     ->nullable();
            $table->unsignedBigInteger('color_id')  ->nullable();
            $table->unsignedBigInteger('size_id')   ->nullable();
            $table->unsignedBigInteger('volume_id') ->nullable();
            $table->unsignedBigInteger('season_id') ->nullable();
            $table->date('manufacture_date')        ->nullable();
            $table->date('expire_date')             ->nullable();
            $table->decimal('stock_discount', 4, 2) ->nullable();
            $table->date('discount_start_date')     ->nullable();
            $table->date('discount_end_date')       ->nullable();
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
