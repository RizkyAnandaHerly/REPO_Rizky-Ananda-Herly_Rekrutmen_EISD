<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResiCycle — Community Waste Deposit Management</title>
    <meta name="description" content="ResiCycle: Sistem pengelolaan setoran sampah terpilah berbasis komunitas. Dukung SDG 11 — Kota dan Permukiman Berkelanjutan.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --rc-primary: #2D6A4F;
            --rc-primary-light: #52B788;
            --rc-primary-lighter: #74C69D;
            --rc-accent: #264653;
            --rc-teal: #2A9D8F;
            --rc-yellow: #E9C46A;
            --rc-orange: #F4A261;
            --rc-coral: #E76F51;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background: #0A0F0D;
            color: #EAEAEA;
            overflow-x: hidden;
        }

        /* ── Navigation ── */
        .landing-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.4s ease;
        }

        .landing-nav.scrolled {
            background: rgba(10, 15, 13, 0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(45, 106, 79, 0.15);
            padding: 0.75rem 2rem;
        }

        .nav-logo {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-logo i { color: var(--rc-primary-lighter); font-size: 1.5rem; }

        .nav-actions { display: flex; gap: 0.75rem; }

        .btn-nav {
            padding: 0.55rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-nav-outline {
            color: #fff;
            border: 1.5px solid rgba(255,255,255,0.25);
            background: transparent;
        }

        .btn-nav-outline:hover {
            border-color: var(--rc-primary-lighter);
            color: var(--rc-primary-lighter);
            background: rgba(82, 183, 136, 0.08);
        }

        .btn-nav-fill {
            color: #fff;
            background: linear-gradient(135deg, var(--rc-primary) 0%, var(--rc-teal) 100%);
            border: none;
            box-shadow: 0 4px 20px rgba(45, 106, 79, 0.4);
        }

        .btn-nav-fill:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(45, 106, 79, 0.5);
            color: #fff;
        }

        /* ── Hero Section ── */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6rem 2rem 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero-bg-gradient {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 40%, rgba(45, 106, 79, 0.15) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 20% 80%, rgba(42, 157, 143, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 80% 20%, rgba(233, 196, 106, 0.05) 0%, transparent 60%);
            pointer-events: none;
        }

        .hero-particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--rc-primary-lighter);
            border-radius: 50%;
            opacity: 0;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1.25rem;
            border-radius: 50px;
            background: rgba(45, 106, 79, 0.15);
            border: 1px solid rgba(82, 183, 136, 0.25);
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--rc-primary-lighter);
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 900;
            line-height: 1.08;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .hero-title .gradient-text {
            background: linear-gradient(135deg, var(--rc-primary-lighter) 0%, var(--rc-teal) 50%, var(--rc-yellow) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: rgba(234, 234, 234, 0.65);
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto 2.5rem;
            font-weight: 300;
        }

        .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

        .btn-hero {
            padding: 0.85rem 2.25rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-hero-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--rc-primary) 0%, var(--rc-teal) 100%);
            box-shadow: 0 6px 25px rgba(45, 106, 79, 0.45);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 40px rgba(45, 106, 79, 0.6);
            color: #fff;
        }

        .btn-hero-ghost {
            color: rgba(255,255,255,0.85);
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.15);
        }

        .btn-hero-ghost:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.3);
            color: #fff;
            transform: translateY(-2px);
        }

        .hero-scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 1.5rem;
        }

        /* ── Sections Base ── */
        .section {
            padding: 6rem 2rem;
            position: relative;
        }

        .section-inner {
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            background: rgba(45, 106, 79, 0.1);
            border: 1px solid rgba(82, 183, 136, 0.2);
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--rc-primary-lighter);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.25rem;
        }

        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }

        .section-desc {
            font-size: 1.05rem;
            color: rgba(234, 234, 234, 0.55);
            line-height: 1.7;
            max-width: 550px;
            font-weight: 300;
        }

        /* ── SDG Section ── */
        .sdg-section { background: linear-gradient(180deg, #0A0F0D 0%, #0D1412 100%); }

        .sdg-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .sdg-visual { display: flex; justify-content: center; align-items: center; }

        .sdg-badge-large {
            width: 220px;
            height: 220px;
            border-radius: 30px;
            background: linear-gradient(145deg, rgba(42,157,143,0.15) 0%, rgba(45,106,79,0.15) 100%);
            border: 2px solid rgba(42, 157, 143, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
        }

        .sdg-badge-large::before {
            content: '';
            position: absolute;
            inset: -15px;
            border-radius: 38px;
            border: 1px solid rgba(42, 157, 143, 0.08);
        }

        .sdg-number { font-size: 4rem; font-weight: 900; color: var(--rc-teal); line-height: 1; }
        .sdg-text { font-size: 0.75rem; font-weight: 600; color: rgba(42, 157, 143, 0.7); text-transform: uppercase; letter-spacing: 2px; }
        .sdg-subtitle { font-size: 0.82rem; color: rgba(42, 157, 143, 0.5); text-align: center; margin-top: 0.5rem; }

        /* ── How It Works ── */
        .how-section { background: #0D1412; }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3.5rem;
        }

        .step-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .step-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--rc-primary), var(--rc-teal));
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .step-card:hover { transform: translateY(-6px); border-color: rgba(45, 106, 79, 0.2); background: rgba(45, 106, 79, 0.04); }
        .step-card:hover::before { opacity: 1; }

        .step-number {
            width: 50px; height: 50px;
            border-radius: 15px;
            background: linear-gradient(135deg, var(--rc-primary) 0%, var(--rc-teal) 100%);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.2rem; font-weight: 800; color: #fff;
            margin-bottom: 1.5rem;
        }

        .step-icon { font-size: 2rem; color: var(--rc-primary-lighter); margin-bottom: 1rem; }
        .step-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.75rem; }
        .step-desc { font-size: 0.88rem; color: rgba(234, 234, 234, 0.5); line-height: 1.65; }

        /* ── Features ── */
        .features-section { background: linear-gradient(180deg, #0D1412 0%, #0A0F0D 100%); }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 18px;
            padding: 2rem;
            transition: all 0.4s ease;
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
        }

        .feature-card:hover { background: rgba(45, 106, 79, 0.05); border-color: rgba(45, 106, 79, 0.2); transform: translateY(-4px); }

        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; flex-shrink: 0;
        }

        .feature-icon-green { background: rgba(45, 106, 79, 0.15); color: var(--rc-primary-lighter); }
        .feature-icon-teal { background: rgba(42, 157, 143, 0.15); color: var(--rc-teal); }
        .feature-icon-yellow { background: rgba(233, 196, 106, 0.15); color: var(--rc-yellow); }
        .feature-icon-orange { background: rgba(244, 162, 97, 0.15); color: var(--rc-orange); }

        .feature-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; }
        .feature-desc { font-size: 0.88rem; color: rgba(234, 234, 234, 0.5); line-height: 1.6; }

        /* ── CTA ── */
        .cta-section { background: linear-gradient(180deg, #0A0F0D 0%, #0D1412 100%); text-align: center; padding: 7rem 2rem; }

        .cta-glow {
            width: 300px; height: 300px;
            position: absolute; border-radius: 50%;
            background: radial-gradient(circle, rgba(45,106,79,0.15) 0%, transparent 70%);
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .cta-title { font-size: clamp(1.6rem, 3.5vw, 2.5rem); font-weight: 800; margin-bottom: 1rem; }
        .cta-desc { font-size: 1.05rem; color: rgba(234,234,234,0.55); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto; }

        /* ── Footer ── */
        .landing-footer {
            background: #080C0A;
            border-top: 1px solid rgba(255,255,255,0.04);
            padding: 2rem; text-align: center;
            font-size: 0.82rem; color: rgba(234,234,234,0.3);
        }
        .landing-footer a { color: var(--rc-primary-lighter); text-decoration: none; }

        /* ── GSAP reveal ── */
        .reveal { opacity: 0; transform: translateY(40px); }
        .reveal-left { opacity: 0; transform: translateX(-50px); }
        .reveal-right { opacity: 0; transform: translateX(50px); }
        .reveal-scale { opacity: 0; transform: scale(0.8); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sdg-grid { grid-template-columns: 1fr; gap: 2rem; text-align: center; }
            .sdg-visual { order: -1; }
            .section-desc { margin-left: auto; margin-right: auto; }
            .steps-container { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
            .nav-actions .btn-nav-outline { display: none; }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="landing-nav" id="landingNav">
        <a href="/" class="nav-logo">
            <i class="bi bi-recycle"></i>
            ResiCycle
        </a>
        <div class="nav-actions">
            <a href="/login" class="btn-nav btn-nav-outline">Masuk</a>
            <a href="/register" class="btn-nav btn-nav-fill">
                <i class="bi bi-person-plus"></i> Daftar
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="hero-bg-gradient"></div>
        <div class="hero-particles" id="particles"></div>

        <div class="hero-content">
            <div class="hero-badge reveal-scale">
                <i class="bi bi-globe-asia-australia"></i>
                SDG 11 — Kota Berkelanjutan
            </div>

            <h1 class="hero-title reveal">
                Kelola Setoran Sampah<br>
                <span class="gradient-text">Komunitas Anda</span>
            </h1>

            <p class="hero-subtitle reveal">
                ResiCycle mendigitalisasi alur setoran sampah terpilah di TPS3R dan Bank Sampah komunitas — dari pengajuan warga hingga verifikasi petugas.
            </p>

            <div class="hero-cta reveal">
                <a href="/register" class="btn-hero btn-hero-primary">
                    <i class="bi bi-rocket-takeoff"></i> Mulai Sekarang
                </a>
                <a href="#how-it-works" class="btn-hero btn-hero-ghost">
                    <i class="bi bi-play-circle"></i> Cara Kerja
                </a>
            </div>
        </div>

        <div class="hero-scroll-indicator">
            <i class="bi bi-chevron-double-down" id="scrollIndicator"></i>
        </div>
    </section>

    <!-- SDG Section -->
    <section class="section sdg-section" id="sdg">
        <div class="section-inner">
            <div class="sdg-grid">
                <div class="reveal-left">
                    <div class="section-label"><i class="bi bi-bullseye"></i> Misi Kami</div>
                    <h2 class="section-title">Mendukung Tujuan<br>Pembangunan<br><span class="gradient-text">Berkelanjutan</span></h2>
                    <p class="section-desc" style="margin-top: 1rem;">
                        ResiCycle berfokus pada SDG 11 target 11.6 — mengurangi dampak lingkungan perkotaan melalui pengelolaan sampah yang lebih terstruktur dan terdokumentasi di level komunitas.
                    </p>
                </div>
                <div class="sdg-visual reveal-right">
                    <div>
                        <div class="sdg-badge-large">
                            <div class="sdg-text">SDG</div>
                            <div class="sdg-number">11</div>
                            <div class="sdg-text">Target 11.6</div>
                        </div>
                        <div class="sdg-subtitle">Sustainable Cities & Communities</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="section how-section" id="how-it-works">
        <div class="section-inner" style="text-align: center;">
            <div class="section-label"><i class="bi bi-diagram-3"></i> Cara Kerja</div>
            <h2 class="section-title">Tiga Langkah<br><span class="gradient-text">Sederhana</span></h2>
            <p class="section-desc" style="margin: 0.5rem auto 0;">Dari pengajuan sampai poin kontribusi — semuanya tercatat digital.</p>

            <div class="steps-container">
                <div class="step-card reveal">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="bi bi-pencil-square"></i></div>
                    <h3 class="step-title">Ajukan Setoran</h3>
                    <p class="step-desc">Pilih tanggal, kategori sampah, dan estimasi berat. Sistem otomatis memberi nomor antrian.</p>
                </div>

                <div class="step-card reveal">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="bi bi-truck"></i></div>
                    <h3 class="step-title">Datang ke TPS3R</h3>
                    <p class="step-desc">Bawa sampah terpilah sesuai jadwal. Cek posisi antrian sebelum berangkat.</p>
                </div>

                <div class="step-card reveal">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="bi bi-patch-check"></i></div>
                    <h3 class="step-title">Verifikasi & Poin</h3>
                    <p class="step-desc">Petugas menimbang dan memverifikasi. Poin kontribusi langsung tercatat di akun Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="section features-section" id="features">
        <div class="section-inner">
            <div class="section-label"><i class="bi bi-stars"></i> Fitur Utama</div>
            <h2 class="section-title">Semua yang<br><span class="gradient-text">Anda Butuhkan</span></h2>
            <p class="section-desc">Didesain untuk warga dan petugas Bank Sampah.</p>

            <div class="features-grid">
                <div class="feature-card reveal">
                    <div class="feature-icon feature-icon-green"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <h4 class="feature-title">Booking Setoran</h4>
                        <p class="feature-desc">Ajukan setoran drop-off kapan saja dengan pilihan tanggal dan kategori sampah.</p>
                    </div>
                </div>

                <div class="feature-card reveal">
                    <div class="feature-icon feature-icon-teal"><i class="bi bi-people"></i></div>
                    <div>
                        <h4 class="feature-title">Sistem Antrian</h4>
                        <p class="feature-desc">Lihat jumlah antrian sebelum booking. Pantau posisi antrian Anda secara real-time.</p>
                    </div>
                </div>

                <div class="feature-card reveal">
                    <div class="feature-icon feature-icon-yellow"><i class="bi bi-trophy"></i></div>
                    <div>
                        <h4 class="feature-title">Poin Kontribusi</h4>
                        <p class="feature-desc">Setiap setoran terverifikasi menghasilkan poin berdasarkan berat aktual sampah.</p>
                    </div>
                </div>

                <div class="feature-card reveal">
                    <div class="feature-icon feature-icon-orange"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h4 class="feature-title">Verifikasi Admin</h4>
                        <p class="feature-desc">Petugas memverifikasi berat aktual setiap kategori. Data akurat dan transparan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section cta-section" id="cta">
        <div class="cta-glow"></div>
        <div class="section-inner" style="position: relative; z-index: 2;">
            <h2 class="cta-title reveal">Siap Berkontribusi untuk<br><span class="gradient-text">Lingkungan Anda?</span></h2>
            <p class="cta-desc reveal">Bergabung dengan komunitas warga yang peduli lingkungan. Daftarkan diri Anda di ResiCycle sekarang.</p>
            <div class="reveal">
                <a href="/register" class="btn-hero btn-hero-primary">
                    <i class="bi bi-arrow-right-circle"></i> Daftar Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <p>&copy; {{ date('Y') }} <a href="/">ResiCycle</a> — Community Waste Deposit Management System | SDG 11</p>
    </footer>

    <!-- GSAP CDN -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // ── Nav scroll effect ──
        window.addEventListener('scroll', () => {
            document.getElementById('landingNav').classList.toggle('scrolled', window.scrollY > 50);
        });

        // ── Hero Animations ──
        const heroTl = gsap.timeline({ delay: 0.3 });

        heroTl
            .to('.reveal-scale', { opacity: 1, scale: 1, duration: 0.6, ease: 'back.out(1.7)' })
            .to('.hero-content .reveal', {
                opacity: 1, y: 0, duration: 0.7, stagger: 0.15, ease: 'power3.out'
            }, '-=0.3')
            .to('.hero-scroll-indicator', { opacity: 1, duration: 0.5 }, '-=0.2');

        // Scroll indicator bounce
        gsap.to('#scrollIndicator', {
            y: 8, duration: 0.8, repeat: -1, yoyo: true, ease: 'power1.inOut'
        });

        // ── Particle System ──
        const particlesEl = document.getElementById('particles');
        for (let i = 0; i < 30; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.top = Math.random() * 100 + '%';
            p.style.width = (2 + Math.random() * 3) + 'px';
            p.style.height = p.style.width;
            particlesEl.appendChild(p);

            gsap.to(p, {
                opacity: 0.3 + Math.random() * 0.4,
                duration: 2 + Math.random() * 3,
                repeat: -1,
                yoyo: true,
                delay: Math.random() * 3,
                ease: 'sine.inOut'
            });

            gsap.to(p, {
                y: -30 - Math.random() * 60,
                x: -15 + Math.random() * 30,
                duration: 5 + Math.random() * 5,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut'
            });
        }

        // ── Scroll Reveal ──
        gsap.utils.toArray('.reveal').forEach(el => {
            if (el.closest('.hero-content')) return;
            gsap.to(el, {
                scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' },
                opacity: 1, y: 0, duration: 0.8, ease: 'power3.out'
            });
        });

        gsap.utils.toArray('.reveal-left').forEach(el => {
            gsap.to(el, {
                scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' },
                opacity: 1, x: 0, duration: 0.9, ease: 'power3.out'
            });
        });

        gsap.utils.toArray('.reveal-right').forEach(el => {
            gsap.to(el, {
                scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' },
                opacity: 1, x: 0, duration: 0.9, ease: 'power3.out'
            });
        });

        // Step cards stagger
        ScrollTrigger.create({
            trigger: '.steps-container',
            start: 'top 80%',
            onEnter: () => {
                gsap.to('.step-card', { opacity: 1, y: 0, duration: 0.7, stagger: 0.2, ease: 'power3.out' });
            }
        });

        // Feature cards stagger
        ScrollTrigger.create({
            trigger: '.features-grid',
            start: 'top 80%',
            onEnter: () => {
                gsap.to('.feature-card', { opacity: 1, y: 0, duration: 0.6, stagger: 0.15, ease: 'power3.out' });
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>

</body>
</html>
