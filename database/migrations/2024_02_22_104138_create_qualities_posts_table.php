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
        Schema::create('qualities_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('posts_id')->unsigned();
            $table->unsignedBiginteger('qualities_id')->unsigned();

            $table->foreign('posts_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('qualities_id')->references('id')->on('qualities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualities_posts');
    }
};
