<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        

        // Get the first user (or create one if none exists)
        $user = User::first() ?? User::factory()->create();

        // Create sample posts
        $posts = [
            [
                'title' => 'The Perfect Truffle Arancini: A Step-by-Step Guide',
                'excerpt' => 'Discover the secrets behind creating the most delicious truffle arancini. From selecting the right rice to achieving the perfect golden crust, this comprehensive guide will elevate your cooking skills.',
                'content' => 'Creating the perfect truffle arancini is an art that combines traditional Italian techniques with modern culinary innovation. This dish, originating from Sicily, has become a beloved appetizer worldwide. The key to success lies in the quality of ingredients and the precise execution of each step.

First, selecting the right rice is crucial. Arborio rice is the traditional choice, known for its high starch content which creates the creamy texture essential for arancini. The rice should be cooked in a rich broth until it reaches the perfect al dente consistency.

The truffle element adds an earthy, luxurious depth to the dish. Whether using fresh black truffles or high-quality truffle oil, the key is to incorporate it subtly so it enhances rather than overwhelms the other flavors.

Shaping the arancini requires patience and practice. The rice mixture should be cooled completely before forming into balls, and the breading process should be done with care to ensure even coverage.

Finally, the frying technique is what brings everything together. The oil should be at the perfect temperature - hot enough to create a crisp exterior but not so hot that it burns before the interior is properly heated.',
                'category_id' => Category::where('name', 'Recipes')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'tags' => 'Italian,Truffle,Appetizer,Arborio Rice',
                'featured' => true,
            ],
            [
                'title' => '5 Essential Knife Skills Every Home Cook Should Master',
                'excerpt' => 'Mastering basic knife skills is the foundation of great cooking. Learn the proper techniques for chopping, dicing, and slicing that will make your prep work faster and more efficient.',
                'content' => 'Knife skills are the foundation of professional cooking and can dramatically improve your efficiency and safety in the kitchen. The five essential skills every home cook should master are:

1. **The Claw Grip**: This technique protects your fingers while cutting. Curl your fingertips under and use your knuckles as a guide for the knife blade. This ensures consistent cuts and prevents accidents.

2. **The Rocking Motion**: Used primarily with chef\'s knives, this technique involves rocking the knife back and forth while keeping the tip on the cutting board. This is perfect for mincing herbs and garlic.

3. **Julienne Cut**: Creating thin, uniform strips is essential for many dishes. Start with a rectangular piece of vegetable, slice it into planks, then stack and cut into thin strips.

4. **Brunoise Dice**: This is the finest dice, typically 1/8 inch cubes. It\'s perfect for garnishes and ensures even cooking. Start with julienne cuts, then dice across the strips.

5. **Chiffonade**: This technique is used for leafy herbs and greens. Stack the leaves, roll them tightly, and slice perpendicular to the roll to create thin ribbons.

Practice these techniques regularly, and you\'ll notice a significant improvement in your cooking speed and presentation.',
                'category_id' => Category::where('name', 'Cooking Tips')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'tags' => 'Technique,Knife Skills,Basics,Safety',
            ],
            [
                'title' => 'From Paris to Your Plate: My Culinary Journey',
                'excerpt' => 'Join me as I share the story of my culinary journey from training in Paris to creating memorable dining experiences. Discover the lessons learned and the passion that drives my cooking.',
                'content' => 'My culinary journey began in the heart of Paris, where I spent three transformative years learning from some of the world\'s most respected chefs. The experience taught me that cooking is not just about following recipes - it\'s about understanding ingredients, respecting traditions, and creating memorable experiences.

In Paris, I learned the importance of patience and precision. Every technique, from the perfect omelette to the delicate art of pastry making, required hours of practice and an unwavering attention to detail. The French approach to cooking emphasizes the quality of ingredients and the respect for traditional methods while embracing innovation.

The most valuable lesson I learned was about the connection between food and culture. Every dish tells a story, and every ingredient has a history. This understanding has shaped my approach to cooking and has influenced how I create menus and develop recipes.

Today, I bring these lessons to my own kitchen, combining French techniques with local ingredients and global influences. The journey continues as I explore new flavors, techniques, and ways to share the joy of cooking with others.',
                'category_id' => Category::where('name', 'Chef Stories')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'tags' => 'Journey,Paris,Inspiration,French Cuisine',
            ],
            [
                'title' => 'The Ultimate Guide to Selecting Fresh Seafood',
                'excerpt' => 'Learn how to identify the freshest seafood at your local market. From checking the eyes and gills to understanding seasonal availability, this guide will help you make the best choices.',
                'content' => 'Selecting fresh seafood is both an art and a science. The key indicators of freshness vary by type of seafood, but there are some universal principles to follow.

For whole fish, the eyes should be clear and bright, not cloudy or sunken. The gills should be bright red or pink, not brown or gray. The flesh should be firm and spring back when pressed, and there should be no strong fishy odor - fresh fish should smell clean and briny.',
                'category_id' => Category::where('name', 'Ingredients')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'tags' => 'Seafood,Freshness,Selection,Quality',
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }

        // Create sample comments for testing
        $this->createSampleComments();
    }

    private function createSampleComments()
    {
        $posts = Post::all();
        
        if ($posts->isEmpty()) {
            return;
        }

        $commentData = [
            [
                'content' => 'This recipe looks amazing! I can\'t wait to try making truffle arancini at home. The step-by-step instructions are so clear.',
                'guest_name' => 'Sarah Johnson',
                'guest_email' => 'sarah.j@example.com',
                'status' => 'pending',
                'ip_address' => '192.168.1.100',
            ],
            [
                'content' => 'I made this last night and it was incredible! The truffle oil really makes a difference. Thanks for sharing this recipe!',
                'guest_name' => 'Mike Chen',
                'guest_email' => 'mike.chen@example.com',
                'status' => 'approved',
                'ip_address' => '192.168.1.101',
                'approved_at' => now()->subDays(2),
                'approved_by' => User::first()->id,
            ],
            [
                'content' => 'Great tips! I\'ve been struggling with knife skills and this really helped. The claw grip technique is a game-changer.',
                'guest_name' => 'Emma Davis',
                'guest_email' => 'emma.davis@example.com',
                'status' => 'approved',
                'ip_address' => '192.168.1.102',
                'approved_at' => now()->subDays(1),
                'approved_by' => User::first()->id,
                'is_featured' => true,
            ],
            [
                'content' => 'This is exactly what I needed! I\'ve been looking for a comprehensive guide to knife skills.',
                'guest_name' => 'David Wilson',
                'guest_email' => 'david.w@example.com',
                'status' => 'pending',
                'ip_address' => '192.168.1.103',
            ],
            [
                'content' => 'Your journey is so inspiring! I love how you combine French techniques with local ingredients.',
                'guest_name' => 'Lisa Rodriguez',
                'guest_email' => 'lisa.r@example.com',
                'status' => 'approved',
                'ip_address' => '192.168.1.104',
                'approved_at' => now()->subDays(3),
                'approved_by' => User::first()->id,
            ],
            [
                'content' => 'This is spam content trying to sell fake products. Please remove.',
                'guest_name' => 'Spam Bot',
                'guest_email' => 'spam@fake.com',
                'status' => 'spam',
                'ip_address' => '192.168.1.105',
            ],
            [
                'content' => 'I tried the seafood selection tips and they worked perfectly! My fish was so fresh.',
                'guest_name' => 'Tom Anderson',
                'guest_email' => 'tom.a@example.com',
                'status' => 'rejected',
                'ip_address' => '192.168.1.106',
                'approved_by' => User::first()->id,
            ],
        ];

        foreach ($commentData as $data) {
            $post = $posts->random();
            $data['post_id'] = $post->id;
            $data['user_agent'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
            
            \App\Models\Comment::create($data);
        }
    }
}
