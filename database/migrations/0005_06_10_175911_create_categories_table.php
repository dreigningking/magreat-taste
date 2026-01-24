<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('description');
            $table->string('image')->nullable();
            $table->string('type')->default('post');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $categories = [
            ['name' => 'Recipes', 'type' => 'post', 'description' => 'Delicious recipes and cooking guides'],
            ['name' => 'Cooking Tips', 'type' => 'post', 'description' => 'Professional cooking tips and techniques'],
            ['name' => 'Chef Stories', 'type' => 'post', 'description' => 'Behind-the-scenes stories from the kitchen'],
            ['name' => 'Events', 'type' => 'post', 'description' => 'Cooking events and workshops'],
            ['name' => 'Ingredients', 'type' => 'post', 'description' => 'Ingredient guides and selection tips'],
            ['name' => 'Local', 'type' => 'meal', 'description' => 'Local cuisine'],
            ['name' => 'Italian', 'type' => 'meal', 'description' => 'Italian cuisine'],
            ['name' => 'Vegan', 'type' => 'meal', 'description' => 'Vegan cuisine'],
            ['name' => 'Pastries', 'type' => 'meal', 'description' => 'Pastries and desserts'],
            ['name' => 'Intercontinental', 'type' => 'meal', 'description' => 'Intercontinental cuisine'],
            ['name' => 'Chinese', 'type' => 'meal', 'description' => 'Chinese cuisine'],
        ];

            foreach ($categories as $categoryData) {
                DB::table('categories')->insert($categoryData);
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
