<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreController extends TestCase
{
    use DatabaseMigrations;
    
    public function testList() {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.index'));


        $response
            ->assertStatus(200)
            ->assertJson([$genre->toArray()]);
    }

    public function testShow()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.show', ['genre' => $genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function testInvalidInput() {

        // test name length -----
        $response = $this->json('POST', route('genres.store'), []);
 

        $response = $this->json('POST', route('genres.store'), [
            'name' => str_repeat('a', 256), 
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);


        // test boolean val in is_active -----
        $response = $this->json('POST', route('genres.store'), []);
 
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'somename',
            'is_active' => 'a'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' =>'is active'])
            ]);

        // test name required -----
        $response = $this->json('POST', route('genres.store'), []);

        $response = $this->json('POST', route('genres.store'), [
            'name' => null
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors((['is_active']))
            ->assertJsonFragment(([
                \Lang::get('validation.required', ['attribute' => 'name'])
            ]));
    }

    public function testStore() {

        // create genre as active
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'my-test-genre',
            'is_active' => true
        ]);

        $response
        ->assertStatus(201);
        $this->assertTrue($response->json('is_active'));
        $this->assertEquals($response->json('name'), 'my-test-genre');

        // create genre as inactive
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'my-test-genre',
            'is_active' => false
        ]);

        $response
        ->assertStatus(201);
        $this->assertFalse($response->json('is_active'));
        $this->assertEquals($response->json('name'), 'my-test-genre');

    }
 
    public function testUpdate() {

        // test name change and is_active field
        $genre = factory(Genre::class)->create([
            'name' => 'some genre',
            'is_active' => true
        ]);

        
        $response = $this->json('PUT', route(
            'genres.update', 
            ['genre' => $genre->id]
            ),
            [
                'name'=> 'EDITED-GENRE',
                'is_active' => false
                ]
            );
            $genre->refresh();
            
        $response
        ->assertStatus(200)
        ->assertJson($genre->toArray())
        ->assertJsonFragment([
            'name'=> 'EDITED-GENRE',
            'is_active' => false
        ]);

    }

    public function testDelete() {
        $genre = factory(Genre::class)->create();
        $genre = Genre::find($genre->id);
        $this->assertNotNull($genre);
        
        $response = $this->json('DELETE', route('genres.destroy', ['genre' => $genre->id]));
        
        
        $genre = Genre::find($genre->id);
        
        $this->assertNull($genre);
    }
}
