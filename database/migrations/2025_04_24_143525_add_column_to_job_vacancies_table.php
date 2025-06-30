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
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropColumn(['address', 'about_company']);
        });

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->longText('description')->nullable();
            $table->string('address')->nullable();
            $table->text('about_company')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropColumn(['description', 'address', 'about_company']);
        });
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('address');
            $table->text('about_company');
        });
    }
};
