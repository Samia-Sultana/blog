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
        Schema::create('blogs', function (Blueprint $table) {
            // blog features
            $table->id();
            $table->string("title");
            $table->string("slug")->unique();
            $table->string("featured_image")->nullable();
            $table->unsignedBigInteger("author_id")->nullable();
            $table->date('published_at')->nullable();

            // seo features
            $table->enum('index_status', [1, 2])->default(2)->comment('1=index, 2=noindex');
            $table->string("meta_title")->nullable();
            $table->text("meta_description")->nullable();

            $table->unsignedBigInteger('created_by')->nullable()->comment('from users table');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('from users table');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('from users table');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};
