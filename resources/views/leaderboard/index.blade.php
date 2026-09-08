@extends('layouts.app')

@section('title', 'Papan Peringkat Kontribusi')

@push('styles')
    <style>
        /* ── Leaderboard Hero Header ── */
        .leaderboard-hero {
            background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 55%, #40916C 100%);
            border-radius: var(--rc-radius-lg);
            color: #fff;
            padding: 2.25rem 2rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 14px 35px rgba(27, 67, 50, 0.25);
        }

        .leaderboard-hero::before {
            content: '';
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
            top: -120px;
            right: -80px;
            pointer-events: none;
        }

        /* Glassmorphic Stat Boxes in Hero (Fix: No more solid white on white text) */
        .hero-stat-box {
            background: rgba(255, 255, 255, 0.16) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 14px;
            padding: 0.65rem 1.25rem;
            text-align: center;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 140px;
        }

        .hero-stat-box .stat-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: rgba(255, 255, 255, 0.88);
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .hero-stat-box .stat-val {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: #FFFFFF !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
            line-height: 1.2;
        }

        /* ── Period Toolbar ── */
        .period-tab-btn {
            background: #FFFFFF;
            color: #2D6A4F;
            border: 2px solid rgba(45, 106, 79, 0.2);
            padding: 0.55rem 1.25rem;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.88rem;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .period-tab-btn:hover {
            background: #D8F3DC;
            color: #1B4332;
            border-color: #2D6A4F;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 106, 79, 0.15);
        }

        .period-tab-btn.active {
            background: #2D6A4F;
            color: #FFFFFF !important;
            border-color: #2D6A4F;
            box-shadow: 0 4px 14px rgba(45, 106, 79, 0.35);
        }

        /* ── Podium Cards (High-Contrast & Solid Depth) ── */
        .podium-container {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            padding-top: 1.5rem;
        }

        .podium-card {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 2rem 1.35rem 1.65rem;
            text-align: center;
            position: relative;
            flex: 1;
            max-width: 310px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .podium-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
        }

        /* ── Rank 1: Gold Champion ── */
        .podium-card.rank-1 {
            order: 2;
            border: 2px solid #F59E0B;
            background: linear-gradient(180deg, #FEFCE8 0%, #FFFFFF 30%);
            box-shadow: 0 14px 40px rgba(217, 119, 6, 0.22);
            padding-top: 2.5rem;
            padding-bottom: 2.2rem;
            transform: translateY(-16px);
        }

        .podium-card.rank-1:hover {
            transform: translateY(-22px);
            box-shadow: 0 20px 48px rgba(217, 119, 6, 0.3);
        }

        /* ── Rank 2: Silver ── */
        .podium-card.rank-2 {
            order: 1;
            border: 2px solid #94A3B8;
            background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 30%);
            box-shadow: 0 8px 25px rgba(71, 85, 105, 0.1);
        }

        /* ── Rank 3: Bronze (Fix: Bold copper colors, no fading) ── */
        .podium-card.rank-3 {
            order: 3;
            border: 2px solid #EA580C;
            background: linear-gradient(180deg, #FFF7ED 0%, #FFFFFF 30%);
            box-shadow: 0 8px 25px rgba(194, 65, 12, 0.1);
        }

        /* ── Rank Pill Badges on Top ── */
        .rank-pill {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            padding: 0.32rem 1.1rem;
            border-radius: 24px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: #FFFFFF !important;
            white-space: nowrap;
        }

        .rank-pill-1 {
            background: linear-gradient(135deg, #B45309 0%, #D97706 50%, #F59E0B 100%);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.4);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .rank-pill-2 {
            background: linear-gradient(135deg, #334155 0%, #475569 50%, #64748B 100%);
            box-shadow: 0 4px 12px rgba(71, 85, 105, 0.35);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .rank-pill-3 {
            background: linear-gradient(135deg, #7C2D12 0%, #C2410C 50%, #EA580C 100%);
            box-shadow: 0 4px 12px rgba(194, 65, 12, 0.4);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        /* ── Vector Badges ── */
        .vector-badge {
            width: 72px;
            height: 72px;
            margin: 0 auto 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rank-1 .vector-badge {
            width: 86px;
            height: 86px;
        }

        /* ── Avatars with High-Contrast Ring ── */
        .avatar-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            font-weight: 800;
            font-size: 1.3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.65rem;
        }

        .rank-1 .avatar-circle {
            width: 66px;
            height: 66px;
            font-size: 1.55rem;
            background: #FEF08A;
            color: #78350F;
            border: 3px solid #D97706;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
        }

        .rank-2 .avatar-circle {
            background: #E2E8F0;
            color: #1E293B;
            border: 3px solid #64748B;
            box-shadow: 0 4px 10px rgba(100, 116, 139, 0.2);
        }

        .rank-3 .avatar-circle {
            background: #FFEDD5;
            color: #7C2D12;
            border: 3px solid #C2410C;
            box-shadow: 0 4px 10px rgba(194, 65, 12, 0.2);
        }

        /* ── User Names & Points ── */
        .user-name-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1E293B;
            margin-bottom: 0.35rem;
        }

        .rank-1 .user-name-title {
            font-size: 1.35rem;
            color: #78350F;
        }

        .points-tag {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            line-height: 1.1;
        }

        .rank-1 .points-tag {
            font-size: 2rem;
            color: #B45309;
        }

        .rank-2 .points-tag {
            color: #0F766E;
        }

        .rank-3 .points-tag {
            color: #C2410C;
        }

        /* ── Remaining Ranks (Rank 4+) ── */
        .rank-row {
            background: #FFFFFF;
            border: 1px solid rgba(45, 106, 79, 0.12);
            border-radius: 14px;
            padding: 1rem 1.35rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }

        .rank-row:hover {
            background: #F8FAFC;
            border-color: #2D6A4F;
            transform: translateX(4px);
            box-shadow: 0 6px 16px rgba(45, 106, 79, 0.08);
        }

        .rank-row.is-current-user {
            border: 2px solid #2D6A4F;
            background: rgba(45, 106, 79, 0.05);
        }

        .rank-badge-pill {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #E2E8F0;
            color: #1E293B;
            font-weight: 800;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .podium-container {
                flex-direction: column;
                align-items: stretch;
                gap: 1.25rem;
            }

            .podium-card {
                max-width: 100%;
            }

            .podium-card.rank-1 {
                order: 1;
                transform: none;
            }

            .podium-card.rank-2 {
                order: 2;
            }

            .podium-card.rank-3 {
                order: 3;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-container">

        <!-- Hero Header -->
        <div class="leaderboard-hero">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <span class="badge bg-white bg-opacity-25 px-3 py-2 rounded-pill text-uppercase mb-2"
                        style="letter-spacing: 0.6px; font-size: 0.72rem; font-weight: 700;">
                        <i class="bi bi-shield-check me-1"></i> Eco-Impact Leaderboard &bull; SDG 11.6
                    </span>
                    <h1 class="mb-1 text-white" style="font-size: 1.95rem; font-weight: 800;">
                        <i class="bi bi-trophy-fill me-2" style="color: #FDE047;"></i> Papan Peringkat Kontribusi
                    </h1>
                    <p class="mb-0 text-white-50" style="font-size: 0.95rem;">
                        Apresiasi warga teraktif dalam memilah sampah dan menyetor ke TPS3R
                    </p>
                </div>

                <!-- Community Stats Quick Glance (Fixed: Glassmorphic with crystal clear white text) -->
                <div class="d-flex gap-2 flex-wrap">
                    <div class="hero-stat-box">
                        <div class="stat-label">
                            <i class="bi bi-star-fill me-1" style="color: #FDE047;"></i> Total Poin
                        </div>
                        <div class="stat-val counter-val" data-val="{{ $totalCommunityPoints }}">
                            {{ number_format($totalCommunityPoints) }}
                        </div>
                    </div>
                    <div class="hero-stat-box">
                        <div class="stat-label">
                            <i class="bi bi-box-seam-fill me-1" style="color: #6EE7B7;"></i> Total Setoran
                        </div>
                        <div class="stat-val counter-val" data-val="{{ $totalCommunityDeposits }}">
                            {{ number_format($totalCommunityDeposits) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Period Filter Toolbar -->
        <div class="card-rc p-3 mb-4" style="background: #FFFFFF; border: 1px solid rgba(45, 106, 79, 0.15);">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <!-- Period Tabs -->
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ route('leaderboard', ['period' => 'current_month']) }}"
                        class="period-tab-btn {{ $period === 'current_month' ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> Bulan Ini
                    </a>
                    <a href="{{ route('leaderboard', ['period' => 'current_year']) }}"
                        class="period-tab-btn {{ $period === 'current_year' ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i> Tahun Ini
                    </a>
                    <a href="{{ route('leaderboard', ['period' => 'all_time']) }}"
                        class="period-tab-btn {{ $period === 'all_time' ? 'active' : '' }}">
                        <i class="bi bi-stars"></i> Semua Waktu
                    </a>
                </div>

                <!-- Archive Dropdown (Previous Months) -->
                <form method="GET" action="{{ route('leaderboard') }}" class="d-flex align-items-center gap-2 m-0">
                    <input type="hidden" name="period" value="archive">
                    <label for="archiveSelect" class="small text-dark fw-bold text-nowrap d-none d-sm-inline"
                        style="font-size: 0.85rem;">
                        <i class="bi bi-clock-history me-1 text-primary"></i> Arsip Juara:
                    </label>
                    <select name="archive_val" id="archiveSelect" class="form-select form-select-sm form-select-rc"
                        style="width: auto; min-width: 170px;">
                        <option value="" disabled {{ $period !== 'archive' ? 'selected' : '' }}>-- Pilih Bulan Lain --
                        </option>
                        @foreach($availableDates as $d)
                            @php
                                $isSelected = ($period === 'archive' && $selectedMonth == $d['month'] && $selectedYear == $d['year']);
                            @endphp
                            <option value="{{ $d['year'] }}-{{ $d['month'] }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $d['label'] }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="small text-muted" style="font-size: 0.85rem;">
                    <i class="bi bi-info-circle me-1 text-primary"></i> Menampilkan peringkat untuk: <strong
                        class="text-dark">{{ $periodTitle }}</strong>
                </span>
                @if(auth()->user()->isResident() && $myRank)
                    <span class="badge bg-success text-white px-3 py-1 rounded-pill"
                        style="font-size: 0.82rem; font-weight: 700;">
                        <i class="bi bi-person-check-fill me-1"></i> Posisi Kamu: #{{ $myRank->rank }}
                        ({{ number_format($myRank->total_points) }} Poin)
                    </span>
                @endif
            </div>
        </div>

        @if($rankedUsers->isEmpty())
            <!-- Empty State -->
            <div class="card-rc p-5 text-center my-4" style="background: #FFFFFF;">
                <div class="vector-badge mb-3" style="width: 72px; height: 72px;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
                        <circle cx="12" cy="12" r="10" stroke="#2D6A4F" stroke-width="2" stroke-opacity="0.3" />
                        <path d="M12 7V13L16 15" stroke="#2D6A4F" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="fw-bold mb-2 text-dark">Belum Ada Kontribusi Terverifikasi</h4>
                <p class="text-muted mb-3" style="max-width: 440px; margin: 0 auto; font-size: 0.95rem;">
                    Belum ada setoran sampah yang diverifikasi pada periode <strong>{{ $periodTitle }}</strong>.
                    Jadilah yang pertama menyetor dan pimpin papan peringkat!
                </p>
                @if(auth()->user()->isResident())
                    <a href="{{ route('bookings.create') }}" class="btn btn-rc">
                        <i class="bi bi-plus-circle me-1"></i> Ajukan Setoran Sekarang
                    </a>
                @endif
            </div>
        @else

            <!-- Top 3 Podium Section -->
            <div class="podium-container">

                <!-- Rank 2: Silver -->
                @if($topThree->has(1))
                    @php $user2 = $topThree->get(1); @endphp
                    <div class="podium-card rank-2 gsap-podium">
                        <span class="rank-pill rank-pill-2">Peringkat 2</span>
                        <div class="vector-badge">
                            <!-- SVG Silver Metallic Shield Badge -->
                            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"
                                style="width: 100%; height: 100%;">
                                <defs>
                                    <linearGradient id="silverMedal" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#F8FAFC" />
                                        <stop offset="40%" stop-color="#CBD5E1" />
                                        <stop offset="80%" stop-color="#94A3B8" />
                                        <stop offset="100%" stop-color="#64748B" />
                                    </linearGradient>
                                </defs>
                                <circle cx="32" cy="32" r="28" fill="url(#silverMedal)" stroke="#64748B" stroke-width="2.5" />
                                <circle cx="32" cy="32" r="23" fill="#FFFFFF" fill-opacity="0.3" />
                                <path d="M32 18L42 24V34C42 41 32 46 32 46C32 46 22 41 22 34V24L32 18Z" fill="#FFFFFF"
                                    stroke="#334155" stroke-width="1.8" />
                                <path d="M32 24V38M27 31H37" stroke="#334155" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="avatar-circle">
                            {{ strtoupper(substr($user2->name, 0, 2)) }}
                        </div>
                        <h5 class="user-name-title text-truncate" title="{{ $user2->name }}">{{ $user2->name }}</h5>
                        <div class="points-tag mb-1 counter-val" data-val="{{ $user2->total_points }}">
                            {{ number_format($user2->total_points) }}</div>
                        <div class="badge bg-secondary bg-opacity-10 text-dark px-3 py-1 rounded-pill mt-1"
                            style="font-size: 0.78rem; font-weight: 700;">
                            <i class="bi bi-box-seam me-1 text-secondary"></i>{{ $user2->deposits_count }} Kali Setor
                        </div>
                    </div>
                @endif

                <!-- Rank 1: Gold Champion -->
                @if($topThree->has(0))
                    @php $champ = $topThree->get(0); @endphp
                    <div class="podium-card rank-1 gsap-podium">
                        <span class="rank-pill rank-pill-1">
                            <i class="bi bi-star-fill me-1" style="color: #FDE047;"></i> JUARA 1 &bull; ECO-HERO
                        </span>
                        <div class="vector-badge">
                            <!-- SVG Gold Metallic Crown Badge -->
                            <svg viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg"
                                style="width: 100%; height: 100%;">
                                <defs>
                                    <linearGradient id="goldMedal" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#FEF08A" />
                                        <stop offset="35%" stop-color="#FACC15" />
                                        <stop offset="70%" stop-color="#EAB308" />
                                        <stop offset="100%" stop-color="#B45309" />
                                    </linearGradient>
                                </defs>
                                <circle cx="36" cy="36" r="32" fill="url(#goldMedal)" stroke="#78350F" stroke-width="2.5" />
                                <circle cx="36" cy="36" r="26" fill="#FFFFFF" fill-opacity="0.35" />
                                <!-- Bold Vector Crown -->
                                <path d="M22 46L24 28L31 35L36 24L41 35L48 28L50 46H22Z" fill="#FEF08A" stroke="#78350F"
                                    stroke-width="2" stroke-linejoin="round" />
                                <circle cx="24" cy="26" r="2.5" fill="#78350F" />
                                <circle cx="36" cy="22" r="3" fill="#78350F" />
                                <circle cx="48" cy="26" r="2.5" fill="#78350F" />
                            </svg>
                        </div>
                        <div class="avatar-circle">
                            {{ strtoupper(substr($champ->name, 0, 2)) }}
                        </div>
                        <h4 class="user-name-title text-truncate" title="{{ $champ->name }}">
                            {{ $champ->name }}
                        </h4>
                        <div class="points-tag mb-1 counter-val" data-val="{{ $champ->total_points }}">
                            {{ number_format($champ->total_points) }}
                        </div>
                        <div class="badge bg-warning text-dark px-3 py-1 rounded-pill my-1"
                            style="font-size: 0.8rem; font-weight: 800; box-shadow: 0 2px 6px rgba(217, 119, 6, 0.2);">
                            <i class="bi bi-patch-check-fill me-1"></i> {{ $champ->deposits_count  }} Kali Setor
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 0.8rem; font-weight: 600;">
                            Kontributor Paling Berdampak
                        </small>
                    </div>
                @endif

                <!-- Rank 3: Bronze (Sharp, Vibrant, High Contrast) -->
                @if($topThree->has(2))
                    @php $user3 = $topThree->get(2); @endphp
                    <div class="podium-card rank-3 gsap-podium">
                        <span class="rank-pill rank-pill-3">Peringkat 3</span>
                        <div class="vector-badge">
                            <!-- SVG Bronze Metallic Star Badge -->
                            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"
                                style="width: 100%; height: 100%;">
                                <defs>
                                    <linearGradient id="bronzeMedal" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#FED7AA" />
                                        <stop offset="35%" stop-color="#FB923C" />
                                        <stop offset="70%" stop-color="#EA580C" />
                                        <stop offset="100%" stop-color="#9A3412" />
                                    </linearGradient>
                                </defs>
                                <circle cx="32" cy="32" r="28" fill="url(#bronzeMedal)" stroke="#7C2D12" stroke-width="2.5" />
                                <circle cx="32" cy="32" r="23" fill="#FFFFFF" fill-opacity="0.3" />
                                <path d="M32 20L35.5 27.5L43.5 28.5L37.5 34L39 42L32 38L25 42L26.5 34L20.5 28.5L28.5 27.5L32 20Z"
                                    fill="#FFFFFF" stroke="#7C2D12" stroke-width="1.8" />
                            </svg>
                        </div>
                        <div class="avatar-circle">
                            {{ strtoupper(substr($user3->name, 0, 2)) }}
                        </div>
                        <h5 class="user-name-title text-truncate" title="{{ $user3->name }}">{{ $user3->name }}</h5>
                        <div class="points-tag mb-1 counter-val" data-val="{{ $user3->total_points }}">
                            {{ number_format($user3->total_points) }}</div>
                        <div class="badge bg-secondary bg-opacity-10 text-dark px-3 py-1 rounded-pill mt-1"
                            style="font-size: 0.78rem; font-weight: 700;">
                            <i class="bi bi-box-seam me-1 text-secondary"></i>{{ $user3->deposits_count }} Kali Setor
                        </div>
                    </div>
                @endif

            </div>

            <!-- Rank 4+ List Section -->
            @if($remainingRanks->isNotEmpty())
                <div class="mt-4">
                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-list-ol me-2 text-primary"></i> Peringkat Warga Lainnya
                    </h5>
                    <div class="rank-list-container">
                        @foreach($remainingRanks as $user)
                            @php
                                $isMe = (auth()->check() && auth()->id() === $user->id);
                            @endphp
                            <div class="rank-row {{ $isMe ? 'is-current-user' : '' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rank-badge-pill">
                                        #{{ $user->rank }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 1rem;">
                                            {{ $user->name }}
                                            @if($isMe)
                                                <span class="badge bg-success text-white ms-1" style="font-size: 0.72rem;">Kamu</span>
                                            @endif
                                        </div>
                                        <small class="text-muted" style="font-size: 0.8rem;">
                                            <i class="bi bi-box-seam me-1"></i>{{ $user->deposits_count }} kali setor
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary fs-5 counter-val" data-val="{{ $user->total_points }}">
                                        {{ number_format($user->total_points) }}
                                    </div>
                                    <small class="text-muted"
                                        style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">Poin</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Parse archive select parameter if changed
            const archiveSelect = document.getElementById('archiveSelect');
            if (archiveSelect) {
                archiveSelect.addEventListener('change', function () {
                    const val = this.value;
                    if (val) {
                        const [y, m] = val.split('-');
                        window.location.href = "{{ route('leaderboard') }}?period=archive&year=" + y + "&month=" + m;
                    }
                });
            }

            // GSAP: Animate Y transform ONLY without messing with opacity (Fix: No more faded/gray cards!)
            if (typeof gsap !== 'undefined') {
                gsap.from('.gsap-podium', {
                    duration: 0.6,
                    y: 25,
                    stagger: 0.1,
                    ease: 'power2.out',
                    clearProps: 'transform'
                });

                // Smooth Number Counter-Up
                document.querySelectorAll('.counter-val').forEach(el => {
                    const target = parseInt(el.dataset.val || 0);
                    if (isNaN(target) || target === 0) return;

                    const counterObj = { val: 0 };
                    gsap.to(counterObj, {
                        val: target,
                        duration: 1.0,
                        ease: 'power2.out',
                        onUpdate: function () {
                            el.innerText = Math.round(counterObj.val).toLocaleString('id-ID');
                        }
                    });
                });
            }
        });
    </script>
@endsection