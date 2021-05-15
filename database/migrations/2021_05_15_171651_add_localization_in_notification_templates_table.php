<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalizationInNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->removeColumn('subject');
            $table->removeColumn('template');

            $table->string('subject_en')->nullable()->after('name');
            $table->string('subject_ar')->nullable()->after('subject_en');
            $table->string('template_en')->nullable()->after('subject_ar');
            $table->string('template_ar')->nullable()->after('template_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->removeColumn('subject_en');
            $table->removeColumn('subject_ar');
            $table->removeColumn('template_en');
            $table->removeColumn('template_ar');

            $table->string('subject', 500)->nullable()->after('name');
            $table->string('template', 2000)->nullable()->after('subject');
        });
    }
}
