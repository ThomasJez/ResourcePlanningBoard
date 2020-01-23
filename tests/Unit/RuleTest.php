<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rule;
use Illuminate\Http\Response;

class RuleTest extends TestCase
{

    /**
     *
     * @return void
     */
    public function test_can_show_rule() {

        $resource = factory(Rule::class)->create();

        $this->get(route('rule.show', $resource))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_can_create_rule() {
        $ruleData = [
            'name' => $this->faker->sentence,
            'note' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(0, 21),
            'r' => $this->faker->numberBetween(0, 255),
            'g' => $this->faker->numberBetween(0, 255),
            'b' => $this->faker->numberBetween(0, 255),
            'pos' => 1
        ];
        $this->post(route('rule.store'), $ruleData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($ruleData);
    }

    public function test_can_update_rule() {
        $rule = factory(Rule::class)->create();
        $ruleData = [
            'name' => $this->faker->sentence,
            'note' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(1, 21),
            'r' => $this->faker->numberBetween(0, 255),
            'g' => $this->faker->numberBetween(0, 255),
            'b' => $this->faker->numberBetween(0, 255),
        ];
        $this->put(route('rule.update', $rule), $ruleData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($ruleData);
    }

    public function test_can_delete_rule() {
        $rule = factory(Rule::class)->create();
        $this->delete(route('rule.delete', $rule))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_validates_create_rule() {
        $ruleData = [
            'name' => '',
            'note' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(1, 21),
            'r' => $this->faker->numberBetween(0, 255),
            'g' => $this->faker->numberBetween(0, 255),
            'b' => $this->faker->numberBetween(0, 255),
            'pos' => 1
        ];
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('rule.store'), $ruleData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_validates_update_rule() {
        $rule = factory(Rule::class)->create();
        $ruleData = [
            'name' => '',
            'note' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(1, 21),
            'r' => $this->faker->numberBetween(0, 255),
            'g' => $this->faker->numberBetween(0, 255),
            'b' => $this->faker->numberBetween(0, 255),
        ];
        $this->withHeaders(['Accept' => 'application/json'])
            ->put(route('rule.update', $rule), $ruleData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_can_show_rule_positions() {
        factory(Rule::class)->create();
        factory(Rule::class)->create();
        $this->get(route('rule.position.show'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_can_update_rule_positions() {
        $rule1 = factory(Rule::class)->create();
        $rule2 = factory(Rule::class)->create();
        $posData = [
            $rule1->id => [
                'id' => $rule1->id,
                'pos' => $this->faker->numberBetween(),
            ],
            $rule2->id => [
                'id' => $rule2->id,
                'pos' => $this->faker->numberBetween(),
            ],
        ];
        $this->post(route('rule.position.update'), $posData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Rules reorderd']);
    }

    public function test_update_rule_positions_fails() {
        $rule1 = factory(Rule::class)->create();
        $rule2 = factory(Rule::class)->create();
        $posData = [
            $rule1->id => [
                'id' => $rule1->id,
                'pos' => '',
            ],
            $rule2->id => [
                'id' => $rule2->id,
                'pos' => $this->faker->numberBetween(),
            ],
        ];
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('rule.position.update'), $posData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
