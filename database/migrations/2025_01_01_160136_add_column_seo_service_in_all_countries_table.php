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
        Schema::table('seo_service_in_all_countries', function (Blueprint $table) {
            $table->enum('index_status', [1, 2])->default(2)->comment('1=index, 2=noindex');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seo_service_in_all_countries', function (Blueprint $table) {
            $table->dropColumn('index_status');
        });
    }
};
