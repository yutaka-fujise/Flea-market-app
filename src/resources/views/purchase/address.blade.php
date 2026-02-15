<h1>配送先変更</h1>

<form action="{{ route('purchase.address.update', $item->id) }}" method="POST">
  @csrf

  <div>
    <label>郵便番号</label>
    <input type="text" name="postal_code" value="{{ old('postal_code', optional($profile)->postal_code) }}">
  </div>

  <div>
    <label>住所</label>
    <input type="text" name="address" value="{{ old('address', optional($profile)->address) }}">
  </div>

  <div>
    <label>建物名</label>
    <input type="text" name="building" value="{{ old('building', optional($profile)->building) }}">
  </div>

  <button type="submit">更新する</button>
</form>

<a href="{{ route('purchase.confirm', $item->id) }}">戻る</a>
