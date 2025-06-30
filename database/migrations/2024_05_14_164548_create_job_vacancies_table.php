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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->string('job_type');
            $table->string('address');
            $table->string('salary')->default('Negotiable');
            $table->string('deadline');
            $table->integer('no_of_vacancy');
            $table->text('about_company');


            $table->text('educations')->nullable(); //array
            $table->text('experiences')->nullable(); //array
            $table->text('employment_statuses')->nullable(); //array
            $table->text('responsibilities')->nullable(); //array
            $table->text('requirements')->nullable(); //array
            $table->text('benefits')->nullable(); //array

            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('job_vacancies');
    }
};
