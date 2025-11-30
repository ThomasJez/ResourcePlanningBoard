<?php

namespace Tests\Unit;

use App\Activity;
use App\Ganttconfig;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;
    public function test_can_show_activity() {

        $activity = factory(Activity::class)->create();

        $this->get(route('activity.show', $activity))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_can_create_activity() {
        factory(Ganttconfig::class)->states('start')->create();
        $days = $this->faker->numberBetween(1, 21);
        $resourceId = $this->faker->numberBetween();
        $ruleId = $this->faker->numberBetween();
        $duration = $this->faker->numberBetween(1, 21);
        $r = $this->faker->numberBetween(0, 255);
        $g = $this->faker->numberBetween(0, 255);
        $b = $this->faker->numberBetween(0, 255);
        $startString = (new \DateTime(Ganttconfig::find('start')->value))
            ->add(new \DateInterval('P' . $days . 'D'))
            ->format('Y-m-d');
        $activityRequestData = [
            'x' => $days,
            'resourceId' => $resourceId,
            'ruleId' => $ruleId,
            'duration' => $duration,
            'r' => $r,
            'g' => $g,
            'b' => $b
        ];
        $activityReturnData = [
            'start' => $startString,
            'resource_id' => $resourceId,
            'rule_id' => $ruleId,
            'duration' => $duration,
            'r' => $r,
            'g' => $g,
            'b' => $b
        ];
        $this->post(route('activity.store'), $activityRequestData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($activityReturnData);
    }

    public function test_can_update_activity() {
        $activity = factory(Activity::class)->create();
        factory(Ganttconfig::class)->states('start')->create();
        $days = $this->faker->numberBetween(1, 21);
        $resourceId = $this->faker->numberBetween();
        $duration = $this->faker->numberBetween(1, 21);
        $startString = (new \DateTime(Ganttconfig::find('start')->value))
            ->add(new \DateInterval('P' . $days . 'D'))
            ->format('Y-m-d');
        $activityRequestData = [
            'x' => $days,
            'resourceId' => $resourceId,
            'duration' => $duration,
        ];
        $activityReturnData = [
            'start' => $startString,
            'resource_id' => $resourceId,
            'duration' => $duration,
        ];
        $this->put(route('activity.update', $activity), $activityRequestData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($activityReturnData);
    }

    public function test_can_delete_activity() {
        $activity = factory(Activity::class)->create();
        $this->delete(route('activity.delete', $activity))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
