<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArabicFieldsInFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('question');
            $table->dropColumn('answer');

            $table->string('question_en', 500)->after('id');
            $table->string('question_ar', 500)->after('question_en');
            $table->text('answer_en')->after('question_ar');
            $table->text('answer_ar')->after('answer_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('question_en');
            $table->dropColumn('question_ar');
            $table->dropColumn('answer_en');
            $table->dropColumn('answer_ar');

            $table->string('question', 1000)->after('id');
            $table->text('answer')->after('question');
        });
    }
}
