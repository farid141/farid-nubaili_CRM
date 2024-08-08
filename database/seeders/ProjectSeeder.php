<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sales = User::where('level', 'Sales')->get()->pluck('id')->toArray();
        $managers = User::where('level', 'Manager')->get()->pluck('id')->toArray();
        $leads = Lead::all()->pluck('id')->toArray();
        $products = Product::all()->toArray();

        foreach (range(1, 10) as $key) {
            $project = Project::create([
                'lead_id' => fake()->randomElement($leads),
                'name' => fake()->company,
                'desc' => fake()->sentence,
                'start_date' => fake()->date,
                'status' => Arr::random(['pending', 'approved', 'rejected']),
                'manager_id' => fake()->randomElement($managers),
                'sales_id' => fake()->randomElement($sales),
            ]);

            $total = 0;
            foreach (range(1, 3) as $prodIndex) {
                $product = fake()->randomElement($products);
                $quantity = fake()->numberBetween(1, 5);
                $sub_total = $product['price'] * $quantity;
                $total += $sub_total;

                ProjectDetail::create([
                    'project_id' => $project->id,
                    'product_id' => $product['id'],
                    'price' => $product['price'],
                    'subtotal' => $product['price'] * $quantity,
                    'quantity' => $quantity,
                ]);
            }
            $project['total'] = $total;
            $project->save();
        }
    }
}
