@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase-confirm.css') }}">
@endsection

@section('content')

<div class="confirm page">

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="confirm-flash confirm-flash--success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="confirm-flash confirm-flash--error">
            {{ session('error') }}
        </div>
    @endif

    <div class="confirm-layout">

        {{-- 左カラム --}}
        <div class="confirm-left">

            {{-- 商品情報 --}}
            <div class="confirm-item">
                <div class="confirm-thumb">
                    <img
                        class="confirm-thumb__img"
                        src="{{ asset('storage/'.$item->image) }}"
                        alt="{{ $item->name }}"
                    >
                </div>

                <div class="confirm-item__info">
                    <div class="confirm-item__name">
                        {{ $item->name }}
                    </div>
                    <div class="confirm-item__price">
                        ¥ {{ number_format($item->price) }}
                    </div>
                </div>
            </div>

            <hr class="confirm-hr">

            {{-- 購入フォーム（右の購入ボタンから submit する） --}}
            <form
                id="purchase-confirm-form"
                action="{{ route('purchase.store', $item->id) }}"
                method="POST"
                class="confirm-form">
                @csrf
            </form>

            {{-- 支払い方法（変更したら即保存→confirmへ戻る） --}}
            <section class="confirm-section">
                <h3 class="confirm-section__title">支払い方法</h3>

                <form
                    method="POST"
                    action="{{ route('purchase.payment.update', $item->id) }}"
                    class="confirm-payment">
                    @csrf

                    <select
                        id="payment_method"
                        class="confirm-select"
                        name="payment_method"
                        onchange="this.form.submit()">

                        <option
                            value="convenience"
                            {{ $paymentMethod === 'convenience' ? 'selected' : '' }}>
                            コンビニ払い
                        </option>

                        <option
                            value="card"
                            {{ $paymentMethod === 'card' ? 'selected' : '' }}>
                            カード払い
                        </option>

                    </select>
                </form>
            </section>

            <hr class="confirm-hr">

            {{-- 配送先 --}}
            <section class="confirm-section">
                <div class="confirm-section__head confirm-section__head--between">
                    <h3 class="confirm-section__title">配送先</h3>
                    <a
                        class="confirm-link"
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

        </div>

        {{-- 右カラム --}}
        <aside class="confirm-right">

            <div class="confirm-summary">
                <div class="confirm-summary__row">
                    <div class="confirm-summary__label">
                        商品代金
                    </div>
                    <div class="confirm-summary__value">
                        ¥ {{ number_format($item->price) }}
                    </div>
                </div>

                <div class="confirm-summary__row confirm-summary__row--border">
                    <div class="confirm-summary__label">
                        支払い方法
                    </div>
                    <div class="confirm-summary__value">
                        {{ $paymentMethod === 'card' ? 'カード払い' : 'コンビニ払い' }}
                    </div>
                </div>
            </div>

            @if(!$profile)
                <p class="confirm-warn">
                    ※配送先を登録してから購入してください
                </p>
                <button
                    class="confirm-buy is-disabled"
                    type="button"
                    disabled>
                    購入する
                </button>
            @else
                <button
                    class="confirm-buy"
                    type="submit"
                    form="purchase-confirm-form">
                    購入する
                </button>
            @endif

        </aside>

    </div>
</div>

@endsection