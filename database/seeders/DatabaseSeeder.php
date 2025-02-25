<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Category;
use App\Models\Products;
use App\Models\ProductsSKU;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\ProductsAttributes;
use App\Models\ProductsAttributesValues;

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

        Products::factory()->create([
            'name' => 'Samsung Galaxy S24 Ultra',
            'description' => 'A high-end smartphone with advanced features.',
            'price' => 78000,
            'category_id' => 2,
            'brand_id' => 4,
            'is_new_arrival' => true,
            'is_featured' => true,
            'stock' => 200,
            'product_image_dir' => '01JMXDJXH49TX4RWPQHCGQWCGV.jpg',

        ]);

        ProductsAttributes::factory()->createMany([
            ['type' => 'color', 'products_id' => 1],
            ['type' => 'sizes', 'products_id' => 1],
        ]);

        ProductsAttributesValues::factory()->createMany([
            ['value' => 'Titanium Blue', 'products_attributes_id' => 1 , 'code' => '#7a72e8'],
            ['value' => 'Titanium Green', 'products_attributes_id' => 1 , 'code' => '#51c240'],
            ['value' => 'Titanium Grey', 'products_attributes_id' => 1 , 'code' => '#adadad'],
            ['value' => '512GB', 'products_attributes_id' => 2 , 'code' => NULL],
            ['value' => '1TB', 'products_attributes_id' => 2 , 'code' => NULL],
        ]);

        ProductsSKU::factory()->createMany([
           [
            'products_id' => 1,
            'sku' => 'SamsungS24 - '.Str::random(5),
            'attributes' => [[1,1],[2,4]],
            'price' => 78000,
            'stock' => 200,
            'sku_image_dir' => '01JMXFQ7YJ7076TFMWYHTXR8NA.jpg',
           ],
           [
            'products_id' => 1,
            'sku' => 'SamsungS24 - '.Str::random(5),
            'attributes' => [[1,2],[2,4]],
            'price' => 78000,
            'stock' => 200,
            'sku_image_dir' => '01JMXDMRAM7KY33631SZH855M9.jpeg',
           ],
           [
            'products_id' => 1,
            'sku' => 'SamsungS24 - '.Str::random(5),
            'attributes' => [[1,3],[2,4]],
            'price' => 78000,
            'stock' => 200,
            'sku_image_dir' => '01JMXFQ7Z9DPPPC2T70DBVAVYB.jpg',
           ],
           [
            'products_id' => 1,
            'sku' => 'SamsungS24 - '.Str::random(5),
            'attributes' => [[1,1],[2,5]],
            'price' => 78000,
            'stock' => 200,
            'sku_image_dir' => '01JMXFQ7ZBRYZD0370ZQ0TNJPF.jpg',
           ],
           [
            'products_id' => 1,
            'sku' => 'SamsungS24 - '.Str::random(5),
            'attributes' => [[1,2],[2,5]],
            'price' => 78000,
            'stock' => 200,
            'sku_image_dir' => '01JMXFQ7ZDVBY3S21ZRZAX753Q.jpeg',
           ],
           [
            'products_id' => 1,
            'sku' => 'SamsungS24 - '.Str::random(5),
            'attributes' => [[1,3],[2,5]],
            'price' => 78000,
            'stock' => 200,
            'sku_image_dir' => '01JMXFQ7ZEC0T1R6DPD7GP3Y7S.jpg',
           ],
           

        ]);
    }
}
