<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

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
        $data = [
            'name' => ''
        ];
        
        $this->assertInvalidationInStoreAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256),
        ];
        
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        
        // $data = [ 
        //     'is_active' => 'a'
        // ];
        // $this->assertInvalidationInStoreAction($data, 'boolean');


        // $this->assertInvalidationMax($response);
        // $this->assertInvalidationBoolean($response);

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

        // $this->assertInvalidationMax($response);
        // $this->assertInvalidationBoolean($response);
    }

    protected function assertInvalidationRequired($response)
    {

        $this
            ->assertInvalidationsFields($response, ['name'], 'required', []);
        $response 
            ->assertJsonMissingValidationErrors(['is_active']);
    }

    protected function assertInvalidationMax($response)
    {
        $this
            ->assertInvalidationsFields($response, ['name'], 'max.string', [ 'max' => 255]);
       
    }

    protected function assertInvalidationBoolean($response)
    {
        $this->assertInvalidationsFields($response, ['is_active'], 'boolean', [ 'max' => 255]);

    }

    public function testStore()
    {
        $data = [
            'name' => 'test'
        ];

        $this->assertStore($data, $data + ['description' => null, 'is_active' => true]);
        // $response = $this->json('POST', route('categories.store'), [ 'name' => 'test' ] );

        // $id = $response->json('id');

        // $category = Category::find($id);
        // $response
        //     ->assertStatus(201)
        //     ->assertJson($category->toArray());
        // $this->assertTrue($response->json('is_active'));
        // $this->assertNUll($response->json('description'));

        // //

        // $response = $this->json('POST', route('categories.store'),  [
        //     'name' => 'test',
        //     'is_active' => false,
        //     'description' => 'some text here'
        // ]);

        // $id = $response->json('id');

        // $category = Category::find($id);
        // $response
        //     ->assertStatus(201)
        //     ->assertJson($category->toArray());
        // $this->assertFalse($response->json('is_active'));
        // $this->assertEquals($response->json('description'), 'some text here');
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

    function testDelete() {
        
        $category = factory(Category::class)->create();
        $category = Category::find($category->id);
        $this->assertNotNull($category);
        
        $response = $this->json('DELETE', route('categories.destroy', ['category' => $category->id]));
        
        
        $category = Category::find($category->id);
        
        $this->assertNull($category);
        
    }


    function routeStore() {
        return route('categories.store');
    }

    protected function model() {
        return Category::class;
    }
}
