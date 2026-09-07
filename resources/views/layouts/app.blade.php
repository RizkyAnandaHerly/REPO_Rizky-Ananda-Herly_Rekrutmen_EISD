<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ResiCycle — Sistem Pengelolaan Setoran Sampah Terpilah Komunitas">
    <title>@yield('title', 'ResiCycle') — ResiCycle</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --rc-primary: #2D6A4F;
            --rc-primary-light: #40916C;
            --rc-primary-lighter: #52B788;
            --rc-primary-pale: #B7E4C7;
            --rc-primary-bg: #D8F3DC;
            --rc-accent: #1B4332;
            --rc-dark: #081C15;
            --rc-warning: #E9C46A;
            --rc-danger: #E76F51;
            --rc-surface: #FFFFFF;
            --rc-surface-alt: #F8FBF9;
            --rc-text: #1A1A2E;
            --rc-text-muted: #6C757D;
            --rc-radius: 12px;
            --rc-radius-lg: 16px;
            --rc-shadow: 0 2px 12px rgba(45, 106, 79, 0.08);
            --rc-shadow-lg: 0 8px 32px rgba(45, 106, 79, 0.12);
            --rc-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: var(--rc-surface-alt);
            color: var(--rc-text);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .heading {
            font-family: 'Outfit', sans-serif;
        }

        /* ── Navbar ── */
        .navbar-rc {
            background: linear-gradient(135deg, var(--rc-accent) 0%, var(--rc-primary) 100%);
            padding: 0.75rem 0;
            box-shadow: var(--rc-shadow-lg);
        }

        .navbar-rc .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: #fff !important;
            letter-spacing: -0.5px;
        }

        .navbar-rc .navbar-brand i {
            color: var(--rc-primary-lighter);
        }

        .navbar-rc .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: var(--rc-transition);
        }

        .navbar-rc .nav-link:hover,
        .navbar-rc .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.12);
        }

        .navbar-rc .btn-logout {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            transition: var(--rc-transition);
        }

        .navbar-rc .btn-logout:hover {
            background: rgba(231, 111, 81, 0.8);
            border-color: transparent;
        }

        /* ── Cards ── */
        .card-rc {
            background: var(--rc-surface);
            border: 1px solid rgba(45, 106, 79, 0.08);
            border-radius: var(--rc-radius-lg);
            box-shadow: var(--rc-shadow);
            transition: var(--rc-transition);
        }

        .card-rc:hover {
            box-shadow: var(--rc-shadow-lg);
            transform: translateY(-2px);
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: linear-gradient(135deg, var(--rc-primary) 0%, var(--rc-primary-light) 100%);
            color: #fff;
            border-radius: var(--rc-radius-lg);
            padding: 1.25rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }

        .stat-card .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            opacity: 0.85;
            margin-top: 0.25rem;
        }

        .stat-card.stat-warning {
            background: linear-gradient(135deg, #E9C46A 0%, #F4A261 100%);
        }

        .stat-card.stat-info {
            background: linear-gradient(135deg, #264653 0%, #2A9D8F 100%);
        }

        /* ── Buttons ── */
        .btn-rc {
            background: linear-gradient(135deg, var(--rc-primary) 0%, var(--rc-primary-light) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            transition: var(--rc-transition);
            box-shadow: 0 4px 12px rgba(45, 106, 79, 0.3);
        }

        .btn-rc:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(45, 106, 79, 0.4);
            color: #fff;
        }

        .btn-rc:active {
            transform: translateY(0);
        }

        .btn-outline-rc {
            background: transparent;
            color: var(--rc-primary);
            border: 2px solid var(--rc-primary);
            border-radius: 10px;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            font-size: 0.9rem;
            transition: var(--rc-transition);
        }

        .btn-outline-rc:hover {
            background: var(--rc-primary);
            color: #fff;
        }

        .btn-danger-rc {
            background: linear-gradient(135deg, #E76F51 0%, #E63946 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: var(--rc-transition);
        }

        .btn-danger-rc:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(231, 111, 81, 0.4);
            color: #fff;
        }

        .btn-warning-rc {
            background: linear-gradient(135deg, #E9C46A 0%, #F4A261 100%);
            color: var(--rc-dark);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: var(--rc-transition);
        }

        /* ── Status Badges ── */
        .badge-status {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            text-transform: capitalize;
        }

        .badge-pending {
            background: rgba(233, 196, 106, 0.15);
            color: #D4890E;
        }

        .badge-verified {
            background: rgba(45, 106, 79, 0.12);
            color: var(--rc-primary);
        }

        .badge-rejected {
            background: rgba(231, 111, 81, 0.12);
            color: #C0392B;
        }

        .badge-cancelled {
            background: rgba(108, 117, 125, 0.12);
            color: var(--rc-text-muted);
        }

        /* ── Tables ── */
        .table-rc {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-rc thead th {
            background: var(--rc-primary-bg);
            color: var(--rc-accent);
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 0.85rem 1rem;
        }

        .table-rc thead th:first-child {
            border-radius: var(--rc-radius) 0 0 0;
        }

        .table-rc thead th:last-child {
            border-radius: 0 var(--rc-radius) 0 0;
        }

        .table-rc tbody td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(45, 106, 79, 0.06);
            font-size: 0.9rem;
        }

        .table-rc tbody tr:hover {
            background: var(--rc-primary-bg);
        }

        /* ── Forms ── */
        .form-control-rc, .form-select-rc {
            border: 2px solid rgba(45, 106, 79, 0.15);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            transition: var(--rc-transition);
        }

        .form-control-rc:focus, .form-select-rc:focus {
            border-color: var(--rc-primary);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.12);
        }

        .form-label-rc {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--rc-accent);
            margin-bottom: 0.4rem;
        }

        /* ── Flash Messages ── */
        .flash-message {
            border-radius: var(--rc-radius);
            border: none;
            font-weight: 500;
            padding: 0.85rem 1.25rem;
            box-shadow: var(--rc-shadow);
        }

        .flash-success {
            background: linear-gradient(135deg, rgba(45, 106, 79, 0.08) 0%, rgba(82, 183, 136, 0.08) 100%);
            color: var(--rc-primary);
            border-left: 4px solid var(--rc-primary-lighter);
        }

        .flash-error {
            background: linear-gradient(135deg, rgba(231, 111, 81, 0.08) 0%, rgba(230, 57, 70, 0.08) 100%);
            color: #C0392B;
            border-left: 4px solid var(--rc-danger);
        }

        /* ── Page Container ── */
        .page-container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .page-header {
            margin-bottom: 1.75rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--rc-accent);
        }

        .page-header p {
            color: var(--rc-text-muted);
            margin-bottom: 0;
        }

        /* ── Category Checkbox Card ── */
        .category-check {
            border: 2px solid rgba(45, 106, 79, 0.12);
            border-radius: var(--rc-radius);
            padding: 1rem 1.25rem;
            transition: var(--rc-transition);
            cursor: pointer;
        }

        .category-check:hover {
            border-color: var(--rc-primary-lighter);
            background: var(--rc-primary-bg);
        }

        .category-check.selected {
            border-color: var(--rc-primary);
            background: rgba(45, 106, 79, 0.04);
        }

        /* ── Auth Pages ── */
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--rc-accent) 0%, var(--rc-primary) 50%, var(--rc-primary-light) 100%);
            padding: 2rem 1rem;
        }

        .auth-card {
            background: var(--rc-surface);
            border-radius: var(--rc-radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
        }

        .auth-card .auth-logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--rc-primary);
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .auth-card .auth-subtitle {
            text-align: center;
            color: var(--rc-text-muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* ── Detail Section ── */
        .detail-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--rc-text-muted);
            margin-bottom: 0.2rem;
        }

        .detail-value {
            font-size: 1rem;
            font-weight: 500;
            color: var(--rc-text);
        }

        /* ── Animation Helpers ── */
        .gsap-fade {
            opacity: 0;
            transform: translateY(20px);
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .page-container {
                padding: 1rem 0.75rem;
            }

            .page-header h1 {
                font-size: 1.35rem;
            }

            .stat-card .stat-value {
                font-size: 1.5rem;
            }

            .auth-card {
                padding: 1.75rem;
            }
        }
    </style>
