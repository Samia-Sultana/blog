<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('country_or_city_wise_page_contents', function (Blueprint $table) {
            $table->text('section_4_content_2')->nullable();
            $table->text('section_4_content_3')->nullable();
            $table->text('section_4_content_4')->nullable();
            $table->text('section_4_content_5')->nullable();
            $table->text('section_4_content_6')->nullable();
            $table->text('section_4_content_7')->nullable();
            $table->string('ai_model')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('country_or_city_wise_page_contents', function (Blueprint $table) {
            $table->dropColumn(['section_4_content_2', 'section_4_content_3', 'section_4_content_4', 'section_4_content_5', 'section_4_content_6', 'section_4_content_7', 'ai_model']);
        });
    }
};
