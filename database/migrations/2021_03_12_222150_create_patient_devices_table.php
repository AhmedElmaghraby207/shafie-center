<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_devices', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->unsigned();
            $table->unsignedBigInteger('PatientId');
            $table->string('device_unique_id', 200);
            $table->string('token', 200)->nullable();
            $table->string('firebase_token', 200)->nullable();
            $table->boolean('is_logged_in')->nullable()->default(0);
            $table->timestamps();
        });
        Schema::table('patient_devices', function (Blueprint $table) {
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
        Schema::dropIfExists('patient_devices');
    }
}