</head>
<body>
    @auth
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-rc">
            <div class="container">
                <a class="navbar-brand" href="{{ auth()->user()->isAdmin() ? route('admin.bookings.index') : route('bookings.index') }}">
                    <i class="bi bi-recycle me-1"></i> ResiCycle
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                    <i class="bi bi-list text-white fs-4"></i>
                </button>
                <div class="collapse navbar-collapse" id="navMain">
                    <ul class="navbar-nav me-auto ms-3">
                        @if(auth()->user()->isResident())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('bookings.index') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                                    <i class="bi bi-list-check me-1"></i> Riwayat Setoran
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('bookings.create') ? 'active' : '' }}" href="{{ route('bookings.create') }}">
                                    <i class="bi bi-plus-circle me-1"></i> Ajukan Setoran
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                                    <i class="bi bi-inbox me-1"></i> Semua Setoran
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.waste-categories.*') ? 'active' : '' }}" href="{{ route('admin.waste-categories.index') }}">
                                    <i class="bi bi-tags me-1"></i> Kategori Sampah
                                </a>
                            </li>
                        @endif
                    </ul>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-white-50 d-none d-lg-inline" style="font-size: 0.85rem;">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->name }}
                            <span class="badge bg-white bg-opacity-25 ms-1" style="font-size: 0.7rem;">{{ ucfirst(auth()->user()->role) }}</span>
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-logout btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="flash-message flash-success gsap-fade" id="flash-success">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="flash-message flash-error gsap-fade" id="flash-error">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Content -->
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- GSAP CDN -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fade-in page content
            gsap.from('.page-container', {
                opacity: 0,
                y: 20,
                duration: 0.5,
                ease: 'power2.out'
            });

            // Flash message animation
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(el) {
                gsap.to(el, {
                    opacity: 1,
                    y: 0,
                    duration: 0.4,
                    ease: 'back.out(1.7)'
                });

                // Auto-dismiss after 5 seconds
                gsap.to(el, {
                    opacity: 0,
                    y: -10,
                    duration: 0.3,
                    delay: 5,
                    ease: 'power2.in',
                    onComplete: function() { el.remove(); }
                });
            });

            // Stat cards stagger animation
            gsap.from('.stat-card', {
                opacity: 0,
                y: 30,
                duration: 0.5,
                stagger: 0.1,
                ease: 'power2.out',
                delay: 0.2
            });

            // Table rows stagger
            gsap.from('.table-rc tbody tr', {
                opacity: 0,
                x: -15,
                duration: 0.3,
                stagger: 0.04,
                ease: 'power2.out',
                delay: 0.3
            });

            // Card hover micro-interaction
            document.querySelectorAll('.card-rc').forEach(function(card) {
                card.addEventListener('mouseenter', function() {
                    gsap.to(card, { scale: 1.01, duration: 0.2, ease: 'power2.out' });
                });
                card.addEventListener('mouseleave', function() {
                    gsap.to(card, { scale: 1, duration: 0.2, ease: 'power2.out' });
                });
            });

            // Button submit feedback
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function() {
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn) {
                        gsap.to(btn, { scale: 0.95, duration: 0.1, yoyo: true, repeat: 1 });
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
