<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Support\Str;

class RestaurantSeeder extends Seeder {
    public function run(): void {
        // Admin user
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@foodiehub.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Categories
        $categories = [
            ['name' => 'Starters',    'slug' => 'starters'],
            ['name' => 'Main Course', 'slug' => 'main-course'],
            ['name' => 'Pizza',       'slug' => 'pizza'],
            ['name' => 'Beverages',   'slug' => 'beverages'],
            ['name' => 'Desserts',    'slug' => 'desserts'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Menu Items
        $items = [
            ['category_id'=>1, 'name'=>'Veg Spring Rolls',      'price'=>149, 'description'=>'Crispy rolls with veg filling', 'is_featured'=>true],
            ['category_id'=>1, 'name'=>'Paneer Tikka',           'price'=>249, 'description'=>'Marinated paneer grilled to perfection', 'is_featured'=>true],
            ['category_id'=>2, 'name'=>'Butter Chicken',         'price'=>320, 'description'=>'Creamy tomato chicken curry', 'is_featured'=>true],
            ['category_id'=>2, 'name'=>'Dal Makhani',            'price'=>220, 'description'=>'Slow-cooked black lentils', 'is_featured'=>false],
            ['category_id'=>3, 'name'=>'Margherita Pizza',       'price'=>299, 'description'=>'Classic cheese and tomato', 'is_featured'=>true],
            ['category_id'=>3, 'name'=>'BBQ Chicken Pizza',      'price'=>399, 'description'=>'Smoky BBQ with chicken', 'is_featured'=>false],
            ['category_id'=>4, 'name'=>'Mango Lassi',            'price'=>89,  'description'=>'Refreshing mango yogurt drink', 'is_featured'=>false],
            ['category_id'=>4, 'name'=>'Cold Coffee',            'price'=>99,  'description'=>'Iced blended coffee', 'is_featured'=>false],
            ['category_id'=>5, 'name'=>'Gulab Jamun',            'price'=>99,  'description'=>'Soft khoya balls in sugar syrup', 'is_featured'=>false],
            ['category_id'=>5, 'name'=>'Chocolate Brownie',      'price'=>149, 'description'=>'Warm fudgy brownie with ice cream', 'is_featured'=>true],
        ];

        foreach ($items as $item) {
            MenuItem::create(array_merge($item, [
                'slug' => Str::slug($item['name']) . '-' . uniqid(),
                'is_available' => true,
            ]));
        }
    }
}