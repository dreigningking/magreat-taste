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
        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->timestamp('viewed_at');
            $table->integer('duration_seconds')->default(0);
            $table->boolean('is_qualified')->default(false); // 5+ minutes = true
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['post_id', 'ip_address']);
            $table->index(['post_id', 'is_qualified']);
            $table->index('viewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
