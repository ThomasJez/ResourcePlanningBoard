<?php

namespace Tests\Unit;

use App\Activity;
use App\Ganttconfig;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory;
use Illuminate\Http\Response;

class GanttchartTest extends TestCase
{
    use DatabaseMigrations;
    //use RefreshDatabase;
    protected $faker = null;
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }

    public function test_getall() {
        factory(Ganttconfig::class)->states('start')->create();
        factory(Ganttconfig::class)->states('resource_term')->create();
        factory(Ganttconfig::class)->states('rule_term')->create();
        $this->withHeaders(['Accept' => 'application/json'])
            ->get(route('everything'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'resources',
                'activities',
                'rules',
                'start',
                'resource_term',
                'rule_term'
            ]);
    }

}
