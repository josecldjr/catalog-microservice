<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{

    public function testFillable()
    {
        $category = new Category();
        $fillable = ['name', 'description', 'is_active'];
        $this->assertEquals(
            $fillable,
            $category->getFillable()
        );
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];

        $classTraits = array_values(class_uses(Category::class));

        $this->assertEquals($traits, $classTraits);
    }

    public function testCasts()
    {
        $casts = ['id' => 'string'];
        $category = new Category();

        $this->assertEquals($casts, $category->getCasts());
    }

    public function testDates()
    {
        $dates = ['deleted_at', 'created_at',  'updated_at'];
        $category = new Category();

        foreach ($dates as $date) {
            $this->assertContains($date, $category->getDates());
        }
    }

    public function testIncrementing()
    {
        $category = new Category();

        $this->assertFalse($category->incrementing);
    }
}
