<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('products')->insert([
            ['name' => 'Widget A', 'description' => 'Simple widget', 'price' => 19.99, 'stock' => 100, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Widget B', 'description' => 'Advanced widget', 'price' => 49.50, 'stock' => 50, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gadget', 'description' => 'Useful gadget', 'price' => 9.99, 'stock' => 200, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
