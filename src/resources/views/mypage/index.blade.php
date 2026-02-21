<h1>マイページ</h1>

<div>
  <p>ユーザー名：{{ $user->name }}</p>

  {{-- 画像は後で。profile実装したら差し込む --}}
</div>

<nav>
  <a href="{{ route('mypage') }}">トップ</a> |
  <a href="{{ route('mypage', ['page' => 'buy']) }}">購入履歴</a> |
  <a href="{{ route('mypage', ['page' => 'sell']) }}">出品履歴</a><a href="{{ route('mypage.profile.edit') }}">プロフィール編集</a>
</nav>

<hr>

@if($page === 'buy')
  <h2>購入履歴</h2>

  @forelse($orders as $order)
    <div>
      <p>商品：{{ $order->item->name ?? '商品が見つかりません' }}</p>
      <p>支払い：{{ $order->payment_method }}</p>
      <p>購入日：{{ $order->created_at }}</p>
    </div>
    <hr>
  @empty
    <p>購入履歴はありません</p>
  @endforelse

@elseif($page === 'sell')
  <h2>出品履歴</h2>

  @forelse($items as $item)
    <div>
      <p>商品：{{ $item->name }}</p>
      <p>価格：{{ $item->price }}</p>
    </div>
    <hr>
  @empty
    <p>出品履歴はありません</p>
  @endforelse

@else
  <h2>メニュー</h2>
  <p>上のリンクから「購入履歴 / 出品履歴」を選べます。</p>
@endif