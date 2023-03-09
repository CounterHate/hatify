<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/" target="_blank">
            <img src="{{ asset('images/logo hor.png') }}" width="60%">
            {{-- <span class="ms-1 font-weight-bold">Counter Hate</span> --}}

        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                @if (Route::currentRouteName() == 'search')
                    <a class="nav-link active" href="/search/twitter">
                    @else
                        <a class="nav-link" href="/search/twitter">
                @endif
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Szukaj</span>
                </a>
            </li>
            @can('anotate')
                <li class="nav-item">
                    @if (Route::currentRouteName() == 'anotate')
                        <a class="nav-link active" href="/anotate">
                        @else
                            <a class="nav-link " href="/anotate">
                    @endif
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Anotuj</span>
                    </a>
                </li>
            @endcan
            {{-- @can('admin') --}}
                <li class="nav-item">
                    @if (Route::currentRouteName() == 'admin')
                        <a class="nav-link active" href="/admin">
                        @else
                            <a class="nav-link " href="/admin">
                    @endif
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Admin</span>
                    </a>
                </li>
                <li class="nav-item">
                    @if (Route::currentRouteName() == 'permissions')
                        <a class="nav-link active" href="/permissions">
                        @else
                            <a class="nav-link " href="/permissions">
                    @endif
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Uprawnienia</span>
                    </a>
                </li>
            {{-- @endcan --}}
            @auth
                <li class="nav-item">
                    <div class="nav-link">

                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="btn btn-link" style="padding: 0px; margin: 0px">
                                {{ __('Wyloguj') }}
                            </button>
                        </form>
                    </div>
                </li>
            @endauth
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">

                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Logowanie</span>
                    </a>
                </li>

                <li class="nav-item">

                    <a class="nav-link" href="{{ route('register') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-collection text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Rejestracja</span>
                    </a>
                </li>
            @endguest
        </ul>
    </div>
</aside>