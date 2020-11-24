<?php

namespace Tests\Feature\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use \Ramsey\Uuid\Uuid;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use phpDocumentor\Reflection\Types\Boolean;

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


    public function testCreate()
    {
        $category = Category::create([
            'name' => 'test1',
        ]);
        $category->refresh();

        $this->assertEquals('test1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        // with null
        $categoryWithNull = Category::create([
            'name' => 'test1',
            'description' => null,
        ]);
        $categoryWithNull->refresh();

        $this->assertNull($categoryWithNull->description);

        // with some text
        $categoryWithSomeText = Category::create([
            'name' => 'test1',
            'description' => 'test_description',
        ]);
        $categoryWithSomeText->refresh();

        $this->assertEquals('test_description', $categoryWithSomeText->description);

        // with is_active false
        $categoryWithActiveFalse = Category::create([
            'name' => 'test1',
            'is_active' => false,
        ]);
        $categoryWithActiveFalse->refresh();

        $this->assertFalse($categoryWithActiveFalse->is_active);

        // with is_active true
        $categoryWithActiveTrue = Category::create([
            'name' => 'test1',
            'is_active' => true,
        ]);
        $categoryWithActiveTrue->refresh();

        $this->assertTrue($categoryWithActiveTrue->is_active);

        // test if uuid is valid
        $categoryTestUUID = Category::create([
            'name' => 'test1',
        ]);
        $categoryTestUUID->refresh();

        $this->assertTrue(Uuid::isValid($categoryTestUUID->id));
    }
}
