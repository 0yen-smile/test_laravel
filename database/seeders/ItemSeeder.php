<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; #DB::table('items')->insert() を使用する場合「Illuminate\Support\Facades\DB」をインポートする必要がある

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('items')->insert([
            ['name' =>'商品A', 'price' =>1000,'created_at' => now(), 'updated_at' => now()],
            ['name' =>'商品B', 'price' =>500,'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }
}
