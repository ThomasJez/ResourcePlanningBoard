<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobberSzenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resources = [
            'Kijimadaira',
            'Ispat',
            'Winterhoff',
            'Blaufelden',
            'Guzei',
            'XML',
            'Dehmelt',
            'Gosciniak',
            'Nöbeling',
            'Vanderlinden',
            'Bourbaki',
            'Bronstein',
            'Weißhüt'
        ];
        $i = 1;
        foreach ($resources as $resource) {
            DB::table('resources')->insert([
                'name' => $resource,
                'pos' => $i++,
            ]);
        }

        $rules = [
            ['Lydia', 3, 0, 0, 255],
            ['Yvonne', 2, 255, 255, 0],
            ['Sven', 4, 0, 255, 0],
            ['Thomas U.', 1, 255, 0, 0],
            ['Thomas S.', 5, 255, 127, 0],
            ['Micha', 5, 255, 127, 127],
            ['Stephan', 3, 180, 180, 255],
            ['Cynthia', 3, 255, 0, 255]
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

        $activities = [
            ['2020-06-22', 1, 3, 4, 0, 255, 0],
            ['2020-06-22', 2, 1, 3, 0, 0, 255],
            ['2020-06-22', 3, 2, 2, 255, 255, 0],
            ['2020-06-22', 4, 7, 3, 180, 180, 255],
            ['2020-06-22', 5, 5, 5, 255, 127, 0],
            ['2020-06-22', 9, 6, 26, 255, 127, 127],
            ['2020-06-22', 12, 8, 3, 255, 0, 255],
            ['2020-06-26', 3, 4, 1, 255, 0, 0],
            ['2020-06-29', 1, 3, 4, 0, 255, 0],
            ['2020-06-29', 2, 1, 3, 0, 0, 255],
            ['2020-06-29', 3, 2, 2, 255, 255, 0],
            ['2020-06-29', 4, 7, 3, 180, 180, 255],
            ['2020-06-29', 5, 5, 5, 255, 127, 0],
            ['2020-06-29', 12, 8, 3, 255, 0, 255],
            ['2020-07-03', 3, 4, 1, 255, 0, 0],
            ['2020-07-06', 1, 3, 4, 0, 255, 0],
            ['2020-07-06', 2, 1, 3, 0, 0, 255],
            ['2020-07-06', 3, 2, 2, 255, 255, 0],
            ['2020-07-06', 4, 7, 3, 180, 180, 255],
            ['2020-07-06', 5, 5, 5, 255, 127, 0],
            ['2020-07-06', 12, 8, 3, 255, 0, 255],
            ['2020-07-10', 3, 4, 1, 255, 0, 0],
            ['2020-07-13', 1, 3, 4, 0, 255, 0],
            ['2020-07-13', 2, 1, 3, 0, 0, 255],
            ['2020-07-13', 3, 2, 2, 255, 255, 0],
            ['2020-07-13', 4, 7, 3, 180, 180, 255],
            ['2020-07-13', 5, 5, 5, 255, 127, 0],
            ['2020-07-13', 12, 8, 3, 255, 0, 255],
            ['2020-07-17', 3, 4, 1, 255, 0, 0],
        ];
        foreach ($activities as $activity) {
            DB::table('activities')->insert([
                'start' => $activity[0],
                'resource_id' => $activity[1],
                'rule_id' => $activity[2],
                'duration' => $activity[3],
                'r' => $activity[4],
                'g' => $activity[5],
                'b' => $activity[6],
            ]);
        }
        $config = [
            ['start', '2020-06-21'],
            ['resource_term', 'Buchprojekte'],
            ['rule_term', 'Setzer'],
        ];
        foreach ($config as $configEntry) {
            DB::table('ganttconfig')->insert([
                'id' => $configEntry[0],
                'value' => $configEntry[1],
            ]);
        }
    }
}
