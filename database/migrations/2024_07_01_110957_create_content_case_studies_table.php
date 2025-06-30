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
        Schema::create('content_case_studies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_study_id');

            $table->string('header_title');
            $table->string('client');
            $table->string('completed_on');
            $table->string('featured_image');

            $table->string('po_title');
            $table->text('po_desc');

            $table->string('challenge_title');
            $table->text('challenge_desc');

            $table->string('helped_title');
            $table->text('helped_desc');

            $table->string('problem_title');
            $table->text('problem_desc');
            $table->string('problem_image');
            

            $table->string('challenge_2_title');
            $table->text('challenge_2_desc');
            $table->string('challenge_2_image');

            $table->string('result_title');
            $table->text('result_desc');
            $table->string('result_image');
            
            $table->enum('index_status', [1, 2])->default(1)->comment('1=index, 2=noindex');
            $table->string("meta_title")->nullable();
            $table->text("meta_description")->nullable();

            $table->foreign('case_study_id')->references('id')->on('case_studies');

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
        Schema::dropIfExists('content_case_studies');
    }
};
