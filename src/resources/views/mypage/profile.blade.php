<h1>プロフィール編集</h1>

<form action="{{ route('mypage.profile.update') }}" method="POST">
  @csrf

  <div>
    <label>ユーザー名</label>
    <input type="text" name="name" value="{{ old('name', $user->name) }}">
    @error('name')<p>{{ $message }}</p>@enderror
  </div>

  <div>
    <label>郵便番号</label>
    <input type="text" name="postal_code" value="{{ old('postal_code', optional($profile)->postal_code) }}">
    @error('postal_code')<p>{{ $message }}</p>@enderror
  </div>

  <div>
    <label>住所</label>
    <input type="text" name="address" value="{{ old('address', optional($profile)->address) }}">
    @error('address')<p>{{ $message }}</p>@enderror
  </div>

  <div>
    <label>建物名</label>
    <input type="text" name="building" value="{{ old('building', optional($profile)->building) }}">
    @error('building')<p>{{ $message }}</p>@enderror
  </div>

  <button type="submit">更新する</button>
</form>

<a href="{{ route('mypage') }}">戻る</a>