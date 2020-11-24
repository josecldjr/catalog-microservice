<?php

namespace Tests\Feature\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Category::class, 1)->create();
        $category = Category::create([
            'name' => 'test1',
            'description' => 'asdasd'
        ]);

        $categories = Category::all();
        $categoryKeys = array_keys($categories->first()->getAttributes());

        $this->assertCount(2, $categories);
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'description',
            'is_active',
            'created_at',
            'updated_at',
            'deleted_at',
        ], $categoryKeys);
    }
}
