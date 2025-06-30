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
        Schema::table('otps', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['user_id']);
        });

        Schema::table('otps', function (Blueprint $table) {
            // Add the new foreign key with onDelete cascade
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('otps', function (Blueprint $table) {
            // Drop the foreign key with onDelete cascade
            $table->dropForeign(['user_id']);
        });
        Schema::table('otps', function (Blueprint $table) {
            // Re-add the foreign key without onDelete cascade
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
