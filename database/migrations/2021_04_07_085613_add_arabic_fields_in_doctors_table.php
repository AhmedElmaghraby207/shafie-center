<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArabicFieldsInDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('about');
            $table->dropColumn('clinic_name');

            $table->string('name_en', 200)->nullable()->after('id');
            $table->string('name_ar', 200)->nullable()->after('name_en');
            $table->text('about_en')->nullable()->after('name_ar');
            $table->text('about_ar')->nullable()->after('about_en');
            $table->string('clinic_name_en')->nullable()->after('about_ar');
            $table->string('clinic_name_ar')->nullable()->after('clinic_name_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('name', 200)->after('id');
            $table->text('about')->after('signature');
            $table->string('clinic_name', 255)->after('about');

            $table->dropColumn('name_en');
            $table->dropColumn('name_ar');
            $table->dropColumn('about_en');
            $table->dropColumn('about_ar');
            $table->dropColumn('clinic_name_en');
            $table->dropColumn('clinic_name_ar');
        });
    }
}
