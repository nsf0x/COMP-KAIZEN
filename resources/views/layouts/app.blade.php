<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.google-analytics')
    @php
        $seoTitle = html_entity_decode(trim($__env->yieldContent('title', config('company.name'))), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $seoDescription = \App\Support\PublicText::normalize(html_entity_decode(trim($__env->yieldContent('meta_description', config('company.description'))), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $companyDescription = \App\Support\PublicText::normalize(config('company.description'));
        $companyName = config('company.name');
        $seoMetaTitle = html_entity_decode(trim($__env->yieldContent('meta_title')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $seoDocumentTitle = $seoMetaTitle !== ''
            ? $seoMetaTitle
            : (str_ends_with($seoTitle, $companyName) ? $seoTitle : $seoTitle . ' | ' . $companyName);
        $seoCanonicalUrl = rtrim(config('app.url'), '/') . '/' . ltrim(request()->getPathInfo(), '/');
        $seoImage = trim($__env->yieldContent('meta_image', rtrim(config('app.url'), '/') . '/images/kaizen-logo-color.png'));
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msvalidate.01" content="E2E930C2EB21A8991D6D676EA062D622" />
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="@yield('meta_robots', request()->query() ? 'noindex,follow' : 'index,follow')">
    <link rel="canonical" href="{{ $seoCanonicalUrl }}">
    <title>{{ $seoDocumentTitle }}</title>
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $companyName }}">
    <meta property="og:title" content="{{ $seoDocumentTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonicalUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoDocumentTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <script type="application/ld+json">
        {!! json_encode([
            '@@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => rtrim(config('app.url'), '/') . '/#organization',
            'name' => config('company.name'),
            'description' => $companyDescription,
            'url' => rtrim(config('app.url'), '/') . '/',
            'logo' => rtrim(config('app.url'), '/') . '/images/kaizen-logo-color.png',
            'image' => rtrim(config('app.url'), '/') . '/images/kaizen-logo-color.png',
            'telephone' => config('company.phone'),
            'email' => config('company.email'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => config('company.street_address'),
                'addressLocality' => config('company.city'),
                'addressRegion' => config('company.region'),
                'addressCountry' => config('company.country'),
            ],
            'sameAs' => array_values(array_filter([
                config('company.instagram'),
                config('company.facebook'),
                config('company.youtube'),
                config('company.tiktok'),
            ])),
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                    'opens' => '08:00',
                    'closes' => '17:00',
                ],
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => 'Saturday',
                    'opens' => '08:00',
                    'closes' => '14:00',
                ],
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => config('company.phone'),
                'contactType' => 'customer service',
                'availableLanguage' => 'Indonesian',
                'description' => \App\Support\PublicText::normalize(config('company.contact_description')),
                'hoursAvailable' => [
                    [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                        'opens' => '00:00',
                        'closes' => '23:59',
                    ],
                ],
            ],
            'areaServed' => 'Jabodetabek',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
    </script>
    @stack('structured_data')
    <script>
        try {
            const savedTheme = localStorage.getItem('theme-mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', savedTheme ? savedTheme === 'dark' : prefersDark);
        } catch (error) {
            document.documentElement.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome Free (category icons) -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --brand-blue: #1736e8;
            --brand-red: #ff0000;
            --brand-gradient: linear-gradient(105deg, #1736e8 8%, #8d0d86 48%, #ff0000 82%, #ff0000 100%);
            --brand-wash: radial-gradient(circle at 86% 48%, rgba(255, 0, 0, 0.096) 0%, rgba(255, 0, 0, 0.048) 18%, rgba(255, 0, 0, 0) 46%), linear-gradient(120deg, #f0f4ff 0%, #fff 58%, #fff2f2 100%);
            --primary: #1736e8;
            --primary-dark: #1026a8;
            --secondary: #ff0000;
            --dark: #17192b;
            --light-bg: #f3f5ff;
        }
        html.dark {
            color-scheme: dark;
            --brand-wash: radial-gradient(circle at 86% 48%, rgba(255, 0, 0, 0.112) 0%, rgba(255, 0, 0, 0.048) 20%, rgba(255, 0, 0, 0) 46%), linear-gradient(120deg, #11172f 0%, #171821 58%, #28171c 100%);
            --dark: #f1f2f7;
            --light-bg: #171821;
        }
        * { font-family: 'Poppins', sans-serif; }
        body { background: #fff; color: #333; }
        html.dark body { background: #101116; color: #e5e6ec; }

        /* Navbar */
        .navbar { background: rgba(255,255,255,0.97); box-shadow: 0 2px 20px rgba(14,28,95,0.1); }
        html.dark .navbar { background: rgba(20,21,28,0.96); box-shadow: 0 2px 24px rgba(0,0,0,0.28); }
        .navbar-brand { display:flex; align-items:center; gap:.55rem; font-weight:700; font-size:1.15rem; }
        .navbar-brand span { color:transparent; background:var(--brand-gradient); background-clip:text; -webkit-background-clip:text; }
        .brand-logo { width:48px; height:48px; object-fit:contain; flex:none; }
        .brand-logo-footer { width:38px; height:38px; }
        .nav-link { font-weight: 500; color: #444 !important; transition: color .2s; }
        html.dark .nav-link { color: #d4d5dc !important; }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; }
        html.dark .nav-link:hover, html.dark .nav-link.active { color: #899bff !important; }
        html.dark .navbar-toggler { border-color: rgba(255,255,255,.2); }
        html.dark .navbar-toggler-icon { filter: invert(1); }
        .theme-toggle { display:inline-grid; width:40px; height:40px; place-items:center; border:1px solid rgba(23,54,232,.16); border-radius:50%; background:rgba(23,54,232,.06); color:var(--primary); transition:transform .2s, background .2s, border-color .2s; }
        .theme-toggle:hover { transform:translateY(-1px); background:rgba(23,54,232,.12); }
        html.dark .theme-toggle { border-color:rgba(255,255,255,.16); background:rgba(255,255,255,.07); color:#dce1ff; }
        html.dark .theme-toggle:hover { background:rgba(255,255,255,.13); }
        .theme-toggle .bi-moon-stars-fill { display:none; }
        html.dark .theme-toggle .bi-sun-fill { display:none; }
        html.dark .theme-toggle .bi-moon-stars-fill { display:inline; }
        .btn-wa { background: #25d366; color: #fff; border-radius: 50px; padding: 8px 20px; font-weight: 600; }
        .btn-wa:hover { background: #128c7e; color: #fff; }
        .btn-primary-custom { background: var(--brand-gradient); color: #fff; border-radius: 50px; padding: 10px 28px; font-weight: 600; border: none; }
        .btn-primary-custom:hover { background: linear-gradient(105deg, var(--primary-dark), #b50925); color: #fff; }
        .brand-badge { background:#edf1ff; color:var(--primary); }

        .product-card-media { aspect-ratio:4 / 3; width:100%; overflow:hidden; background:#f1f4ff; }
        .product-card-media img { display:block; width:100%; height:100%; object-fit:cover; }
        .product-card-title { display:-webkit-box; min-height:2.7em; overflow:hidden; -webkit-box-orient:vertical; -webkit-line-clamp:2; line-height:1.35; }
        .product-card-description { display:-webkit-box; min-height:3em; overflow:hidden; -webkit-box-orient:vertical; -webkit-line-clamp:2; line-height:1.5; }
        .product-card-cta { display:grid; gap:8px; width:100%; margin-top:auto; }
        .product-card-cta__actions { display:grid; grid-template-columns:minmax(0, 1fr); gap:8px; }
        .product-card-cta__actions--paired { grid-template-columns:repeat(2, minmax(0, 1fr)); }
        .product-card-cta__button {
            box-sizing:border-box;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            width:100%;
            min-width:0;
            height:44px;
            padding:0 16px;
            border:1.5px solid transparent;
            border-radius:12px;
            font-size:14px;
            font-weight:600;
            line-height:1;
            text-align:center;
            text-decoration:none;
            white-space:nowrap;
            transition:background-color .18s ease, border-color .18s ease, color .18s ease, transform .18s ease;
        }
        .product-card-cta__button i { flex:none; font-size:18px; line-height:1; }
        .product-card-cta__button--buy { border-color:#087443; background:#087443; color:#fff; }
        .product-card-cta__button--buy:hover { border-color:#06643a; background:#06643a; color:#fff; transform:translateY(-1px); }
        .product-card-cta__button--rent { border-color:#16884f; background:transparent; color:#087443; }
        .product-card-cta__button--rent:hover { border-color:#16884f; background:rgba(37,211,102,.12); color:#06643a; transform:translateY(-1px); }
        .product-card-cta__detail { border-color:#d5d9e2; background:transparent; color:#535b6b; }
        .product-card-cta__detail:hover { border-color:#aeb5c2; background:#f4f6fa; color:#303847; transform:translateY(-1px); }
        .product-card-cta__button:focus-visible { outline:3px solid #ffbf47; outline-offset:2px; }
        .product-card-cta__button:active { transform:translateY(0); }
        html.dark .product-card-cta__button--rent { border-color:#57d991; color:#57d991; }
        html.dark .product-card-cta__button--rent:hover { background:rgba(87,217,145,.14); color:#8aefb3; }
        html.dark .product-card-cta__detail { border-color:#454754; color:#c0c2cc; }
        html.dark .product-card-cta__detail:hover { border-color:#686b79; background:#252733; color:#fff; }
        @media (prefers-reduced-motion: reduce) {
            .product-card-cta__button { transition:none; }
        }

        /* Footer */
        footer { background: var(--dark); color: #ccc; }
        html.dark footer { background: #17192b; color: #ccc; }
        footer a { color: #ccc; text-decoration: none; }
        footer a:hover { color: var(--secondary); }
        footer .footer-brand { display:flex; align-items:center; gap:.5rem; color:#fff; font-weight:700; font-size:1.15rem; }
        footer .social-icon { display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.1);
            color: #fff; margin-right: 6px; transition: background .2s; text-decoration: none; }
        footer .social-icon:hover { background: var(--primary); color: #fff; }
        footer hr { border-color: rgba(255,255,255,0.1); }

        /* WhatsApp float */
        .wa-float { position: fixed; bottom: 24px; right: 24px; z-index: 999;
            width: 56px; height: 56px; background: #25d366; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 20px rgba(37,211,102,0.5); text-decoration: none;
            font-size: 1.6rem; color: #fff; transition: transform .2s; }
        .wa-float:hover { transform: scale(1.1); color: #fff; }

        /* Section headings */
        .section-title { font-size: 2rem; font-weight: 700; color: var(--dark); }
        .section-subtitle { color: #888; font-size: 1rem; }
        .divider-pink { width: 60px; height: 4px; background: var(--brand-gradient); border-radius: 2px; }
        .hero-logo { display:block; width:min(360px, 80vw); aspect-ratio:1; margin:auto; object-fit:contain; filter:drop-shadow(0 18px 30px rgba(23,54,232,.12)); }
        .home-hero { background:var(--brand-wash); min-height:90vh; display:flex; align-items:center; }
        @media (max-width: 767.98px) {
            [data-aos="fade-left"], [data-aos="fade-right"] {
                transform: translate3d(0, 0, 0) !important;
                transition-property: opacity !important;
            }
        }
        .category-card { overflow:hidden; border-radius:12px; transition:transform .2s, box-shadow .2s; }
        .category-card-icon { height:112px; display:grid; place-items:center; color:var(--primary); background:var(--brand-wash); font-size:2.5rem; }
        .category-card-icon i { color:transparent; background:var(--brand-gradient); background-clip:text; -webkit-background-clip:text; }
        [style*="#ffeef9"] { background-color:#f1f4ff !important; }
        [style*="#fdf6fb"] { background:var(--brand-wash) !important; }
        [style*="#ffe4f5"] { background-color:#e8eeff !important; }
        [style*="#ffd4ee"] { background:linear-gradient(110deg,#edf2ff,#fff0f1) !important; }
        [style*="rgba(233,30,140"] { box-shadow:0 12px 30px rgba(23,54,232,.12) !important; }
        html.dark .home-hero { background:var(--brand-wash); }
        html.dark .home-hero h1,
        html.dark [style*="color:#1a1a2e"],
        html.dark [style*="color: #1a1a2e"],
        html.dark [style*="color:#25283a"],
        html.dark [style*="color: #25283a"] { color:#f1f2f7 !important; }
        html.dark .text-muted,
        html.dark .section-subtitle { color:#aaadba !important; }
        html.dark .card,
        html.dark .modal-content,
        html.dark .dropdown-menu { border-color:#343642; background-color:#1a1b23; color:#e5e6ec; }
        html.dark .card-body,
        html.dark .card-footer { color:#e5e6ec; }
        html.dark .breadcrumb,
        html.dark .breadcrumb-item.active,
        html.dark .breadcrumb-item::before { color:#fff !important; }
        html.dark .category-card-icon,
        html.dark [style*="#ffeef9"],
        html.dark [style*="#fdf6fb"],
        html.dark [style*="#ffe4f5"],
        html.dark [style*="#ffd4ee"] { background:#202230 !important; }
        html.dark .form-control,
        html.dark .form-select { border-color:#454754; background-color:#1a1b23; color:#e5e6ec; }
        html.dark .form-control::placeholder { color:#9a9daa; }
        html.dark .border,
        html.dark .table { border-color:#343642 !important; }
        html.dark .table { --bs-table-color:#e5e6ec; --bs-table-bg:#171821; --bs-table-striped-bg:#1d1f29; --bs-table-hover-bg:#252733; }
        html.dark .btn-outline-success { color:#57d991; border-color:#57d991; }
        html.dark .btn-outline-success:hover { color:#101116; background:#57d991; }
        @media (prefers-reduced-motion: no-preference) {
            body, .navbar, .card, .modal-content { transition:background-color .25s ease, color .25s ease, border-color .25s ease; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <x-optimized-image src="{{ asset('images/kaizen-logo-color.png') }}" alt="Logo Kaizen Kreasi Indonesia"
                               width="48" height="48" loading="eager" class="brand-logo" />
            <span>{{ config('company.name') }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') && !request()->routeIs('products.sales') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.sales') ? 'active' : '' }}" href="{{ route('products.sales') }}">Jual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('portfolio.*') ? 'active' : '' }}" href="{{ route('portfolio.index') }}">Event Portofolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                </li>
                <li class="nav-item ms-lg-2">
                    <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Ganti ke mode gelap" aria-pressed="false" title="Ganti ke mode gelap">
                        <i class="bi bi-sun-fill" aria-hidden="true"></i>
                        <i class="bi bi-moon-stars-fill" aria-hidden="true"></i>
                    </button>
                </li>
                <li class="nav-item ms-lg-2">
                    <a href="https://wa.me/{{ config('company.whatsapp') }}" class="btn btn-wa" target="_blank">
                        <i class="bi bi-whatsapp me-1"></i>WhatsApp
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Content -->
@yield('content')

<!-- Footer -->
<footer class="pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand mb-2">
                    <x-optimized-image src="{{ asset('images/kaizen-logo-color.png') }}" alt="Logo Kaizen Kreasi Indonesia"
                                       width="38" height="38" class="brand-logo brand-logo-footer" />
                    <span>{{ config('company.name') }}</span>
                </div>
                <p class="small">{{ $companyDescription }}</p>
                <div class="mt-3">
                    @if(config('company.instagram'))
                    <a href="{{ config('company.instagram') }}" class="social-icon" target="_blank"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if(config('company.facebook'))
                    <a href="{{ config('company.facebook') }}" class="social-icon" target="_blank"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if(config('company.tiktok'))
                    <a href="{{ config('company.tiktok') }}" class="social-icon" target="_blank"><i class="bi bi-tiktok"></i></a>
                    @endif
                    <a href="https://wa.me/{{ config('company.whatsapp') }}" class="social-icon" target="_blank"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="text-white fw-600 mb-3">Menu</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="mb-2"><a href="{{ route('products.sales') }}">Jual</a></li>
                    <li class="mb-2"><a href="{{ route('portfolio.index') }}">Event Portofolio</a></li>
                    <li class="mb-2"><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="mb-2"><a href="{{ route('about') }}">Tentang Kami</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6">
                <h6 class="text-white fw-600 mb-3">WhatsApp & Jam Kantor</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1"><i class="bi bi-whatsapp me-1"></i> {{ config('company.hours.whatsapp') }}</li>
                    <li class="mb-1"><i class="bi bi-clock me-1"></i> {{ config('company.hours.weekday') }}</li>
                    <li class="mb-1"><i class="bi bi-clock me-1"></i> {{ config('company.hours.weekend') }}</li>
                    <li class="mb-1"><i class="bi bi-info-circle me-1"></i> {{ config('company.hours.holiday') }}</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="text-white fw-600 mb-3">Kontak</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="bi bi-whatsapp me-2"></i>
                        <a href="https://wa.me/{{ config('company.whatsapp') }}" target="_blank" rel="noopener noreferrer">{{ config('company.phone') }}</a>
                    </li>
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i><a href="mailto:{{ config('company.email') }}">{{ config('company.email') }}</a></li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i><a href="{{ config('company.maps_url') }}" target="_blank" rel="noopener noreferrer">{{ config('company.address') }}</a></li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="text-center small">
            <div class="mb-2">
                <a href="{{ route('privacy') }}" class="me-3">Kebijakan Privasi</a>
                <a href="{{ route('terms') }}">Syarat Layanan</a>
            </div>
            <span>&copy; {{ date('Y') }} {{ config('company.name') }}. All rights reserved.</span>
        </div>
    </div>
</footer>

<!-- WhatsApp Float Button -->
<a href="https://wa.me/{{ config('company.whatsapp') }}" class="wa-float" target="_blank" title="Chat WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true });</script>
<script>
    (() => {
        const root = document.documentElement;
        const toggle = document.getElementById('theme-toggle');
        if (!toggle) return;

        const applyTheme = (isDark) => {
            root.classList.toggle('dark', isDark);
            const label = isDark ? 'Ganti ke mode terang' : 'Ganti ke mode gelap';
            toggle.setAttribute('aria-label', label);
            toggle.setAttribute('title', label);
            toggle.setAttribute('aria-pressed', String(isDark));
        };

        applyTheme(root.classList.contains('dark'));
        toggle.addEventListener('click', () => {
            const isDark = !root.classList.contains('dark');
            applyTheme(isDark);
            try {
                localStorage.setItem('theme-mode', isDark ? 'dark' : 'light');
            } catch (error) {
                console.warn('Theme preference could not be saved in this browser.', error);
            }
        });
    })();
</script>
<script>
    document.addEventListener('click', (event) => {
        const target = event.target instanceof Element ? event.target : null;
        const link = target?.closest('a[href*="wa.me/"]');
        if (!link) return;

        const productName = link.dataset.productName
            || link.closest('[data-product-name]')?.dataset.productName
            || null;
        const eventData = {
            event: 'whatsapp_click',
            page_source: window.location.pathname,
            product_name: productName,
        };

        if (typeof window.gtag === 'function') {
            window.gtag('event', 'whatsapp_click', {
                page_source: eventData.page_source,
                product_name: eventData.product_name,
            });
        } else if (window.google_tag_manager && Array.isArray(window.dataLayer)) {
            window.dataLayer.push(eventData);
        }
    });
</script>
@stack('scripts')
</body>
</html>
