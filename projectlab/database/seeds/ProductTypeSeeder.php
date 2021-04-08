<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->insert([
            [
                'name' => 'Sofas',
                'image' => 'thumbnail_sofa.jpg',
            ],
            [
                'name' => 'Bed frames',
                'image' => 'thumbnail_bedframe.jpg',
            ],
            [
                'name' => 'Bookshelves',
                'image' => 'thumbnail_bookshelf.jpg',
            ],
            [
                'name' => 'Chairs',
                'image' => 'thumbnail_chair.jpg',
            ],
            [
                'name' => 'Dining Tables',
                'image' => 'thumbnail_dining_table.jpg',
            ],
            [
                'name' => 'Food Containers',
                'image' => 'thumbnail_food_container.jpg',
            ],
            [
                'name' => 'Office Chairs',
                'image' => 'thumbnail_office_chair.jpg',
            ],
            [
                'name' => 'Chest of drawers',
                'image' => 'thumbnail_drawers.jpg',
            ],
        ]);
    }
}
