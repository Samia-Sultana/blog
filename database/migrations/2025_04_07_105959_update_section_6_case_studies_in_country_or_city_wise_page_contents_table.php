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
            $table->dropColumn([
                'section_6_content_1',
                'section_6_content_2'
            ]);

            // Add new JSON column
            $table->json('section_6_case_studies')->nullable()->after('section_5_content_4');
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
            $table->dropColumn('section_6_case_studies');

            // Recreate the old columns
            $table->string('section_6_content_1')->nullable();
            $table->text('section_6_content_2')->nullable();
        });
    }
};
