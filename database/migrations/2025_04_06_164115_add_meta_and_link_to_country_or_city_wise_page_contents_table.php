<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('country_or_city_wise_page_contents', function (Blueprint $table) {
            $table->string('index')->nullable()->after('section_10_content_1');
            $table->text('meta')->nullable()->after('index');
            $table->text('link')->nullable()->after('meta');
            $table->text('script')->nullable()->after('link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_or_city_wise_page_contents', function (Blueprint $table) {
            $table->dropColumn(['index', 'meta', 'link', 'script']);
        });
    }
};
