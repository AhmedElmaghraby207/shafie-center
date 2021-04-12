<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBirthDateInPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('age');
        });
        Schema::table('patients', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('image');
        });
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('birth_date');
        });
    }
}
