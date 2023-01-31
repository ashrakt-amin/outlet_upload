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
            $table->unsignedBigInteger('project_id') ->nullable();
            $table->unsignedBigInteger('trader_id')->default('0');
            $table->unsignedBigInteger('package_id') ->nullable();
            $table->boolean('famous')                ->default(0);
            $table->boolean('offers')                ->default(0);
            $table->boolean('online')                ->default(0);
            $table->text('description')              ->nullable();
            $table->unsignedBigInteger('created_by') ->nullable();
            $table->unsignedBigInteger('updated_by') ->nullable();
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
