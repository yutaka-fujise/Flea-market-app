@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')

<div class="detail page">

    <div class="detail-main">
        {{-- 左：画像 --}}
        <div class="detail-image">
            <img
                class="detail-image__img"
                src="{{ str_starts_with($item->image, 'items/') ? asset('storage/'.$item->image) : asset($item->image) }}"
                alt="{{ $item->name }}"
            >
        </div>

        {{-- 右：情報 --}}
        <div class="detail-info">
            <h1 class="detail-title">{{ $item->name }}</h1>
            <p class="detail-brand">{{ $item->brand }}</p>

            <p class="detail-price">
                ¥{{ number_format($item->price) }}
                <span class="detail-price__tax">(税込)</span>
            </p>

            <div class="detail-icons">

                {{-- いいね --}}
                <div class="detail-icon">
                    @auth
                        <form action="{{ route('items.favorite', ['item_id' => $item->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="icon-btn">
                                <img
                                    src="{{ asset(
                                      $isFavorited
                                        ? 'images/material/ハートロゴ_ピンク.png'
                                        : 'images/material/ハートロゴ_デフォルト.png'
                                    ) }}"
                                    alt="favorite"
                                    class="icon-img"
                                >
                            </button>
                        </form>
                    @else
                        <a href="/login" class="icon-btn">
                            <img
                                src="{{ asset('images/material/ハートロゴ_デフォルト.png') }}"
                                alt="favorite"
                                class="icon-img"
                            >
                        </a>
                    @endauth

                    <div class="icon-count">{{ $item->favorites_count }}</div>
                </div>

                {{-- コメント --}}
                <div class="detail-icon">
                    <div class="icon-btn">
                        <img
                            src="{{ asset('images/material/ふきだしロゴ.png') }}"
                            alt="comments"
                            class="icon-img"
                        >
                    </div>
                    <div class="icon-count">{{ $item->comments->count() }}</div>
                </div>

            </div>

            {{-- SOLD表示 --}}
            @if ($item->orders_count > 0)
                <div class="detail-sold">SOLD</div>
            @endif

            {{-- 購入ボタン --}}
            <div class="detail-buy">
                @if ($item->orders_count > 0)
                    {{-- SOLD時は何も出さない（上で表示済み） --}}
                @elseif (Auth::check() && $item->user_id === Auth::id())
                    <div class="detail-own">あなたの商品です</div>
                @else
                    @auth
                        <a href="{{ url('/purchase/' . $item->id) }}" class="btn-primary">購入手続きへ</a>
                    @else
                        <a href="{{ url('/login') }}" class="btn-primary">ログインして購入</a>
                    @endauth
                @endif
            </div>

            {{-- 商品説明 --}}
            <section class="detail-section">
                <h2 class="detail-section__title">商品説明</h2>
                <div class="detail-desc">
                    <p class="detail-desc__meta">カラー：{{ $item->color ?? '—' }}</p>
                    <p class="detail-desc__meta">{{ $item->condition ?? '' }}</p>
                    <p class="detail-desc__text">{{ $item->description }}</p>
                </div>
            </section>

            {{-- 商品の情報 --}}
            <section class="detail-section">
                <h2 class="detail-section__title">商品の情報</h2>

                <div class="detail-table">
                    <div class="detail-row">
                        <div class="detail-key">カテゴリー</div>
                        <div class="detail-val">
                            {{-- ここはあなたの実装に合わせて --}}
                            @if(isset($item->categories))
                                @foreach($item->categories as $cat)
                                    <span class="pill">{{ $cat->name }}</span>
                                @endforeach
                            @else
                                <span class="pill">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-key">商品の状態</div>
                        <div class="detail-val">
                            <span class="pill">{{ $item->condition_name ?? ($item->condition ?? '—') }}</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- コメント --}}
            <section class="detail-section">
                <h2 class="detail-section__title">コメント({{ $item->comments->count() }})</h2>

                <div class="comments">
                    @forelse ($item->comments as $comment)
                        <div class="comment">
                            <div class="comment-user">
                                <div class="comment-avatar"></div>
                                <div class="comment-name">{{ $comment->user->name }}</div>
                            </div>
                            <div class="comment-body">{{ $comment->comment }}</div>
                        </div>
                    @empty
                        <p class="empty">コメントはまだありません。</p>
                    @endforelse
                </div>

                <div class="comment-form">
                    @auth
                        <form action="{{ route('items.comment', ['item_id' => $item->id]) }}" method="POST">
                            @csrf
                            <div class="comment-form__label">商品へのコメント</div>
                            <textarea class="comment-form__textarea" name="comment" placeholder="コメントを書く"></textarea>
                            <button class="btn-primary btn-primary--wide" type="submit">コメントを送信する</button>
                        </form>
                    @else
                        <p><a href="/login">ログインしてコメントする</a></p>
                    @endauth
                </div>
            </section>

        </div>
    </div>

</div>

@endsection