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
        Schema::create('canceled_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')      ->default();
            $table->unsignedBigInteger('statu_id')  ->default('0');
            $table->unsignedBigInteger('trader_id') ->default('0');
            $table->unsignedBigInteger('finance_id')  ->nullable();
            $table->date('created_date')->nullable();
            $table->date('canceled_date')->nullable();
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
        Schema::dropIfExists('canceled_units');
    }
};
