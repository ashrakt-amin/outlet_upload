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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('item_unit_id');
            $table->integer('unit_parts_count');
            $table->unsignedBigInteger('trader_id')      ->nullable();
            $table->string('item_code')                    ->unique();
            $table->decimal('discount', 16)              ->default(0);
            $table->text('description')                  ->nullable();
            $table->unsignedBigInteger('manufactory_id') ->nullable();
            $table->unsignedBigInteger('agent_id')       ->nullable();
            $table->unsignedBigInteger('company_id')     ->nullable();
            $table->boolean('import')                    ->nullable();
            $table->unsignedBigInteger('importer_id')    ->nullable();
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
        Schema::dropIfExists('items');
    }
};
