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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('continent');
            $table->unsignedBigInteger('country_id');
            $table->string('country');
            $table->string('code');
            $table->string('dial');
            $table->unsignedBigInteger('state_id');
            $table->string('state');
            $table->string('city');  
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
