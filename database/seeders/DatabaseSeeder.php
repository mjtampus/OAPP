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
use Illuminate\Support\Facades\Hash;
use App\Models\ProductsAttributesValues;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
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

        Products::factory()->createMany([
            [
            'name' => 'Samsung Galaxy S24 Ultra',
            'description' => 'A high-end smartphone with advanced features.',
            'price' => 78000,
            'category_id' => 2,
            'brand_id' => 4,
            'is_new_arrival' => true,
            'is_featured' => true,
            'stock' => 200,
            'product_image_dir' => '01JMXDJXH49TX4RWPQHCGQWCGV.jpg',
            ],
            [
            'name' => 'Iphone 15 Pro Max',
            'description' => 'IPhone 15 Pro Max. Forged in titanium and featuring the groundbreaking A17 Pro chip, a customizable Action button, and the most powerful iPhone camera system',
            'price' => 79190,
            'category_id' => 2,
            'brand_id' => 3,
            'is_new_arrival' => true,
            'is_featured' => true,
            'stock' => 200,
            'product_image_dir' => '01JMXW6Z7FNN38K10VT85MKKJE.webp',
            ],
            
        ]);

        ProductsAttributes::factory()->createMany([
            ['type' => 'color', 'products_id' => 1],
            ['type' => 'sizes', 'products_id' => 1],
            ['type' => 'color', 'products_id' => 2],
            ['type' => 'sizes', 'products_id' => 2],
        ]);

        ProductsAttributesValues::factory()->createMany([
            ['value' => 'Titanium Blue', 'products_attributes_id' => 1 , 'code' => '#7a72e8'],
            ['value' => 'Titanium Green', 'products_attributes_id' => 1 , 'code' => '#51c240'],
            ['value' => 'Titanium Grey', 'products_attributes_id' => 1 , 'code' => '#adadad'],
            ['value' => '512GB', 'products_attributes_id' => 2 , 'code' => NULL],
            ['value' => '1TB', 'products_attributes_id' => 2 , 'code' => NULL],
            ['value' => 'Titanium Black', 'products_attributes_id' => 3 , 'code' => '#464543' ],
            ['value' => 'Titanium Blue', 'products_attributes_id' => 3 , 'code' => '#515662' ],
            ['value' => 'Titanium Natural', 'products_attributes_id' => 3 , 'code' => '#b1ada4' ],
            ['value' => 'Titanium White', 'products_attributes_id' => 3 , 'code' => '#f4f1e9' ],
            ['value' => '256GB', 'products_attributes_id' => 4 , 'code' => NULL],
            ['value' => '512GB', 'products_attributes_id' => 4 , 'code' => NULL],
            ['value' => '1TB', 'products_attributes_id' => 4 , 'code' => NULL],

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
           ],[
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,6],[4,10]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD71MS5KZEPXEGTMV7J3.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,7],[4,10]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD897H1FEEBE1QYE4A8P.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,8],[4,10]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD8D89MHVZ5W67KWXWX3.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,9],[4,10]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD8FGV80C584DN7D113F.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,6],[4,11]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD8T4NPSCN137RX2Q06Y.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,7],[4,11]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD90WJHV0SBHHMQS5DVC.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,8],[4,11]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD94H28W442ZM0247REJ.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,9],[4,11]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD9H2NFFV5Z44PEYH26T.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,6],[4,12]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD9T9VBG5PPVHXJEJYBJ.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,7],[4,12]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBD9YETMC59684WH0H82P.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,8],[4,12]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBDA9B97F44A0N58KNCWT.webp',
           ],
           [
            'products_id' => 2,
            'sku' => 'Iphone 15 Pro Max - '.Str::random(5),
            'attributes' => [[3,9],[4,12]],
            'price' => 71190,
            'stock' => 200,
            'sku_image_dir' => '01JMXWBDAR0KJXPMSTYBQ488VG.webp',
           ],

           

        ]);
    }
}
