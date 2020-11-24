<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFillable()
    {
        $genre = new Genre();
        $fillable = ['name', 'is_active'];
        $this->assertEqualsCanonicalizing($fillable, $genre->getFillable());
    }

    public function testCasts()
    {
        $casts = [
            'id' => 'string',
            'is_active' => 'boolean'
        ];

        $genre = new Genre();

        $this->assertEquals($casts, $genre->getCasts());
    }

    public function testDates()
    {
        $dates = ['deleted_at', 'created_at',  'updated_at'];
        $genre = new Genre();

        foreach ($dates as $date) {
            $this->assertContains($date, $genre->getDates());
        }
    }

    public function testIncrementing()
    {
        $category = new Genre();

        $this->assertFalse($category->incrementing);
    }
}
