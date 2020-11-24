<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \Ramsey\Uuid\Uuid;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate()
    {

        $genre = Genre::create([
            'name' => 'Genre1',
        ]);



        $genre->refresh();

        $this->assertEquals('Genre1', $genre->name);
        $this->assertTrue($genre->is_active);
        $this->assertTrue(Uuid::isValid($genre->id)); // test if uuid is gereted correctly

        // create as inacive
        $genreAsInactive = Genre::create([
            'name' => 'Genre1',
            'is_active' => false,
        ]);

        $genreAsInactive->refresh();

        $this->assertFalse($genreAsInactive->is_active);

        // create as inacive
        $genreAsActive = Genre::create([
            'name' => 'Genre1',
            'is_active' => true,
        ]);

        $genreAsActive->refresh();

        $this->assertTrue($genreAsActive->is_active);
    }

    public function testDelete()
    {
        $genre1 = Genre::create([
            'name' => '111'
        ]);

        $genre2 = Genre::create([
            'name' => '222'
        ]);

        $this->assertCount(2, Genre::all());

        $genre1->delete();
        $this->assertCount(1, Genre::all());

        $genre2->delete();
        $this->assertCount(0, Genre::all());
    }
}
