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
        Schema::create('seo_service_in_all_countries', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title_1st_section')->nullable();
            $table->text('description_1st_section')->nullable();
            $table->string('image_1st_section')->nullable();

            $table->boolean('section_1st_is_active')->default(1);

            $table->string('title_2nd_section')->nullable();
            $table->text('description_2nd_section')->nullable();
            $table->string('section_2nd_video_link')->nullable();

            $table->boolean('section_2nd_is_active')->default(1);

            $table->string('title_3rd_section')->nullable();
            $table->text('description_3rd_section')->nullable();
            $table->string('section_3rd_image')->nullable();

            $table->boolean('section_3rd_is_active')->default(1);

            $table->string('case_study_1st_image')->nullable();
            $table->string('case_study_2nd_image')->nullable();

            $table->boolean('case_stydy_section_is_active')->default(1);


            $table->boolean('is_published')->default(0);


            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('canonical')->nullable();

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
        Schema::dropIfExists('seo_service_in_all_countries');
    }
};
