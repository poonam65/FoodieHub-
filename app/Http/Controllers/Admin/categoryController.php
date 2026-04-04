<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('menuItems')->latest()->paginate(10);
        return view('admin.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $request->validate(['name' => 'required|string|max:255']);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('categories', 'public');
    }

    // ✅ Unique slug banao
    $slug = Str::slug($request->name);
    $count = Category::where('slug', 'like', $slug . '%')->count();
    if ($count > 0) {
        $slug = $slug . '-' . ($count + 1);
    }

    Category::create([
        'name'      => $request->name,
        'slug'      => $slug,           // ✅ Unique slug
        'image'     => $imagePath,
        'is_active' => $request->boolean('is_active', true),
    ]);

    return redirect()->route('admin.categories.index')->with('success', 'Category created!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate(['name'=>'required|string|max:255']);

        $imagePath = $category->image;
        if($request->hasFile('image')){
            $imagePath =$request ->file('image')->store('categories','public');
        }

          $slug = Str::slug($request->name);
    $count = Category::where('slug', 'like', $slug . '%')
        ->where('id', '!=', $category->id)
        ->count();
    if ($count > 0) {
        $slug = $slug . '-' . ($count + 1);
    }
        $category->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'image'     => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success','category update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success','category deleted!');
    }
}
