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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            
            // Common fields for all contact types
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->date('preferred_date')->nullable();
            $table->longText('message');
            $table->enum('contact_type', ['inquiry', 'booking', 'feedback', 'review']);
            $table->enum('status', ['pending', 'resolved', 'closed'])->default('pending');
            
            // Dynamic fields for inquiry type
            $table->string('inquiry_subject')->nullable();
            $table->enum('inquiry_type', ['General Question', 'Pricing', 'Menu', 'Other'])->nullable();
            
            // Dynamic fields for booking type
            $table->string('event_type')->nullable();
            $table->integer('guest_count')->nullable();
            $table->string('event_location')->nullable();
            $table->enum('service_type', ['Full Catering', 'Drop-off', 'Personal Chef', 'Custom Menu'])->nullable();
            
            // Dynamic fields for feedback type
            $table->enum('feedback_type', ['Suggestion', 'Complaint', 'Compliment', 'Improvement'])->nullable();
            $table->enum('rating', ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'])->nullable();
            
            // Dynamic fields for review type
            $table->string('dish_name')->nullable();
            $table->enum('review_rating', ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'])->nullable();
            $table->boolean('publish_review')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
