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
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('trader_id')->default('0');
            $table->unsignedBigInteger('site_id')    ->default(0);
            $table->unsignedBigInteger('statu_id')    ->default(0);
            // $table->enum('status', ['خالية', 'تعاقد'])->default('خالية');
            // $table->unsignedBigInteger('package_id') ->nullable();
            $table->unsignedBigInteger('finance_id') ->default(0);
            $table->decimal('space', 8, 2)           ->default(0);
            $table->decimal('price_m', 8, 2)         ->default(0);
            $table->decimal('unit_value', 16, 2)     ->default(0);
            $table->tinyInteger('deposit')         ->default('6');
            $table->decimal('rent_value', 8, 2)      ->default(0);
            $table->tinyInteger('rents_count')    ->default('36');
            $table->tinyInteger('discount')          ->default(0);
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
