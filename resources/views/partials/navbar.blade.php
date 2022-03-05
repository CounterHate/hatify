<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/index">Index</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/search">Szukaj</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/similar/ ">Szukaj podobnej treści</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/userTweets/ ">Szukaj innych autora</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/">Anotacja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/verify">Weryfikacja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/stats">Statystyki</a>
                </li>
            </ul>
            {{-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            </ul> --}}
            <span class="navbar-text">
                @if (Route::has('login'))
                    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                        @auth
                            {{-- <a href="{{ url('/dashboard') }}"
                                class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a> --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="btn btn-primary">
                                    {{ __('Log Out') }}
                                </button>
                            </form>

                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log
                                in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </span>
        </div>
    </div>
</nav>
