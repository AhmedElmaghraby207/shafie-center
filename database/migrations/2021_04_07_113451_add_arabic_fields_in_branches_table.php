<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArabicFieldsInBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('address');

            $table->string('name_en')->after('id');
            $table->string('name_ar')->after('name_en');
            $table->string('address_en', 500)->after('name_ar');
            $table->string('address_ar', 500)->after('address_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('name_en');
            $table->dropColumn('name_ar');
            $table->dropColumn('address_en');
            $table->dropColumn('address_ar');

            $table->string('name')->after('id');
            $table->string('address', 500)->after('address');
        });
    }
}
