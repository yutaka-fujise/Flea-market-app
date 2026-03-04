<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PurchaseController extends Controller
{
    /**
     * 購入確認画面
     */
    public function confirm(Request $request, Item $item)
    {
        if ($request->query('success')) {

            // すでに注文がある場合は作らない（二重防止）
            if (!$item->orders()->exists()) {
                \App\Models\Order::create([
                    'user_id'        => Auth::id(),
                    'item_id'        => $item->id,
                    'payment_method' => session('payment_method', 'card'),
                ]);
            }

            return redirect()
                ->route('items.show', $item->id)
                ->with('success', '購入が完了しました');
        }

        if ($request->query('canceled')) {
            return redirect()
                ->route('purchase.confirm', $item->id)
                ->with('error', '決済をキャンセルしました');
        }

        // 防衛ロジック（自分の商品 / 売り切れ）
        if ($res = $this->guardPurchasable($item)) {
            return $res;
        }

        // 支払い方法（セッション優先）
        $paymentMethod = session('payment_method', 'card');

        // プロフィール（住所）
        $profile = Auth::user()->profile;

        // Blade側が将来 $address を使っても壊れないように残す
        $address = $this->formatAddress($profile);

        return view('purchase.confirm', compact('item', 'paymentMethod', 'address', 'profile'));
    }


    /**
     * 購入確定（Stripe Checkoutへ）
     */
    public function store(Request $request, Item $item)
    {
        // 防衛ロジック（自分の商品 / 売り切れ）
        if ($res = $this->guardPurchasable($item)) {
            return $res;
        }

        // 支払い方法（セッション > リクエスト > デフォルト）
        $paymentMethod = session('payment_method')
            ?? $request->input('payment_method')
            ?? 'card';

        if (!in_array($paymentMethod, ['card', 'convenience'], true)) {
            return redirect()
                ->route('purchase.confirm', $item->id)
                ->with('error', '支払い方法が不正です');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::create([
            'mode' => 'payment',
            'payment_method_types' => $paymentMethod === 'convenience'
                ? ['konbini']
                : ['card'],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency'    => 'jpy',
                    'unit_amount' => (int) $item->price,
                    'product_data' => [
                        'name' => $item->name,
                    ],
                ],
            ]],
            // ✅ 必ず purchase.confirm に戻す（success/cancel を拾う）
            'success_url' => url("/purchase/{$item->id}?success=1"),
            'cancel_url'  => url("/purchase/{$item->id}?canceled=1"),
        ]);

        return redirect($session->url);
    }


    /**
     * 支払い方法選択画面（任意：別ページ）
     */
    public function editPayment(Item $item)
    {
        if ($res = $this->guardPurchasable($item)) {
            return $res;
        }

        $paymentMethod = session('payment_method', 'card');

        return view('purchase.payment', compact('item', 'paymentMethod'));
    }


    /**
     * 支払い方法保存（confirm画面のselect変更で使用）
     */
    public function updatePayment(Request $request, Item $item)
    {
        if ($res = $this->guardPurchasable($item)) {
            return $res;
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:card,convenience'],
        ]);

        session(['payment_method' => $validated['payment_method']]);

        return redirect()->route('purchase.confirm', $item->id);
    }


    /**
     * 配送先編集画面
     */
    public function editAddress(Item $item)
    {
        if ($res = $this->guardPurchasable($item)) {
            return $res;
        }

        $profile = Auth::user()->profile; // nullでもOK

        return view('purchase.address', compact('item', 'profile'));
    }


    /**
     * 配送先更新
     */
    public function updateAddress(Request $request, Item $item)
    {
        if ($res = $this->guardPurchasable($item)) {
            return $res;
        }

        $validated = $request->validate([
            'postal_code' => ['required', 'string', 'max:8'],
            'address'     => ['required', 'string', 'max:255'],
            'building'    => ['nullable', 'string', 'max:255'],
        ]);

        Profile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'name'        => Auth::user()->name,
                'postal_code' => $validated['postal_code'],
                'address'     => $validated['address'],
                'building'    => $validated['building'] ?? null,
            ]
        );

        return redirect()
            ->route('purchase.confirm', $item->id)
            ->with('success', '配送先を更新しました');
    }

    private function guardPurchasable(Item $item)
    {
        if ($item->user_id === Auth::id()) {
            return redirect()
                ->route('items.show', $item->id)
                ->with('error', 'あなたの商品は購入できません');
        }

        if ($item->orders()->exists()) {
            return redirect()
                ->route('items.show', $item->id)
                ->with('error', '売り切れのため購入できません');
        }

        return null;
    }


    /**
     * 住所表示用
     */
    private function formatAddress(?Profile $profile): string
    {
        if (!$profile) {
            return '未登録（配送先を変更してください）';
        }

        $address = "〒{$profile->postal_code} {$profile->address}";

        if (!empty($profile->building)) {
            $address .= " {$profile->building}";
        }

        return $address;
    }
}