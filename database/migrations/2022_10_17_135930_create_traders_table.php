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
            $table->string('f_name')                ->nullable();
            $table->string('l_name')                ->nullable();
            $table->string('img')                   ->nullable();
            $table->date('age')                     ->nullable();
            $table->string('password')              ->nullable();
            $table->string('phone')       ->nullable()->unique();
            $table->string('national_id') ->nullable()->unique();
            $table->string('code')                    ->unique();
            $table->boolean('approved')             ->default(0);
            $table->string('email')       ->nullable()->unique();
            $table->timestamp('email_verified_at')  ->nullable();
            $table->timestamp('phone_verified_at')  ->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
