<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Order::factory(10)->create();
        Category::factory()->createMany(
            [
                ['name' => 'Laptops'],
                ['name' => 'Phones'],
                ['name' => 'Tablets'],
            ]
        );

        Brand::factory()->createMany([
            ['name' => 'Dell', 'category_id' => 1],
            ['name' => 'Asus', 'category_id' => 1],
            ['name' => 'Apple', 'category_id' => 2],
            ['name' => 'Samsung', 'category_id' => 2],
            ['name' => 'Lenovo', 'category_id' => 3],
            ['name' => 'Google', 'category_id' => 3],
        ]);
    }
}
