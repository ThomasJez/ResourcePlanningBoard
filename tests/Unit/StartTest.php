<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Ganttconfig;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;

class StartTest extends TestCase
{
    public function test_can_show_start() {

        $start = factory(Ganttconfig::class)->states('start')->create();
        $this->get(route('start.show', $start))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_can_update_start() {
        factory(Ganttconfig::class)->states('start')->create();
        $startData = $this->faker->date;
        $this->put(route('start.update'), [(new \DateTime($startData))->format('Y-m-d')]);
        $this->assertDatabaseHas('ganttconfig', ['value' => $startData]);
    }

    public function test_validates_update_start() {
        factory(Ganttconfig::class)->create();
        $startData = $this->faker->date;
        $this->withHeaders(['Accept' => 'application/json'])
            ->put(route('start.update'), [(new \DateTime($startData))->format('j.n.y')])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
