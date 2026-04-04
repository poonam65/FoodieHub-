<?php
namespace App\Http\Controllers;

use App\Models\MenuItem;  // ✅ Fixed
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index() {
        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $id) {
        $item = MenuItem::findOrFail($id);  // ✅ Fixed capital O
        $cart = session()->get('cart', []); // ✅ Fixed lowercase session()

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'     => $item->name,
                'price'    => $item->price,
                'image'    => $item->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', $item->name . ' added to cart!'); // ✅ Fixed 'sucess' typo
    }

    public function update(Request $request, $id) {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, (int) $request->quantity);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Cart updated!');
    }

    public function remove($id) {
        $cart = session()->get('cart', []);  // ✅ Fixed lowercase
        unset($cart[$id]);
        session()->put('cart', $cart);       // ✅ Fixed lowercase
        return back()->with('success', 'Item removed from cart.');
    }

    public function clear() {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared!');
    }
}