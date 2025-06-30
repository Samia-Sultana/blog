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
        Schema::create('country_or_city_wise_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->nullable();
            $table->string('page_url')->nullable();
            $table->string('section_1_content_1')->nullable();
            $table->string('section_1_content_2')->nullable();
            $table->string('section_1_content_3')->nullable();
            $table->string('section_1_content_4')->nullable();
            $table->string('section_1_content_5')->nullable();
            $table->string('section_1_content_6')->nullable();
            $table->string('section_1_content_7')->nullable();

            $table->string('section_2_content_1')->nullable();

            $table->string('section_3_content_1')->nullable();
            $table->text('section_3_content_2')->nullable();

            $table->string('section_4_content_1')->nullable();

            $table->string('section_5_content_1')->nullable();
            $table->text('section_5_content_2')->nullable();
            $table->string('section_5_content_3')->nullable();
            $table->string('section_5_content_4')->nullable();

            $table->string('section_6_content_1')->nullable();
            $table->text('section_6_content_2')->nullable();

            $table->string('section_7_content_1')->nullable();
            $table->string('section_7_content_2')->nullable();
            $table->string('section_7_content_3')->nullable();
            $table->string('section_7_content_4')->nullable();
            $table->string('section_7_content_5')->nullable();
            $table->string('section_7_content_6')->nullable();
            $table->string('section_7_content_7')->nullable();
            $table->string('section_7_content_8')->nullable();

            $table->string('section_8_content_1')->nullable();
            $table->string('section_8_content_2')->nullable();
            $table->text('section_8_content_3')->nullable();

            $table->string('section_9_content_1')->nullable();
            $table->string('section_9_content_2')->nullable();
            $table->text('section_9_content_3')->nullable();

            $table->string('section_10_content_1')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_or_city_wise_page_contents');
    }
};
