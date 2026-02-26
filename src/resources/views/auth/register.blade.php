@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="auth page">

  <h1 class="auth-title">会員登録</h1>

  @if ($errors->any())
    <ul class="auth-errors">
      @foreach ($errors->all() as $error)
        <li class="auth-error">{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action="/register" class="auth-form">
    @csrf

    <div class="auth-field">
      <label class="auth-label">ユーザー名</label>
      <input class="auth-input" type="text" name="name" value="{{ old('name') }}">
    </div>

    <div class="auth-field">
      <label class="auth-label">メールアドレス</label>
      <input class="auth-input" type="email" name="email" value="{{ old('email') }}">
    </div>

    <div class="auth-field">
      <label class="auth-label">パスワード</label>
      <input class="auth-input" type="password" name="password">
    </div>

    <div class="auth-field">
      <label class="auth-label">確認用パスワード</label>
      <input class="auth-input" type="password" name="password_confirmation">
    </div>

    <button type="submit" class="auth-submit">登録する</button>

    <div class="auth-link">
      <a href="{{ route('login') }}">ログインはこちら</a>
    </div>

  </form>

</div>
@endsection