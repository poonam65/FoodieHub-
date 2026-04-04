<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;  // ✅ Fixed casing
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index() {
        $items = MenuItem::with('category')->latest()->paginate(15);  // ✅ Fixed
        return view('admin.menu_items.index', compact('items'));  // ✅ Fixed path
    }

    public function create() {
        $categories = Category::where('is_active', true)->get();
        return view('admin.menu_items.create', compact('categories'));  // ✅ Fixed path
    }

    public function store(Request $request) {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',  // ✅ Fixed 'nullabe' typo
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_items', 'public');
        }

        MenuItem::create([  // ✅ Fixed casing
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => Str::slug($request->name) . '-' . uniqid(),
            'description'  => $request->description,
            'price'        => $request->price,
            'image'        => $imagePath,
            'is_available' => $request->boolean('is_available', true),  // ✅ Fixed key
            'is_featured'  => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item created!');
    }

    public function edit(MenuItem $menuItem) {  // ✅ Fixed casing
        $categories = Category::where('is_active', true)->get();
        return view('admin.menu_items.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem) {  // ✅ Fixed casing
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',  // ✅ Fixed table name
            'price'       => 'required|numeric|min:0',
        ]);

        $imagePath = $menuItem->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_items', 'public');
        }

        $menuItem->update([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'image'        => $imagePath,
            'is_available' => $request->boolean('is_available', true),
            'is_featured'  => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item updated!');
    }

    public function destroy(MenuItem $menuItem) {  // ✅ Fixed casing
        $menuItem->delete();
        return back()->with('success', 'Menu item deleted!');
    }
}