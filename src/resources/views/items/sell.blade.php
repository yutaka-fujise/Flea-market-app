@if ($errors->any())
  <div style="border:1px solid red; padding:10px; margin-bottom:10px;">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div>
    <label>商品名</label>
    <input type="text" name="name" value="{{ old('name') }}">
  </div>

  <div>
    <label>ブランド</label>
    <input type="text" name="brand" value="{{ old('brand') }}">
  </div>

  <div>
    <label>説明</label>
    <textarea name="description">{{ old('description') }}</textarea>
  </div>

  <div>
    <label>価格</label>
    <input type="number" name="price" value="{{ old('price') }}">
  </div>

  <div>
    <label>カテゴリー</label>
    <select name="category_id">
      @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ (string)old('category_id') === (string)$category->id ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label>商品の状態</label>
    <select name="condition_id">
      @foreach($conditions as $condition)
        <option value="{{ $condition->id }}" {{ (string)old('condition_id') === (string)$condition->id ? 'selected' : '' }}>
          {{ $condition->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label>画像</label>
    <input type="file" name="image">
  </div>

  <button type="submit">出品する</button>
</form>