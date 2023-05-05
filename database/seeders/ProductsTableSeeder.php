<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $products = [
            [
                'name' => 'Product 1',
                'price' => 10.99,
                'description' => 'This is the first product.',
                'image' => 'https://www.91-img.com/pictures/144646-v8-apple-iphone-14-pro-mobile-phone-large-1.jpg',
            ],
            [
                'name' => 'Product 2',
                'price' => 19.99,
                'description' => 'This is the second product.',
                'image' => 'https://www.91-img.com/gallery_images_uploads/3/8/38b2bbfdb23141b1d26133a7f57bc1b384e5c5eb.jpg?w=0&h=901&q=80&c=1',
            ],
            [
                'name' => 'Product 3',
                'price' => 5.99,
                'description' => 'This is the third product.',
                'image' => 'https://www.91-img.com/pictures/149276-v8-oneplus-nord-2t-mobile-phone-large-1.jpg?tr=q-80',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
