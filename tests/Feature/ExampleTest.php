<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testList()
    {
        // $category = Category::create([
        //     'name' => 'test1',
        //     'description' => 'desc here!'
        // ]);

        factory( Category::class, 1)->create();
        $categories = Category::all();
        $categoryKey = array_keys($categories->first()->getAttributes());

        $this->assertCount(1, $categories);
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'description', 'created_at', 'updated_at', 'deleted_at', 'is_active'
        ], $categoryKey);
    }
}
