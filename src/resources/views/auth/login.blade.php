@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="auth page">

  <h1 class="auth-title">ログイン</h1>

  @if ($errors->any())
    <ul class="auth-errors">
      @foreach ($errors->all() as $error)
        <li class="auth-error">{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action="/login" class="auth-form">
    @csrf

    <div class="auth-field">
      <label class="auth-label">メールアドレス</label>
      <input class="auth-input" type="email" name="email" value="{{ old('email') }}">
    </div>

    <div class="auth-field">
      <label class="auth-label">パスワード</label>
      <input class="auth-input" type="password" name="password">
    </div>

    <button type="submit" class="auth-submit">ログインする</button>

    <div class="auth-link">
      <a href="{{ url('/register') }}">会員登録はこちら</a>
    </div>
  </form>

</div>
@endsection