<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('PatientId');
            $table->float('weight');
            $table->timestamps();
        });
        Schema::table('patient_weights', function (Blueprint $table) {
            $table->foreign('PatientId')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_weights');
    }
}
