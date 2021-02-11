<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

abstract class BasicCrundController extends Controller
{

    protected abstract function model();

    // protected abstract function routeStore(); 

    // protected abstract function routeUpdate();       


    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean'
    ];

    public function index()
    {
        return $this->model()::all();
    }

    // public function create(Request $request)
    // {
    // }

    // public function store(Request $request)
    // {
    //     $this->validate($request, $this->rules);
    //     $category =  Category::create($request->all());
    //     $category->refresh();
    //     return $category;
    // }

    // public function show(Category $category)
    // {
    //     return $category;
    // }

    // public function edit(Category $category)
    // {
    //     //
    // }

    // public function update(Request $request, Category $category)
    // {
    //     $this->validate($request, $this->rules);
    //     $category->update($request->all());

    //     return $category;
    // }

    // public function destroy(Category $category)
    // {
    //     $category->delete();
    //     return response()->noContent(); // 204 - No Content
    // }
}
