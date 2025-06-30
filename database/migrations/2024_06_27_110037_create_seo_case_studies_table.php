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
        Schema::create('seo_case_studies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_study_id');

            $table->string('header_title');
            $table->string('seo_conversion');
            $table->string('seo_incress');
            $table->string('revenue');
            $table->text('header_description');
            $table->string('featured_image');

            $table->string('sec_1_title');
            $table->text('sec_1_desc');
            $table->string('sec_1_image');

            $table->string('sec_2_title');
            $table->text('sec_2_desc');
            $table->string('sec_2_image');

            $table->string('sec_3_title');
            $table->text('sec_3_desc');
            $table->string('sec_3_image');

            $table->string('sec_result_title');
            $table->text('sec_result_desc');

            $table->string('sec_ranking_title');
            $table->text('sec_ranking_desc');
            $table->string('sec_ranking_image');

            $table->enum('index_status', [1, 2])->default(2)->comment('1=index, 2=noindex');
            $table->string("meta_title")->nullable();
            $table->text("meta_description")->nullable();

            $table->foreign('case_study_id')->references('id')->on('case_studies')->onDelete('cascade');


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
        Schema::dropIfExists('seo_case_studies');
    }
};
