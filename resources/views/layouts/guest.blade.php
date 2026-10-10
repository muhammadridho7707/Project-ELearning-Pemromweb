<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    @include('layouts.partials.head')
</head>
<body>

<div class="position-fixed top-0 end-0 p-3">
    @include('layouts.partials.theme-toggle')
</div>

<main class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="w-100 px-3" style="max-width: @yield('width', '440px');">
        <div class="text-center mb-4">
            <i class="bi bi-mortarboard-fill text-primary fs-1"></i>
            <h5 class="fw-semibold mb-0">PjBL E-Learning</h5>
        </div>

        @include('layouts.partials.flash')
        @yield('content')
    </div>
</main>

@include('layouts.partials.scripts')
</body>
</html>