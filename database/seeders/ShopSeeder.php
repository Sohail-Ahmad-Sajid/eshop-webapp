<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create(['name'=>'Admin','email'=>'admin@gmail.com','password'=>Hash::make('123456'),'role'=>'admin']);
        User::create(['name'=>'Customer','email'=>'customer@gmail.com','password'=>Hash::make('123456'),'role'=>'customer']);
        $cats = ['Electronics','Clothing','Home','Sports'];
        foreach($cats as $cat) {
            Category::create(['name'=>$cat,'slug'=>str()->slug($cat)]);
        }
        
        Product::create(['category_id'=>1,'name'=>'iPhone 15','slug'=>'iphone-15','price'=>999,'stock'=>50,'description'=>'Latest iPhone']);
        Product::create(['category_id'=>1,'name'=>'MacBook Pro','slug'=>'macbook-pro','price'=>1999,'stock'=>30,'description'=>'M3 Chip']);
        Product::create(['category_id'=>2,'name'=>'Nike Shoes','slug'=>'nike-shoes','price'=>120,'stock'=>100,'description'=>'Running shoes']);
        Product::create(['category_id'=>3,'name'=>'Coffee Maker','slug'=>'coffee-maker','price'=>80,'stock'=>40,'description'=>'Automatic coffee machine']);




    }
}
