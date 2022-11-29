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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('construction_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('site_id')    ->nullable();
            $table->unsignedBigInteger('statu_id') ->default('0');
            $table->unsignedBigInteger('trader_id')->default('0');
            $table->unsignedBigInteger('finance_id') ->nullable();
            $table->decimal('space', 8, 0);
            $table->decimal('price_m', 8, 0);
            $table->decimal('unit_value', 16, 0)     ->nullable();
            $table->tinyInteger('deposit')         ->default('6');
            $table->decimal('rent_value', 8, 0)      ->nullable();
            $table->tinyInteger('rents_count')    ->default('36');
            $table->tinyInteger('discount')          ->nullable();
            $table->text('description')              ->nullable();
            $table->date('created_date')             ->nullable();
            $table->date('canceled_date')            ->nullable();
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
        Schema::dropIfExists('units');
    }
};
