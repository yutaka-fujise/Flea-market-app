@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="page">

    <div class="tabs">
        <a
            class="tab {{ request('tab') !== 'mylist' ? 'is-active' : '' }}"
            href="{{ url('/') . '?' . http_build_query(array_merge(request()->query(), ['tab' => null])) }}"
        >
            おすすめ
        </a>

        <a
            class="tab {{ request('tab') === 'mylist' ? 'is-active' : '' }}"
            href="{{ url('/') . '?' . http_build_query(array_merge(request()->query(), ['tab' => 'mylist'])) }}"
        >
            マイリスト
        </a>
    </div>

    <main class="container">
        <div class="items-grid">

            @forelse($items as $item)
                <a class="item-card" href="{{ route('items.show', ['item_id' => $item->id]) }}">
                    <div class="item-card__thumb">

                        {{-- 画像パスはプロジェクトに合わせて調整 --}}
                        <img
                            class="item-card__img"
                            src="{{ str_starts_with($item->image, 'items/') ? asset('storage/' . $item->image) : asset($item->image) }}"
                            alt="{{ $item->name }}"
                            loading="lazy"
                        >

                        @if($item->orders_count > 0)
                            <span class="badge-sold">SOLD</span>
                        @endif

                    </div>

                    <div class="item-card__name">
                        {{ $item->name }}
                    </div>
                </a>
            @empty
                <p class="empty">商品がありません</p>
            @endforelse

        </div>
    </main>

</div>

@endsection