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
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn(['position_id']);
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->string('applying_position')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('status_labels')->onDelete('cascade');
            $table->foreignId('position_id')->nullable()->constrained('status_labels')->onDelete('cascade');
            $table->tinyInteger('application_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn(['position_id']);
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('position_id')->constrained('status_labels')->onDelete('cascade');
            $table->dropForeign(['department_id']);
            $table->dropColumn(['applying_position', 'application_type', 'department_id']);
        });
    }
};
