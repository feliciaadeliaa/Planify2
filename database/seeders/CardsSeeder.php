<?php

namespace Database\Seeders;

use App\Models\CardModel;
use App\Models\ColumnModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $columns = ColumnModel::all();
        
        // Create 5 cards for each column
        foreach ($columns as $column) {
            foreach (range(1, 5) as $index) {
                CardModel::create([
                    'column_id' => $column->id,
                    'card_name' => fake()->sentence,
                    'status' => 1,
                    'position' => $index,
                ]);
            }
        }
    }
}
