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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email");
            $table->string("phone");
            $table->string("dp");
            $table->longText("comment");
            $table->boolean("has_reply")->default(false);
            $table->boolean("has_ignored")->default(false);
            $table->boolean("is_verified_user")->default(false);
            $table->boolean("has_login")->default(false);
            $table->foreignId('post_id')->constrained()->onDelete('cascade')->default(0);
            $table->integer("status")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
