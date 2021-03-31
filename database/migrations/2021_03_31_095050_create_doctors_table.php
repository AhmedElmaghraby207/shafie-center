<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('email', 200)->nullable();
            $table->string('phone', 200)->nullable();
            $table->string('image', 500)->nullable();
            $table->string('signature', 500)->nullable();
            $table->text('about')->nullable();
            $table->string('clinic_name')->nullable();
            $table->string('facebook', 500)->nullable();
            $table->string('instagram', 500)->nullable();
            $table->string('twitter', 500)->nullable();
            $table->string('youtube', 500)->nullable();
            $table->string('website', 500)->nullable();
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
        Schema::dropIfExists('doctors');
    }
}
