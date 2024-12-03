<?php

namespace Database\Seeders;

use App\Models\ColumnModel;
use App\Models\ProjectModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = ProjectModel::all();
        $columnNames = ['Backlog', 'On Progress', 'Done'];

        // Create 3 columns for each project
        foreach ($projects as $project) {
            foreach ($columnNames as $index => $name) {
                ColumnModel::create([
                    'project_id' => $project->id,
                    'column_name' => $name,
                    'status' => 1,
                    'position' => $index,
                ]);
            }
        }
    }
}
