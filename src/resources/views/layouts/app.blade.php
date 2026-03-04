<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Coachtech</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('css')
</head>

<body>

    <header class="site-header">
        <div class="header-inner">

            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/material/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH">
                </a>
            </div>

            @php
                $hideHeaderParts = request()->routeIs(
                    'login',
                    'register',
                    'password.request',
                    'password.reset',
                    'verification.notice'
                );
            @endphp

            @unless($hideHeaderParts)

                <div class="search">
                    <form action="{{ url('/') }}" method="GET">
                        <input type="text" name="q" placeholder="なにをお探しですか？">
                    </form>
                </div>

                <div class="nav">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="logout-btn">ログアウト</button>
                        </form>

                        <a href="{{ route('mypage') }}">マイページ</a>
                        <a href="{{ route('sell.create') }}">出品</a>
                    @else
                        <a href="{{ route('login') }}">ログイン</a>
                        <a href="{{ route('mypage') }}">マイページ</a>
                        <a href="{{ route('sell.create') }}">出品</a>
                    @endauth
                </div>

            @endunless

        </div>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>