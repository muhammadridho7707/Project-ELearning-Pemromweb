<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    @include('layouts.partials.head')
</head>
<body>

<nav class="navbar navbar-expand-lg app-navbar sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand fw-semibold" href="{{ url('/') }}">
            <i class="bi bi-mortarboard-fill text-primary me-1"></i> PjBL E-Learning
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if (auth()->user()->isSiswa())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active fw-semibold' : '' }}"
                           href="{{ route('siswa.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.courses.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('siswa.courses.index') }}">Daftar Kelas</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active fw-semibold' : '' }}"
                           href="{{ route('guru.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guru.courses.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('guru.courses.index') }}">Kelas Saya</a>
                    </li>
                @endif
            </ul>

            <div class="d-flex align-items-center gap-3">
                @include('layouts.partials.theme-toggle')

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-body dropdown-toggle"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        @include('partials.avatar', ['user' => auth()->user(), 'size' => 36])
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li class="px-3 py-2">
                            <div class="fw-semibold">{{ auth()->user()->name }}</div>
                            <div class="small text-muted">{{ auth()->user()->email }}</div>
                            <span class="badge text-bg-primary mt-1 text-uppercase">{{ auth()->user()->role }}</span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle me-2"></i>Profil Saya
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4">
    @include('layouts.partials.flash')
    @yield('content')
</main>

@include('layouts.partials.scripts')
</body>
</html>