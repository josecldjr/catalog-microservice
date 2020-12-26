<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('categories.store'), []);

        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('categories.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);

        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

        $category = factory(Category::class)->create();
        $response = $this->json(
            'PUT',
            route(
                'categories.update',
                ['category' => $category->id]
            ),
            [
                'name' => str_repeat('a', 256),
                'is_active' => 'a'
            ]
        );

        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
    }

    protected function assertInvalidationRequired($response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors((['is_active']))
            ->assertJsonFragment(([
                \Lang::get('validation.required', ['attribute' => 'name'])
            ]));
    }

    protected function assertInvalidationMax($response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }

    protected function assertInvalidationBoolean($response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active'])

            ]);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('categories.store'),  [
            'name' => 'test'
        ]);

        $id = $response->json('id');

        $category = Category::find($id);
        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNUll($response->json('description'));

        //

        $response = $this->json('POST', route('categories.store'),  [
            'name' => 'test',
            'is_active' => false,
            'description' => 'some text here'
        ]);

        $id = $response->json('id');

        $category = Category::find($id);
        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());
        $this->assertFalse($response->json('is_active'));
        $this->assertEquals($response->json('description'), 'some text here');
    }

    public function testUpdte()
    {
        $category = factory(Category::class)->create([
            'is_active' => false,
            'description' => 'old description'
        ]);

        $response = $this->json('PUT', route(
            'categories.update',
            ['category' => $category->id]
        ),  [
            'name' => 'test-updated',
            'is_active' => true,
            'description' => 'new description'
        ]);

        $id = $response->json('id');

        $category = Category::find($id);
        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'name' => 'test-updated',
                'is_active' => true,
                'description' => 'new description',
            ]);

        //

        $category = factory(Category::class)->create([
            'is_active' => true,
            'description' => 'old descripton'
        ]);

        $response = $this->json('PUT', route(
            'categories.update',
            ['category' => $category->id]
        ),  [
            'name' => 'test-updated',
            'is_active' => true,
            'description' => ''
        ]);

        $id = $response->json('id');

        $category = Category::find($id);
        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'is_active' => true,
                'description' => null,
            ]);
    }
}
