<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;  // ✅ Fixed casing
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index() {
        $categories = Category::where('is_active', true)
            ->withCount('menuItems')
            ->get();
        $featured = MenuItem::where('is_featured', true)  // ✅ Fixed casing
            ->where('is_available', true)
            ->with('category')
            ->take(8)
            ->get();
        return view('menu.index', compact('categories', 'featured'));
    }

    public function menu(){
        $categories = category::where('is_active',true)->withCount('menuItems')->with(['menuItems'=>function($q){
            $q->where('is_available',true);
        }])->get();

        return view('menu.index',compact('categories'));
    }

    public function search(Request $request){
         $query = $request->q;
         $categoryId = $request->category;
         $items = MenuItem::where('is_available',true)
         ->when($query,function($q)  use ($query){
       $q->where(function($q) use ($query){
        $q->where('name','like','%'.$query.'%')
        ->orWhere('description','like','%'.$query.'%');
       });
    })
      ->when($categoryId,function($q) use ($categoryId){
        $q->where('categoryId',$categoryId);
      })
       ->with('category')
       ->paginate(12);

       $categories = category::where('is_active',true)->get();

       return view('menu.search',compact('items','query','categories','categoryId'));
    }

    public function category(Category $category) {  // ✅ Fixed Capital C
        $items      = $category->menuItems()->where('is_available', true)->paginate(12); // ✅ Fixed $items
        $categories = Category::where('is_active', true)->get(); // ✅ Fixed
        return view('menu.category', compact('category', 'items', 'categories'));
    }

    public function show(MenuItem $menuItem) {
        $related = MenuItem::where('category_id', $menuItem->category_id)
            ->where('id', '!=', $menuItem->id)
            ->where('is_available', true)
            ->take(4)->get();
        return view('menu.show', compact('menuItem', 'related'));
    }
}