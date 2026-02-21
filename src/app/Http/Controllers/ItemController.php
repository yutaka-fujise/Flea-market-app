<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // mylistを見ようとして未ログイン → /login へ
        if ($request->query('tab') === 'mylist' && !auth()->check()) {
            return redirect('/login');
        }

        // ベースクエリ
        $query = Item::with('user')
            ->withCount(['favorites', 'orders']);

        // mylist のときだけ「自分がいいねした商品」に絞る
        if ($request->query('tab') === 'mylist') {
            $query->whereHas('favorites', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $keyword = $request->query('q');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('brand', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }

        $items = $query->latest()->get();

        return view('items.index', compact('items'));
    }


    public function show($item_id)
    {
        $item = Item::with(['user', 'comments.user'])
            ->withCount(['favorites', 'orders'])
            ->findOrFail($item_id);

        $isFavorited = false;

        if (Auth::check()) {
            $isFavorited = Favorite::where('user_id', Auth::id())
                ->where('item_id', $item->id)
                ->exists();
        }

        return view('items.show', compact('item', 'isFavorited'));
    }


    public function toggleFavorite($item_id)
    {
        $userId = auth()->id();

        $favorite = Favorite::where('user_id', $userId)
            ->where('item_id', $item_id)
            ->first();

        if ($favorite) {
            // いいね済み → 解除
            $favorite->delete();
        } else {
            // 未いいね → 登録
            Favorite::create([
                'user_id' => $userId,
                'item_id' => $item_id,
            ]);
        }

        return back();
    }


    public function storeComment(Request $request, $item_id)
    {
        $request->validate([
            'comment' => ['required', 'string', 'max:255'],
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return back();
    }

    public function create()
{
    $categories = Category::all();
    $conditions = Condition::all();

    return view('items.sell', compact('categories', 'conditions'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'brand' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string'],
        'price' => ['required', 'integer', 'min:1'],
        'category_id' => ['required', 'exists:categories,id'],
        'condition_id' => ['required', 'exists:conditions,id'],
        'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
    ]);

    $imagePath = $request->file('image')->store('items', 'public');

    Item::create([
        'name' => $validated['name'],
        'brand' => $validated['brand'],
        'description' => $validated['description'],
        'price' => $validated['price'],
        'category_id' => $validated['category_id'],
        'condition_id' => $validated['condition_id'],
        'image' => $imagePath,
        'user_id' => Auth::id(),
    ]);

    return redirect()->route('mypage', ['page' => 'sell']);
}
}
