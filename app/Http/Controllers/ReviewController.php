<?php

namespace App\Http\Controllers;
use App\Models\order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Symfony\Contracts\Service\Attribute\Required;

class ReviewController extends Controller
{
    public function create ($orderNumber){
        $order = Order::where('order_number', $orderNumber)
        ->where('user_id',Auth::id())
        ->where('status','delivered')
        ->with('Items.menuItem')
        ->firstOrFail();

        $reviewedItems = Review::where('order_id',$order->id)
        ->where('user_id',Auth::id())
        ->pluck('menu_item_id')
        ->toArray();

          return view('reviews.create',compact('order','reviewedItems'));
    }


    public function store(Request $request){
        $request->validate([
          'order_id'=>'required|exists:orders,id',
          'reviews'=>'required|array',
          'reviews.*.menu_items_id'=>'required|exists:menu_items,id',
          'reviews.*.rating'=>'required|integer|min:1|max:5',
          'reviews.*.comments'=>'required|string|mix::500',
        ]);

                $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        foreach ($request->reviews as $reviewData) {
            // Duplicate check
            $exists = Review::where('user_id', Auth::id())
                ->where('menu_item_id', $reviewData['menu_item_id'])
                ->where('order_id', $order->id)
                ->exists();

            if (!$exists) {
                Review::create([
                    'user_id'      => Auth::id(),
                    'menu_item_id' => $reviewData['menu_item_id'],
                    'order_id'     => $order->id,
                    'rating'       => $reviewData['rating'],
                    'comment' => $reviewData['comment'] ?? null,
                    'is_approved'  => true,
                ]);
            }
        }

        return redirect()->route('orders.history')
            ->with('success', 'Review submit ho gaya! Shukriya! 🌟');
    }

    // ✅ Review delete karo
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        $review->delete();
        return back()->with('success', 'Review deleted!');
    }
}
