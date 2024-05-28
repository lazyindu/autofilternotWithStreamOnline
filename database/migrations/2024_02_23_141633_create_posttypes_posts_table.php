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
        Schema::create('posttypes_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('posts_id')->unsigned();
            $table->unsignedBigInteger('posttypes_id')->unsigned();

            $table->foreign('posts_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('posttypes_id')->references('id')->on('posttypes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posttypes_posts');
    }
};
