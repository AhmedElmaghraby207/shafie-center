<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdInPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->bigInteger('branch_id')->unsigned()->after('last_name')->nullable();
        });
        Schema::table('patients', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches');
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
            $table->dropForeign('branch_id');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
    }
}
