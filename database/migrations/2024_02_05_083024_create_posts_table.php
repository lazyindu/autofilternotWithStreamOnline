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
        Schema::create('posts', function (Blueprint $table) {
            
            $table->id();

            // general
            $table->string("thumbnail")->nullable();
            $table->longText("title")->default('Title')->nullable();
            
            $table->longText("pixels")->default('Not mentioned')->nullable();
            
            $table->string("min_size")->default('--')->nullable();
            $table->string("med_size")->default('--')->nullable();
            $table->string("max_size")->default('--')->nullable();
            $table->integer("is_highly_requested")->default(0)->nullable();
            $table->integer("is_most_searched")->default(0)->nullable();
            $table->integer("is_newly_released")->default(0)->nullable();
            
            // imdb card
            $table->decimal('rating', 2, 1)->default(0)->nullable();
            $table->longText("movie_name")->default('Not mentioned')->nullable();
            $table->string("release_year")->default('Not mentioned')->nullable();
            $table->longText("storyline")->default('Not mentioned')->nullable();
            $table->longText("screenshots")->default('Uploading soon..... ')->nullable();
            $table->longText("format")->default('Not mentioned')->nullable();

            // links
            $table->longText("download_description")->default('Updating soon...')->nullable();

            // Meta 
            $table->longText("meta_title")->default('not-defined')->nullable();
            $table->longText("meta_description")->default('not-defined')->nullable();
            $table->longText("meta_keywords")->default('not-defined')->nullable();
            
            // slugs
            $table->longText("slug")->default('slug-not-mentioned')->nullable();
            
            // foreign keys
            $table->foreignId('manager_id')->constrained('managers')->default(null);
            $table->foreignId('admin_id')->constrained('admins')->default(null);
            
            // Author
            $table->string('author_role')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }

};
