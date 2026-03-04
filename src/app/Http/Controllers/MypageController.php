<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Item;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user()->load('profile');
        $page = $request->query('page'); // buy / sell / null

        if ($page === 'buy') {
            $orders = Order::with('item')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return view('mypage.index', compact('user', 'page', 'orders'));
        }

        // ✅ sell または null は items を渡す（ここがポイント）
        $items = Item::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('mypage.index', compact('user', 'page', 'items'));
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
            'name'          => ['required', 'string', 'max:255'],
            'postal_code'   => ['required', 'string', 'max:8'],
            'address'       => ['required', 'string', 'max:255'],
            'building'      => ['nullable', 'string', 'max:255'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user->update([
            'name' => $validated['name'],
        ]);

        // 既存プロフィール取得（古い画像削除に使う）
        $profile = Profile::where('user_id', $user->id)->first();

        $data = [
            'name'        => $validated['name'],
            'postal_code' => $validated['postal_code'],
            'address'     => $validated['address'],
            'building'    => $validated['building'] ?? null,
        ];

        if ($request->hasFile('profile_image')) {
            // 任意：古い画像を消す（保存先がpublicの場合のみ）
            if ($profile && $profile->profile_image) {
                Storage::disk('public')->delete($profile->profile_image);
            }

            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect('/')
            ->with('success', 'プロフィールを更新しました');
    }


    public function setupProfile()
    {
        $user = Auth::user();

        // すでにプロフィールがある人は編集へ or 一覧へ（どっちでもOK）
        if ($user->profile) {
            return redirect()->route('mypage.profile.edit');
        }

        $profile = $user->profile; // null想定

        return view('profile.setup', compact('user', 'profile'));
    }


    public function storeProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'postal_code'   => ['required', 'string', 'max:8'],
            'address'       => ['required', 'string', 'max:255'],
            'building'      => ['nullable', 'string', 'max:255'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user->update(['name' => $validated['name']]);

        $data = [
            'name'        => $validated['name'],
            'postal_code' => $validated['postal_code'],
            'address'     => $validated['address'],
            'building'    => $validated['building'] ?? null,
        ];

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect('/')
            ->with('success', 'プロフィールを登録しました');
    }
}