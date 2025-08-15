<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Create sample posts
        $users = array(
            array('id' => '1','slug' => 'local','name' => 'Local','type' => 'meal','is_active' => '1','created_at' => '2023-06-09 12:54:22','updated_at' => '2023-06-09 12:54:22'),
            array('id' => '2','slug' => 'italian','name' => 'Italian','type' => 'meal','is_active' => '1','created_at' => '2023-06-09 12:55:05','updated_at' => '2023-06-09 12:55:05'),
            array('id' => '5','slug' => 'vegan','name' => 'Vegan','type' => 'meal','is_active' => '1','created_at' => '2023-06-09 12:56:23','updated_at' => '2023-06-09 12:56:23'),
            array('id' => '9','slug' => 'pastries','name' => 'Pastries','type' => 'meal','is_active' => '1','created_at' => '2023-06-09 13:39:30','updated_at' => '2023-06-09 13:39:30'),
            array('id' => '10','slug' => 'intercontinental','name' => 'Intercontinental','type' => 'meal','is_active' => '1','created_at' => '2023-06-09 13:40:19','updated_at' => '2023-06-09 13:40:19'),
            array('id' => '11','slug' => 'chinese','name' => 'Chinese','type' => 'meal','is_active' => '1','created_at' => '2023-06-09 13:40:29','updated_at' => '2023-06-09 13:40:29')
            );
        foreach ($users as $user) {
            Category::create([
                'slug' => $user['slug'],
                'name' => $user['name'],
                'is_active' => true,
                'description' => $user['name'],
                'image' => null,
                'type' => 'meal',
            ]);
        }
    }
}
