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
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('project_id');
            $table->boolean('flash_sales')->default(0);
            $table->text('key_words');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('level_id');
            $table->dropColumn('project_id');
            $table->dropColumn('flash_sales');
            $table->dropColumn('key_words');
        });
    }
};