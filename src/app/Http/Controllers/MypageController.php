<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Item;
use App\Models\Profile;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page'); // buy / sell / null

        if ($page === 'buy') {
            $orders = Order::with('item')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return view('mypage.index', compact('user', 'page', 'orders'));
        }

        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)
                ->latest()
                ->get();

            return view('mypage.index', compact('user', 'page', 'items'));
        }

        return view('mypage.index', compact('user', 'page'));
    }


    public function editProfile()
    {
        $user = Auth::user();
        $profile = $user->profile; // nullでもOK

        return view('mypage.profile', compact('user', 'profile'));
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:8'],
            'address'     => ['required', 'string', 'max:255'],
            'building'    => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $validated['name'],
        ]);

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name'        => $validated['name'],
                'postal_code' => $validated['postal_code'],
                'address'     => $validated['address'],
                'building'    => $validated['building'] ?? null,
            ]
        );

        return redirect()->route('mypage')
            ->with('success', 'プロフィールを更新しました');
    }
}