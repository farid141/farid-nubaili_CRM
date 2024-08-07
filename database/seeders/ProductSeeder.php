<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Broadband Internet (DSL)',
                'desc' => 'Digital Subscriber Line Internet service',
                'price' => 300000,
            ],
            [
                'name' => 'Broadband Internet (Fiber Optic)',
                'desc' => 'High-speed fiber optic Internet service',
                'price' => 500000,
            ],
            [
                'name' => 'Business Email Hosting',
                'desc' => 'Professional email hosting service with spam and virus filtering',
                'price' => 100000,
            ],
            [
                'name' => 'Cloud Storage',
                'desc' => 'Secure cloud storage solutions for businesses',
                'price' => 200000,
            ],
            [
                'name' => 'VoIP Services',
                'desc' => 'Voice over IP services for residential and business customers',
                'price' => 150000,
            ],
            // Add more products/services as needed
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
