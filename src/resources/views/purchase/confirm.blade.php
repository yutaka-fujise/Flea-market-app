<h1>購入確認</h1>

<div>商品名：{{ $item->name }}</div>
<div>価格：{{ $item->price }}</div>

<form action="/purchase/{{ $item->id }}" method="POST">
  @csrf

<div>
  <h3>支払い方法</h3>
  <p>{{ $paymentMethod === 'card' ? 'カード払い' : 'コンビニ払い' }}</p>

  <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">

  <a href="{{ route('purchase.payment.edit', $item->id) }}">変更する</a>
</div>

<h3>配送先</h3>
<p>{{ $address }}</p>

<a href="{{ route('purchase.address.edit', $item->id) }}">変更する</a>

@if(!$profile)
  <p style="color:red;">※配送先を登録してから購入してください</p>
  <button type="submit" disabled>購入する</button>
@else
  <button type="submit">購入する</button>
@endif
