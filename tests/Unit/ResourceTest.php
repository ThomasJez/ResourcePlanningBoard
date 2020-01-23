<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Resource;
use Illuminate\Http\Response;

class ResourceTest extends TestCase
{
    public function test_can_show_resource() {

        $resource = factory(Resource::class)->create();

        $this->get(route('resource.show', $resource))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_can_create_resource() {
        $resourceData = [
            'name' => $this->faker->sentence,
            'note' => $this->faker->paragraph,
            'pos' => 1
        ];
        $this->post(route('resource.store'), $resourceData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($resourceData);
    }

    public function test_can_update_resource() {
        $resource = factory(Resource::class)->create();
        $resourceData = [
            'name' => $this->faker->sentence,
            'note' => $this->faker->paragraph
        ];
        $this->put(route('resource.update', $resource), $resourceData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($resourceData);
    }

    public function test_can_delete_resource() {
        $resource = factory(Resource::class)->create();
        $this->delete(route('resource.delete', $resource))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_validates_create_resource() {
        $resourceData = [
            'name' => '',
            'note' => $this->faker->paragraph,
            'pos' => 1
        ];
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('resource.store'), $resourceData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_validates_update_resource() {
        $resource = factory(Resource::class)->create();
        $resourceData = [
            'name' => '',
            'note' => $this->faker->paragraph,
        ];
        $this->withHeaders(['Accept' => 'application/json'])
            ->put(route('resource.update', $resource), $resourceData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_can_show_resource_positions() {
        factory(Resource::class)->create();
        factory(Resource::class)->create();
        $this->get(route('resource.position.show'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_can_update_resource_positions() {
        $resource1 = factory(Resource::class)->create();
        $resource2 = factory(Resource::class)->create();
        $posData = [
            $resource1->id => [
                'id' => $resource1->id,
                'pos' => $this->faker->numberBetween(),
            ],
            $resource2->id => [
                'id' => $resource2->id,
                'pos' => $this->faker->numberBetween(),
            ],
        ];
        $this->post(route('resource.position.update'), $posData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Resources reorderd']);
    }

    public function test_update_resource_positions_fails() {
        $resource1 = factory(Resource::class)->create();
        $resource2 = factory(Resource::class)->create();
        $posData = [
            $resource1->id => [
                'id' => $resource1->id,
                'pos' => '',
            ],
            $resource2->id => [
                'id' => $resource2->id,
                'pos' => $this->faker->numberBetween(),
            ],
        ];
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('resource.position.update'), $posData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}
