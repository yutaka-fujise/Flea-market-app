@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')

<div class="sell page">

  <h1 class="sell-title">商品の出品</h1>

  @if ($errors->any())
    <div class="sell-errors">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
    @csrf

    {{-- 画像 --}}
    <div class="sell-section">
      <label class="sell-label">商品画像</label>
      <div class="sell-image-box">
        <input type="file" name="image" class="sell-file">
      </div>
    </div>

    <hr class="sell-hr">

    {{-- カテゴリー（複数選択） --}}
    <div class="sell-section">
      <label class="sell-label">カテゴリー</label>

      <div class="sell-categories">
        @foreach($categories as $category)
          <label class="sell-tag">
            <input
              type="checkbox"
              name="categories[]"
              value="{{ $category->id }}"
              {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
            >
            <span>{{ $category->name }}</span>
          </label>
        @endforeach
      </div>
    </div>

    {{-- 商品状態（プルダウン） --}}
    <div class="sell-section">
      <label class="sell-label">商品の状態</label>
      <select name="condition_id" class="sell-select">
        @foreach($conditions as $condition)
          <option value="{{ $condition->id }}"
            {{ (string)old('condition_id') === (string)$condition->id ? 'selected' : '' }}>
            {{ $condition->name }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- 詳細 --}}
    <div class="sell-section">
      <label class="sell-label">商品名</label>
      <input type="text" name="name" value="{{ old('name') }}" class="sell-input">
    </div>

    <div class="sell-section">
      <label class="sell-label">ブランド名</label>
      <input type="text" name="brand" value="{{ old('brand') }}" class="sell-input">
    </div>

    <div class="sell-section">
      <label class="sell-label">商品の説明</label>
      <textarea name="description" class="sell-textarea">{{ old('description') }}</textarea>
    </div>

    <div class="sell-section">
      <label class="sell-label">販売価格</label>
      <div class="sell-price">
        <span>¥</span>
        <input type="number" name="price" value="{{ old('price') }}" class="sell-input">
      </div>
    </div>

    <button type="submit" class="sell-submit">出品する</button>

  </form>
</div>

@endsection