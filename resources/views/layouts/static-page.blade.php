<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $pageTitle ?? 'Halaman') - E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <style>
        .static-page-container { max-width: 800px; margin: 0 auto; padding: 24px 20px 48px; }
        .static-page-container h1 { font-size: 1.75rem; margin-bottom: 1rem; color: var(--text-dark, #2c3e3f); }
        .static-page-container h2 { font-size: 1.25rem; margin: 1.5rem 0 0.5rem; color: var(--primary-color, #4481ae); }
        .static-page-container p, .static-page-container li { margin-bottom: 0.75rem; color: var(--text-light, #6b7a7a); line-height: 1.7; }
        .static-page-container ul { padding-left: 1.5rem; margin-bottom: 1rem; }
        .static-page-container a { color: var(--primary-color); text-decoration: none; }
        .static-page-container a:hover { text-decoration: underline; }
        .static-page-container .updated { font-size: 0.875rem; color: var(--text-light); margin-bottom: 1.5rem; }
        .header-title { flex: 1; text-align: center; font-weight: 600; font-size: 1rem; }
        .btn-kembali { display: inline-flex; align-items: center; gap: 8px; color: var(--primary-color); text-decoration: none; font-weight: 500; }
        .btn-kembali:hover { text-decoration: underline; }
    </style>
</head>
<body class="body-background-3d">
    <div class="header-bar">
        <a href="{{ url('/dashboard') }}" class="btn-kembali">&larr; Dashboard</a>
        <div class="header-title">@yield('title', $pageTitle ?? '')</div>
        <div style="width:100px;"></div>
    </div>
    <main class="static-page-container">
        @yield('content')
    </main>

</body>
</html>
