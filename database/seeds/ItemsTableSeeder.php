<?php

use Illuminate\Database\Seeder;
use App\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = ['Desk', 'Chair', 'Table', 'Sofa'];

        foreach ($items as $item) {
            Item::create(['name' => $item]);
        }
    }
}
