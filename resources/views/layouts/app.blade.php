<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PjBL E-Learning')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">PjBL E-Learning</a>
        
        @auth
    <div class="navbar-nav flex-row gap-3 ms-3">
        @if (auth()->user()->isSiswa())
            <a class="nav-link text-white" href="{{ route('siswa.dashboard') }}">Dashboard</a>
            <a class="nav-link text-white" href="{{ route('siswa.courses.index') }}">Daftar Kelas</a>
        @else
            <a class="nav-link text-white" href="{{ route('guru.dashboard') }}">Dashboard</a>
            <a class="nav-link text-white" href="{{ route('guru.courses.index') }}">Kelas Saya</a>
        @endif
    </div>
        @endauth
        <div class="ms-auto d-flex align-items-center gap-3">
            @auth
                <span class="text-white">
                    {{ auth()->user()->name }}
                    <span class="badge bg-light text-primary text-uppercase">{{ auth()->user()->role }}</span>
                </span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Login</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-light">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<main class="container py-4">
    {{-- Flash message (disimpan di session, tampil sekali) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>