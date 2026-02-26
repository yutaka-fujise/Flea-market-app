@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address page">

  <h1 class="address-title">住所の変更</h1>

  <form action="{{ route('purchase.address.update', $item->id) }}" method="POST" class="address-form">
    @csrf

    <div class="address-field">
      <label class="address-label">郵便番号</label>
      <input
        class="address-input"
        type="text"
        name="postal_code"
        value="{{ old('postal_code', optional($profile)->postal_code) }}"
      >
    </div>

    <div class="address-field">
      <label class="address-label">住所</label>
      <input
        class="address-input"
        type="text"
        name="address"
        value="{{ old('address', optional($profile)->address) }}"
      >
    </div>

    <div class="address-field">
      <label class="address-label">建物名</label>
      <input
        class="address-input"
        type="text"
        name="building"
        value="{{ old('building', optional($profile)->building) }}"
      >
    </div>

    <button type="submit" class="address-submit">更新する</button>
  </form>

  <div class="address-back">
    <a href="{{ route('purchase.confirm', $item->id) }}">戻る</a>
  </div>

</div>
@endsection