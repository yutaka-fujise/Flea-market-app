@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage page">

  {{-- 上：ユーザー情報 --}}
  <div class="mypage-head">
    <div class="mypage-user">
      <div class="mypage-avatar">
        {{-- 画像は後で差し込み。今はグレー丸 --}}
      </div>

      <div class="mypage-name">{{ $user->name }}</div>
    </div>

    <a class="mypage-edit" href="{{ route('mypage.profile.edit') }}">プロフィールを編集</a>
  </div>

  {{-- タブ --}}
  <div class="mypage-tabs">
    <a
      class="mypage-tab {{ $page === 'sell' || $page === null ? 'is-active' : '' }}"
      href="{{ route('mypage', ['page' => 'sell']) }}"
    >
      出品した商品
    </a>

    <a
      class="mypage-tab {{ $page === 'buy' ? 'is-active' : '' }}"
      href="{{ route('mypage', ['page' => 'buy']) }}"
    >
      購入した商品
    </a>
  </div>

  <hr class="mypage-hr">

  {{-- 一覧（画像の4列グリッド） --}}
  @if($page === 'buy')
    <div class="mypage-grid">
      @forelse($orders as $order)
        @php $item = $order->item; @endphp

        @if($item)
          <a class="mypage-card" href="{{ route('items.show', ['item_id' => $item->id]) }}">
            <div class="mypage-thumb">
              <img
                class="mypage-thumb__img"
                src="{{ str_starts_with($item->image, 'items/') ? asset('storage/'.$item->image) : asset($item->image) }}"
                alt="{{ $item->name }}"
              >
            </div>
            <div class="mypage-card__name">{{ $item->name }}</div>
          </a>
        @else
          <div class="mypage-emptycard">商品が見つかりません</div>
        @endif
      @empty
        <p class="mypage-empty">購入履歴はありません</p>
      @endforelse
    </div>

  @else
    {{-- sell をデフォルトに（画像は出品した商品が赤） --}}
    <div class="mypage-grid">
      @forelse($items as $item)
        <a class="mypage-card" href="{{ route('items.show', ['item_id' => $item->id]) }}">
          <div class="mypage-thumb">
            <img
              class="mypage-thumb__img"
              src="{{ str_starts_with($item->image, 'items/') ? asset('storage/'.$item->image) : asset($item->image) }}"
              alt="{{ $item->name }}"
            >
          </div>
          <div class="mypage-card__name">{{ $item->name }}</div>
        </a>
      @empty
        <p class="mypage-empty">出品履歴はありません</p>
      @endforelse
    </div>
  @endif

</div>
@endsection