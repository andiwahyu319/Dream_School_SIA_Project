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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('teacher');
            $table->unsignedBigInteger('class_id');
            $table->enum("method", [1, 2]);
            $table->string('key');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();

            $table->foreign('teacher')->references('id')->on('users');
            $table->foreign('class_id')->references('id')->on('class_rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
