<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalizationInAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('subject');
            $table->dropColumn('message');

            $table->string('subject_en')->nullable()->after('patients_ids');
            $table->string('subject_ar')->nullable()->after('subject_en');
            $table->text('message_en')->nullable()->after('subject_ar');
            $table->text('message_ar')->nullable()->after('message_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('subject_en');
            $table->dropColumn('subject_ar');
            $table->dropColumn('message_en');
            $table->dropColumn('message_ar');

            $table->string('subject', 500)->after('patients_ids');
            $table->string('message', 2000)->after('subject');
        });
    }
}
