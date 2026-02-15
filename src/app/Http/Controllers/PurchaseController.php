<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class PurchaseController extends Controller
{
    // 購入確認画面
public function confirm(Item $item)
{
    if ($item->user_id === Auth::id()) {
        return redirect("/item/{$item->id}")->with('error', 'あなたの商品は購入できません');
    }

    if ($item->orders()->exists()) {
        return redirect("/item/{$item->id}")->with('error', '売り切れのため購入できません');
    }

    $paymentMethod = session('payment_method', 'card');

    $profile = Auth::user()->profile;

    if ($profile) {
        $address = "〒{$profile->postal_code} {$profile->address}";
        if (!empty($profile->building)) {
            $address .= " {$profile->building}";
        }
    } else {
        $address = "未登録（配送先を変更してください）";
    }

    return view('purchase.confirm', compact('item', 'paymentMethod', 'address', 'profile'));
}


    // 購入確定
    public function store(Request $request, Item $item)
{
    if ($item->user_id === Auth::id()) {
        return redirect("/item/{$item->id}")->with('error', 'あなたの商品は購入できません');
    }

    if ($item->orders()->exists()) {
        return redirect("/item/{$item->id}")->with('error', '売り切れのため購入できません');
    }

    // セッション優先（なければrequest、なければcard）
    $paymentMethod = session('payment_method')
        ?? $request->input('payment_method')
        ?? 'card';

    // 値を固定（想定外を弾く）
    if (!in_array($paymentMethod, ['card', 'convenience'], true)) {
        return redirect("/purchase/{$item->id}")->with('error', '支払い方法が不正です');
    }

    Order::create([
        'user_id' => Auth::id(),
        'item_id' => $item->id,
        'payment_method' => $paymentMethod,
    ]);

    // 購入が終わったらセッション掃除
    session()->forget('payment_method');

    return redirect("/item/{$item->id}")->with('success', '購入が完了しました');
}

// 支払い方法選択画面
public function editPayment(Item $item)
{
    // confirmと同じ防衛（URL直叩き対策）
    if ($item->user_id === Auth::id() || $item->orders()->exists()) {
        return redirect("/item/{$item->id}")->with('error', '購入できない商品です');
    }

    $paymentMethod = session('payment_method', 'card');
    return view('purchase.payment', compact('item', 'paymentMethod'));
}

// 支払い方法保存
public function updatePayment(Request $request, Item $item)
{
    if ($item->user_id === Auth::id() || $item->orders()->exists()) {
        return redirect("/item/{$item->id}")->with('error', '購入できない商品です');
    }

    $validated = $request->validate([
        'payment_method' => ['required', 'in:card,convenience'],
    ]);

    session(['payment_method' => $validated['payment_method']]);

    return redirect("/purchase/{$item->id}");

    
}
public function editAddress(Item $item)
{
    if ($item->user_id === Auth::id() || $item->orders()->exists()) {
        return redirect("/item/{$item->id}")->with('error', '購入できない商品です');
    }

    $profile = Auth::user()->profile; // nullでもOK
    return view('purchase.address', compact('item', 'profile'));
}

public function updateAddress(Request $request, Item $item)
{
    if ($item->user_id === Auth::id() || $item->orders()->exists()) {
        return redirect("/item/{$item->id}")->with('error', '購入できない商品です');
    }

    $validated = $request->validate([
        'postal_code' => ['required', 'string', 'max:8'],
        'address'     => ['required', 'string', 'max:255'],
        'building'    => ['nullable', 'string', 'max:255'],
    ]);

    Profile::updateOrCreate(
    ['user_id' => Auth::id()],
    [
        'name' => Auth::user()->name,
        'postal_code' => $validated['postal_code'],
        'address' => $validated['address'],
        'building' => $validated['building'] ?? null,
    ]
);

    return redirect()->route('purchase.confirm', $item->id)
        ->with('success', '配送先を更新しました');
}
}
