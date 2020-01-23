<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $resources = [
            'Sawing Machine 1',
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
            ['resource_term', 'Resources'],
            ['rule_term', 'Rules'],
        ];
        foreach ($config as $configEntry) {
            DB::table('ganttconfig')->insert([
                'id' => $configEntry[0],
                'value' => $configEntry[1],
            ]);
        }
    }
}
