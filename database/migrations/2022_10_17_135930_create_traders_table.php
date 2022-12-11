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
        Schema::create('traders', function (Blueprint $table) {
            $table->id();
            $table->string('f_name');
            $table->string('m_name');
            $table->string('l_name');
            $table->string('logo')                ->nullable();
            $table->tinyInteger('age');
            $table->string('password')            ->nullable();
            $table->string('phone')                 ->unique();
            $table->string('national_id')           ->unique();
            $table->bigInteger('code')              ->unique();
            $table->string('email')               ->nullable();
            $table->string('guard_name')   ->default('trader');
            $table->string('phone2')    ->unique()->nullable();
            $table->string('phone3')    ->unique()->nullable();
            $table->string('phone4')    ->unique()->nullable();
            $table->string('phone5')    ->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('traders');
    }
};
