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

        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

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
        $item = Item::with([
                'user',
                'comments.user.profile',
                'categories',
                'condition',
            ])
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
        $item = Item::findOrFail($item_id);

        // 自分の商品はいいね不可
        if ($item->user_id === Auth::id()) {
            return back()->with('error', '自分の商品にはいいねできません');
        }

        $userId = auth()->id();

        $favorite = Favorite::where('user_id', $userId)
            ->where('item_id', $item_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            Favorite::create([
                'user_id' => $userId,
                'item_id' => $item_id,
            ]);
        }

        return back();
    }


    public function storeComment(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // 自分の商品はコメント不可
        if ($item->user_id === Auth::id()) {
            return back()->with('error', '自分の商品にはコメントできません');
        }

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
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:1'],
            'condition_id' => ['required', 'exists:conditions,id'],

            'categories' => ['required', 'array'],
            'categories.*' => ['exists:categories,id'],

            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'name.required' => '商品名を入力してください',
        ]);

        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'name' => $validated['name'],
            'brand' => $validated['brand'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition_id' => $validated['condition_id'],
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        $item->categories()->sync($validated['categories']);

        return redirect()->route('mypage', ['page' => 'sell']);
    }
}