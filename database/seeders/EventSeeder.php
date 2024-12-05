<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;


class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $days = [[1, 3], 5, 6, 9, [12, 13]];
        $fake = fake('idID');
        $today = date('y-m-d');

        foreach ($days as $day){
            if(is_array($day)) {
            $event []=[
                'title' => $fake->sentence(3),
                'start_date' => date('y-m-d', strtotime($today. '+ '. $day[0].' days')),
                'end_date' => date('y-m-d', strtotime($today. '+ '. $day[1].' days')),
                'category' => $fake->randomElement(['success', 'danger', 'warning', 'info']),
                'created_at' => now(),
                'updated_at' => now(),
            ];

        } else {
            $event []=[
                'title' => $fake->sentence(3),
                'start_date' => date('y-m-d', strtotime($today. '+ '. $day.' days')),
                'end_date' => date('y-m-d', strtotime($today. '+ '. $day.' days')),
                'category' => $fake->randomElement(['success', 'danger', 'warning', 'info']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            }
        }

        Event::insert($event);
    }
}
