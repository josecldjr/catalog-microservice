<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function create(Request $request)
    { 
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255]',
            'is_active' => 'boolean'            
        ]);

        return Category::create($request->all());
        //
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(Request $request, Category $category)
    {
        //
    }
 
    public function destroy(Category $category)
    {
        //
    }
}
