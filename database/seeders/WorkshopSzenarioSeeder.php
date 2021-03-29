<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkshopSzenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resources = [
            'Sawing Machine 1',
            'Sawing Machine 2',
            'Sawing Machine 3',
            'Sawing Machine 4',
            'Milling Machine 1',
            'Milling Machine 2',
            'Planer',
            'Painting',
            'Assembling',
            'Delivery',
        ];
        $i = 1;
        foreach ($resources as $resource) {
            DB::table('resources')->insert([
                'name' => $resource,
                'pos' => $i++,
            ]);
        }

        $rules = [
            ['Wardrobe "Herbert"',3,0,0,255,1],
            ['Wardrobe "Carola"',4,255,127,0,2],
            ['Individual Wardrobe for Mr. Miller',5,0,255,0,3],
            ['Stool "Bertha"',3,180,0,180,4],
            ['Cleaning',1,255,0,0,5],
            ['Maintainance',5,255,255,0,6],
        ];

        $i = 1;
        foreach ($rules as $rule) {
            DB::table('rules')->insert([
                'name' => $rule[0],
                'duration' => $rule[1],
                'r' => $rule[2],
                'g' => $rule[3],
                'b' => $rule[4],
                'pos' => $i++,
            ]);
        }

        $config = [
            ['start', '2020-06-21'],
            ['resource_term', 'Geräte'],
            ['rule_term', 'Aufträge'],
        ];
        foreach ($config as $configEntry) {
            DB::table('ganttconfig')->insert([
                'id' => $configEntry[0],
                'value' => $configEntry[1],
            ]);
        }
    }
}
