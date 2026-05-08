<!doctype html>
<html lang="id" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0E4DA4">
    <meta name="description" content="@yield('meta_description', 'Portal resmi Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kota Surabaya — pelayanan publik modern, transparan, dan akuntabel.')">

    <title>@yield('title', $pageTitle ?? 'DPMPTSP Surabaya') · DPMPTSP Kota Surabaya</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('brand/favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
    <a href="#main-content" class="skip-link">Lompat ke konten utama</a>

    <x-navbar />

    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
