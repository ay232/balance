<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Балансы</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('operations') ? 'active' : '' }}" href="{{ route('operations') }}">Операции</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users') ? 'active' : '' }} disabled" href="#">Пользователи</a>
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Войти</a>
                    </li>
                @endguest
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Выйти</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
