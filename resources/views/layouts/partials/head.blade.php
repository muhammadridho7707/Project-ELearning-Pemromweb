<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'PjBL E-Learning')</title>

{{-- Terapkan tema tersimpan SEBELUM halaman tampil, supaya tidak berkedip --}}
<script>
    (function () {
        var saved = localStorage.getItem('theme');
        var theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-bs-theme', theme);
    })();
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
    /* ===== Palet warna terang ===== */
    :root, [data-bs-theme="light"] {
        --bs-body-bg: #f1f4f9;
        --bs-body-bg-rgb: 241, 244, 249;
        --bs-border-color: #dfe5ee;
        --app-surface: #ffffff;
        --app-surface-2: #f6f8fc;
    }

    /* ===== Palet warna gelap ===== */
    [data-bs-theme="dark"] {
        --bs-body-bg: #0d1220;
        --bs-body-bg-rgb: 13, 18, 32;
        --bs-border-color: #263149;
        --app-surface: #151c2e;
        --app-surface-2: #1b243a;
    }

    /* ===== Komponen mengikuti palet ===== */
    .card {
        --bs-card-bg: var(--app-surface);
        --bs-card-cap-bg: var(--app-surface-2);
        --bs-card-border-color: var(--bs-border-color);
    }
    .app-navbar {
        background-color: var(--app-surface);
        border-bottom: 1px solid var(--bs-border-color);
    }
    .dropdown-menu {
        --bs-dropdown-bg: var(--app-surface);
        --bs-dropdown-border-color: var(--bs-border-color);
    }
    .list-group {
        --bs-list-group-bg: transparent;
        --bs-list-group-border-color: var(--bs-border-color);
    }
    .table { --bs-table-bg: transparent; }
    .table-light {
        --bs-table-color: var(--bs-body-color);
        --bs-table-bg: var(--app-surface-2);
        --bs-table-border-color: var(--bs-border-color);
    }
    .form-control, .form-select { background-color: var(--app-surface); }

    /* Jaring pengaman: sisa class bg-white di view lama tidak menyilaukan di mode gelap */
    [data-bs-theme="dark"] .bg-white { background-color: var(--app-surface-2) !important; }

    /* ===== Ikon tombol tema ===== */
    .theme-icon-dark { display: none; }
    [data-bs-theme="dark"] .theme-icon-light { display: none; }
    [data-bs-theme="dark"] .theme-icon-dark { display: inline-block; }
</style>