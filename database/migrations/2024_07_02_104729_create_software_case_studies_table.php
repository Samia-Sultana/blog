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
        Schema::create('software_case_studies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_study_id');

            $table->string('header_title');
            $table->string('client');
            $table->string('completed_on');  
            $table->string('featured_image_alt');
            $table->string('po_title');
            $table->text('po_desc');
            $table->string('challenge_title');
            $table->text('challenge_desc');
            $table->string('problem_title');
            $table->text('problem_desc');
            $table->string('middle_1st_image_alt');
            $table->string('middle_2nd_image_alt');
            $table->string('middle_3rd_image_alt');
            $table->string('middle_4th_image_alt');
            $table->string('workflow_title');
            $table->text('workflow_desc');
            $table->string('solution_title');
            $table->text('solution_desc');
            $table->string('bottom_1st_image_alt');
            $table->string('bottom_2nd_image_alt');
            $table->string('bottom_3rd_image_alt');
            $table->string('bottom_4th_image_alt');
            $table->string('bottom_5th_image_alt');
            $table->string('bottom_6th_image_alt');
            $table->string('conclusion_title');
            $table->text('conclusion_desc');


            $table->string('featured_image');

            $table->string('middle_1st_image');
            $table->string('middle_2nd_image');
            $table->string('middle_3rd_image');
            $table->string('middle_4th_image');

            $table->string('bottom_1st_image');
            $table->string('bottom_2nd_image');
            $table->string('bottom_3rd_image');
            $table->string('bottom_4th_image');
            $table->string('bottom_5th_image');
            $table->string('bottom_6th_image');

            $table->enum('index_status', [1, 2])->default(1)->comment('1=index, 2=noindex');
            $table->string("meta_title")->nullable();
            $table->text("meta_description")->nullable();

            $table->timestamps();
            $table->foreign('case_study_id')->references('id')->on('case_studies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('software_case_studies');
    }
};
