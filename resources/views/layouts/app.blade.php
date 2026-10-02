<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', config('company.description'))">
    <title>@yield('title', config('company.name')) | {{ config('company.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/kaizen-logo-color.png') }}">

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
            --brand-red: #f10c28;
            --brand-gradient: linear-gradient(105deg, #1736e8 8%, #8d0d86 52%, #f10c28 100%);
            --brand-wash: linear-gradient(120deg, #f0f4ff 0%, #fff 58%, #fff0f1 100%);
            --primary: #1736e8;
            --primary-dark: #1026a8;
            --secondary: #f10c28;
            --dark: #17192b;
            --light-bg: #f3f5ff;
        }
        * { font-family: 'Poppins', sans-serif; }
        body { background: #fff; color: #333; }

        /* Navbar */
        .navbar { background: rgba(255,255,255,0.97); box-shadow: 0 2px 20px rgba(14,28,95,0.1); }
        .navbar-brand { display:flex; align-items:center; gap:.55rem; font-weight:700; font-size:1.15rem; }
        .navbar-brand span { color:transparent; background:var(--brand-gradient); background-clip:text; -webkit-background-clip:text; }
        .brand-logo { width:48px; height:48px; object-fit:contain; flex:none; }
        .brand-logo-footer { width:38px; height:38px; }
        .nav-link { font-weight: 500; color: #444 !important; transition: color .2s; }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; }
        .btn-wa { background: #25d366; color: #fff; border-radius: 50px; padding: 8px 20px; font-weight: 600; }
        .btn-wa:hover { background: #128c7e; color: #fff; }
        .btn-primary-custom { background: var(--brand-gradient); color: #fff; border-radius: 50px; padding: 10px 28px; font-weight: 600; border: none; }
        .btn-primary-custom:hover { background: linear-gradient(105deg, var(--primary-dark), #b50925); color: #fff; }
        .brand-badge { background:#edf1ff; color:var(--primary); }

        /* Footer */
        footer { background: var(--dark); color: #ccc; }
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
    </style>

    @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/kaizen-logo-color.png') }}" alt="" class="brand-logo">
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
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('portfolio.*') ? 'active' : '' }}" href="{{ route('portfolio.index') }}">Portofolio</a>
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
                    <img src="{{ asset('images/kaizen-logo-color.png') }}" alt="" class="brand-logo brand-logo-footer">
                    <span>{{ config('company.name') }}</span>
                </div>
                <p class="small">{{ config('company.description') }}</p>
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
                    <li class="mb-2"><a href="{{ route('portfolio.index') }}">Portofolio</a></li>
                    <li class="mb-2"><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="mb-2"><a href="{{ route('about') }}">Tentang Kami</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6">
                <h6 class="text-white fw-600 mb-3">Jam Operasional</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1"><i class="bi bi-clock me-1"></i> {{ config('company.hours.weekday') }}</li>
                    <li class="mb-1"><i class="bi bi-clock me-1"></i> {{ config('company.hours.weekend') }}</li>
                    <li class="mb-1"><i class="bi bi-info-circle me-1"></i> {{ config('company.hours.holiday') }}</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="text-white fw-600 mb-3">Kontak</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>{{ config('company.phone') }}</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i>{{ config('company.email') }}</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>{{ config('company.address') }}</li>
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
@stack('scripts')
</body>
</html>
