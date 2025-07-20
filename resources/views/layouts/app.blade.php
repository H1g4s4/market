<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'COACHTECHフリマ')</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @stack('styles') <!-- 各ページでスタイルを挿入する場所 -->
        <style>
            .badge {
                display: inline-block;
                background-color: red;
                color: white;
                padding: 3px 6px;
                border-radius: 12px;
                font-size: 12px;
                margin-left: 4px;
            }
        </style>
    </head>
    <body>
        <header class="header-container">
            <div class="logo">
                <a href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
                </a>
            </div>

            @if (!(Request::routeIs('register.show') || Request::routeIs('login.show')))
                <div class="search-box">
                    <form method="GET" action="{{ route('items.index') }}">
                        <input type="text" name="search" placeholder="なにをお探しですか？" value="{{ request('search') }}">
                    </form>
                </div>
            @endif

            <div class="header-buttons">
                @auth
                    <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>

                    <a href="{{ route('profile.show') }}">
                        マイページ
                        @if(isset($globalUnreadCount) && $globalUnreadCount > 0)
                            <span class="badge">{{ $globalUnreadCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('items.create') }}" class="sell-button">出品</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login.show') }}">ログイン</a>
                    <a href="{{ route('register.show') }}">会員登録</a>
                @endauth
            </div>
        </header>

        <main class="content-container">
            @yield('content')
        </main>

        @yield('scripts')
    </body>
</html>
