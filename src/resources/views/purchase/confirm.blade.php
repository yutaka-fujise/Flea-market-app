@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase-confirm.css') }}">
@endsection

@section('content')

<div class="confirm page">

    <div class="confirm-layout">
        {{-- 左カラム --}}
        <div class="confirm-left">

            {{-- 商品情報 --}}
            <div class="confirm-item">
                <div class="confirm-thumb">
                    <img
                        class="confirm-thumb__img"
                        src="{{ str_starts_with($item->image, 'items/') ? asset('storage/'.$item->image) : asset($item->image) }}"
                        alt="{{ $item->name }}"
                    >
                </div>

                <div class="confirm-item__info">
                    <div class="confirm-item__name">{{ $item->name }}</div>
                    <div class="confirm-item__price">¥ {{ number_format($item->price) }}</div>
                </div>
            </div>

            <hr class="confirm-hr">

            {{-- フォーム（購入するをsubmit） --}}
            <form id="purchase-confirm-form"
                  action="/purchase/{{ $item->id }}"
                  method="POST"
                  class="confirm-form">
                @csrf

                {{-- 支払い方法 --}}
                <section class="confirm-section">
                    <h3 class="confirm-section__title">支払い方法</h3>

                    <div class="confirm-payment">
                        <select id="payment_method"
                                class="confirm-select"
                                name="payment_method">
                            <option value="convenience" {{ $paymentMethod === 'convenience' ? 'selected' : '' }}>
                                コンビニ払い
                            </option>
                            <option value="card" {{ $paymentMethod === 'card' ? 'selected' : '' }}>
                                カード払い
                            </option>
                        </select>
                    </div>
                </section>

                <hr class="confirm-hr">

                {{-- 配送先 --}}
                <section class="confirm-section">
                    <div class="confirm-section__head confirm-section__head--between">
                        <h3 class="confirm-section__title">配送先</h3>
                        <a class="confirm-link"
                           href="{{ route('purchase.address.edit', $item->id) }}">
                            変更する
                        </a>
                    </div>

                    <div class="confirm-address">
                        <p class="confirm-postal">
                            〒{{ optional($profile)->postal_code ?? '---' }}
                        </p>

                        <p class="confirm-address-line">
                            {{ optional($profile)->address ?? '' }}
                            {{ optional($profile)->building ?? '' }}
                        </p>
                    </div>
                </section>

                <hr class="confirm-hr">

            </form>
        </div>

        {{-- 右カラム --}}
        <aside class="confirm-right">

            <div class="confirm-summary">
                <div class="confirm-summary__row">
                    <div class="confirm-summary__label">商品代金</div>
                    <div class="confirm-summary__value">
                        ¥ {{ number_format($item->price) }}
                    </div>
                </div>

                <div class="confirm-summary__row confirm-summary__row--border">
                    <div class="confirm-summary__label">支払い方法</div>
                    <div id="payment_method_text"
                         class="confirm-summary__value">
                        {{ $paymentMethod === 'card' ? 'カード払い' : 'コンビニ払い' }}
                    </div>
                </div>
            </div>

            @if(!$profile)
                <p class="confirm-warn">※配送先を登録してから購入してください</p>
                <button class="confirm-buy is-disabled"
                        type="button"
                        disabled>
                    購入する
                </button>
            @else
                <button class="confirm-buy"
                        type="submit"
                        form="purchase-confirm-form">
                    購入する
                </button>
            @endif

        </aside>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('payment_method');
    const textEl = document.getElementById('payment_method_text');

    const labels = {
        convenience: 'コンビニ払い',
        card: 'カード払い',
    };

    const render = () => {
        textEl.textContent = labels[select.value] ?? '';
    };

    render(); // 初期表示も同期
    select.addEventListener('change', render);
});
</script>

@endsection