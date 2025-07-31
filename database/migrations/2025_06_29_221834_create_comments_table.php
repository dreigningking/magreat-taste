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
            
            // Comment content
            $table->text('content');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            
            // Guest user info (if not logged in)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            
            // @username mentions (JSON array of mentioned user IDs)
            $table->json('mentions')->nullable();
            
            // Status and moderation
            $table->enum('status', ['pending', 'approved', 'spam', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            
            
            // User agent and IP for spam detection
            $table->string('user_agent')->nullable();
            $table->ipAddress('ip_address')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['post_id', 'status', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('approved_at');
            $table->index('is_featured');
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
