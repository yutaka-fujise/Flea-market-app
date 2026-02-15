<h1>支払い方法の変更</h1>

<form action="{{ route('purchase.payment.update', $item->id) }}" method="POST">
  @csrf

  <label>
    <input type="radio" name="payment_method" value="card" {{ $paymentMethod==='card' ? 'checked' : '' }}>
    カード払い
  </label>

  <label>
    <input type="radio" name="payment_method" value="convenience" {{ $paymentMethod==='convenience' ? 'checked' : '' }}>
    コンビニ払い
  </label>

  <button type="submit">変更する</button>
</form>

<a href="{{ route('purchase.confirm', $item->id) }}">戻る</a>
