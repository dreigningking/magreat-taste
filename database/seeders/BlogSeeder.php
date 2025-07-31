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
        $categories = [
            ['name' => 'Recipes', 'type' => 'post', 'description' => 'Delicious recipes and cooking guides'],
            ['name' => 'Cooking Tips', 'type' => 'post', 'description' => 'Professional cooking tips and techniques'],
            ['name' => 'Chef Stories', 'type' => 'post', 'description' => 'Behind-the-scenes stories from the kitchen'],
            ['name' => 'Events', 'type' => 'post', 'description' => 'Cooking events and workshops'],
            ['name' => 'Ingredients', 'type' => 'post', 'description' => 'Ingredient guides and selection tips'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

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
                'views_count' => 1200,
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
                'views_count' => 856,
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
                'views_count' => 1500,
            ],
            [
                'title' => 'The Ultimate Guide to Selecting Fresh Seafood',
                'excerpt' => 'Learn how to identify the freshest seafood at your local market. From checking the eyes and gills to understanding seasonal availability, this guide will help you make the best choices.',
                'content' => 'Selecting fresh seafood is both an art and a science. The key indicators of freshness vary by type of seafood, but there are some universal principles to follow.

For whole fish, the eyes should be clear and bright, not cloudy or sunken. The gills should be bright red or pink, not brown or gray. The flesh should be firm and spring back when pressed, and there should be no strong fishy odor - fresh fish should smell clean and briny.

When selecting shellfish like clams, mussels, and oysters, they should be alive and responsive. The shells should be tightly closed or close when tapped. Avoid any with broken shells or that don\'t close when handled.

For shrimp, look for firm, translucent flesh with no black spots or discoloration. The shells should be intact and the tails should be curled under the body.

Understanding seasonal availability is also crucial. Different types of seafood are at their peak during different times of the year, and buying in season ensures the best quality and often better prices.

Building a relationship with your fishmonger is invaluable. A good fishmonger can provide information about the source, catch date, and preparation recommendations for any seafood you\'re considering.',
                'category_id' => Category::where('name', 'Ingredients')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'tags' => 'Seafood,Fresh,Selection,Quality',
                'views_count' => 723,
            ],
            [
                'title' => 'Upcoming Cooking Classes: Master the Art of French Pastry',
                'excerpt' => 'Join me for an exclusive series of French pastry classes. From croissants to éclairs, learn the techniques that make French pastries so special. Limited spots available!',
                'content' => 'I\'m excited to announce a new series of French pastry classes that will take you through the fundamentals of classic French baking. These hands-on workshops will cover everything from basic techniques to advanced pastry skills.

The curriculum includes:
- Laminated dough techniques for croissants and pain au chocolat
- Choux pastry for éclairs and profiteroles
- Pâte sucrée for tarts and tartlets
- French buttercream and ganache techniques
- Decorative piping and finishing skills

Each class is limited to 12 participants to ensure personalized attention and hands-on practice. All skill levels are welcome, from complete beginners to experienced bakers looking to refine their techniques.

The classes will be held in our professional kitchen, equipped with all the tools and ingredients you\'ll need. You\'ll take home your creations and receive detailed recipe booklets with step-by-step instructions.

Registration is now open for the spring session, with classes beginning next month. Early bird pricing is available for those who register before the end of this week.',
                'category_id' => Category::where('name', 'Events')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'tags' => 'Classes,French,Pastry,Baking',
                'views_count' => 945,
            ],
            [
                'title' => 'Artisan Sourdough Bread: A Complete Beginner\'s Guide',
                'excerpt' => 'Master the art of sourdough bread making with this comprehensive guide. From creating your own starter to achieving the perfect crust, every step is explained in detail.',
                'content' => 'Sourdough bread making is a rewarding journey that connects us to ancient baking traditions. The process begins with creating and maintaining a sourdough starter - a living culture of wild yeast and bacteria that gives sourdough its distinctive flavor and texture.

Creating your starter is simple but requires patience. Mix equal parts flour and water, and let it sit at room temperature. Over the next few days, feed it regularly with fresh flour and water. You\'ll know it\'s ready when it doubles in size within 4-6 hours of feeding and has a pleasant, slightly sour aroma.

The key to successful sourdough bread is understanding the fermentation process. The starter needs time to develop flavor and structure, which is why sourdough breads typically require longer fermentation times than commercial yeast breads.

Shaping and scoring the dough are crucial steps that affect both the appearance and texture of the final loaf. Proper shaping creates tension in the dough, while scoring allows for controlled expansion during baking.

Baking in a Dutch oven or on a baking stone helps create the perfect crust and oven spring. The high heat and steam environment mimics the conditions of traditional bread ovens.',
                'category_id' => Category::where('name', 'Recipes')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(17),
                'tags' => 'Bread,Sourdough,Baking,Artisan',
                'views_count' => 678,
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }
    }
}
