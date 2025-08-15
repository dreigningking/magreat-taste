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
            
            // Basic post information
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            
            // Media and author
            $table->string('featured_image')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Publishing and status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
    
            $table->string('meta_keywords')->nullable();
            
            // Engagement and analytics
            
            // Tags (JSON for flexibility)
            $table->json('tags')->nullable();
            
            // Reading time and difficulty
            $table->integer('reading_time')->nullable(); // in minutes
            
            // Social sharing
            $table->boolean('allow_comments')->default(true);
            $table->boolean('featured')->default(false);
            $table->timestamp('broadcasted_at')->nullable();
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'published_at']);
            $table->index(['user_id', 'published_at']);
            $table->index(['featured', 'published_at']);
            $table->index('created_at');
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
