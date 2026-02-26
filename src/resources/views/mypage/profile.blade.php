@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endsection

@section('content')
<div class="profile page">

  <h1 class="profile-title">プロフィール設定</h1>

  <form action="{{ route('mypage.profile.update') }}"
        method="POST"
        class="profile-form"
        enctype="multipart/form-data">
    @csrf

    {{-- ✅ ここだけ1回！ --}}
    <div class="profile-photo">
      <div class="profile-avatar"></div>

      <label class="profile-photo__btn">
        画像を選択する
        <input type="file" name="avatar" class="profile-file">
      </label>
    </div>

    <div class="profile-field">
      <label class="profile-label">ユーザー名</label>
      <input class="profile-input" type="text" name="name" value="{{ old('name', $user->name) }}">
      @error('name')<p class="profile-error">{{ $message }}</p>@enderror
    </div>

    <div class="profile-field">
      <label class="profile-label">郵便番号</label>
      <input class="profile-input" type="text" name="postal_code" value="{{ old('postal_code', optional($profile)->postal_code) }}">
      @error('postal_code')<p class="profile-error">{{ $message }}</p>@enderror
    </div>

    <div class="profile-field">
      <label class="profile-label">住所</label>
      <input class="profile-input" type="text" name="address" value="{{ old('address', optional($profile)->address) }}">
      @error('address')<p class="profile-error">{{ $message }}</p>@enderror
    </div>

    <div class="profile-field">
      <label class="profile-label">建物名</label>
      <input class="profile-input" type="text" name="building" value="{{ old('building', optional($profile)->building) }}">
      @error('building')<p class="profile-error">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="profile-submit">更新する</button>

    <div class="profile-back">
      <a href="{{ route('mypage') }}">戻る</a>
    </div>

  </form>

</div>
@endsection