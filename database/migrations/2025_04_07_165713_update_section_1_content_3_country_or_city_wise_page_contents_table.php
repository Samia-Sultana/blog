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
            // Drop the original column
            $table->dropColumn('section_1_content_3');
        });

        Schema::table('country_or_city_wise_page_contents', function (Blueprint $table) {
            // Recreate the column as text
            $table->text('section_1_content_3')->nullable();
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
            // Drop the text column
            $table->dropColumn('section_1_content_3');
        });

        Schema::table('country_or_city_wise_page_contents', function (Blueprint $table) {
            // Recreate the original column as string
            $table->string('section_1_content_3')->nullable();
        });
    }
};
