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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default('anonymous');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('type')->default('admin');
            $table->string('dp')->nullable();
            $table->string('status')->default(1);
            $table->string('lucky_no')->default(0);
            $table->longText('address')->nullable()->default('not-mentioned');
            $table->longText('remarks')->nullable()->default('not-mentioned');

            $table->string('role')->default('admin');
            $table->boolean('active')->default(false);
            $table->string('password')->nullable(); 
            $table->string('admin_id')->nullable();
            $table->boolean('super_admin')->default(false);
            $table->boolean('can_crud_manager')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
