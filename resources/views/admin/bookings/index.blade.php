@extends('layouts.app')

@section('title', $view === 'history' ? 'Riwayat Setoran' : 'Setoran Masuk')

@section('content')
    <div class="page-container">
        <div class="page-header">
            <h1><i class="bi bi-inbox me-2"></i>Manajemen Setoran</h1>
            <p>Kelola setoran warga — verifikasi, tolak, atau lihat riwayat</p>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-4">
            <div class="d-flex gap-2" style="border-bottom: 2px solid rgba(45,106,79,0.1); padding-bottom: 0;">
                <a href="{{ route('admin.bookings.index') }}" class="admin-tab {{ $view !== 'history' ? 'active' : '' }}">
                    <i class="bi bi-hourglass-split me-1"></i> Setoran Masuk
                    @if($pendingCount > 0)
                        <span class="tab-badge">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.bookings.index', ['view' => 'history']) }}"
                    class="admin-tab {{ $view === 'history' ? 'active' : '' }}">
                    <i class="bi bi-clock-history me-1"></i> Riwayat
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            @if($view !== 'history')
                <div class="col-sm-6 col-lg-4">
                    <div class="stat-card stat-warning">
                        <div class="stat-value">{{ $pendingCount }}</div>
                        <div class="stat-label"><i class="bi bi-hourglass-split me-1"></i> Total Menunggu</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="stat-card" style="border-left: 4px solid var(--rc-teal);">
                        <div class="stat-value">{{ $todayPendingCount }}</div>
                        <div class="stat-label"><i class="bi bi-calendar-event me-1"></i> Jadwal Hari Ini</div>
                    </div>
                </div>
            @else
                <div class="col-sm-6 col-lg-4">
                    <div class="stat-card" style="border-left: 4px solid var(--rc-primary-lighter);">
                        <div class="stat-value">{{ $bookings->total() }}</div>
                        <div class="stat-label"><i class="bi bi-archive me-1"></i> Total Riwayat</div>
                    </div>
                </div>
            @endif
        </div>
        <!-- Filter Bar (History tab only) -->
        @if($view === 'history')
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="filter-bar card-rc p-3 mb-4">
                <input type="hidden" name="view" value="history">
                <div class="row g-2 align-items-end">
                    <div class="col-md-7">
                        <label class="form-label form-label-rc mb-1">
                            <i class="bi bi-search me-1"></i> Cari
                        </label>
                        <input type="text" name="search" id="filterSearch" value="{{ request('search') }}"
                            class="form-control form-control-rc" placeholder="Ketik kode booking, nama warga, atau tanggal (YYYY-MM-DD)...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label form-label-rc mb-1">
                            <i class="bi bi-funnel me-1"></i> Status
                        </label>
                        <select name="status" id="filterStatus" class="form-select form-select-rc" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-rc w-100" style="padding: 0.6rem 0.5rem; font-size: 0.88rem;">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted" id="filterCount">
                        @if($bookings->total() > 0)
                            Menampilkan {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} dari {{ $bookings->total() }} data
                        @else
                            Menampilkan 0 data
                        @endif
                        @if(request('search') || request('status'))
                            <span class="badge bg-primary bg-opacity-10 text-primary ms-1">Filter Aktif</span>
                        @endif
                    </small>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.bookings.index', ['view' => 'history']) }}" class="btn btn-sm btn-outline-rc" style="font-size: 0.78rem; padding: 0.25rem 0.75rem;">
                            <i class="bi bi-x-circle me-1"></i> Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        @endif

        <!-- Bookings Table -->
        <div class="card-rc p-0 overflow-hidden">
            @if($bookings->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-{{ $view === 'history' ? 'archive' : 'inbox' }}"
                        style="font-size: 3rem; color: var(--rc-primary-pale);"></i>
                    <p class="mt-3 text-muted">
                        @if($view === 'history')
                            Belum ada riwayat setoran.
                        @else
                            Tidak ada setoran yang menunggu verifikasi.
                        @endif
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-rc mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Warga</th>
                                <th>Tanggal <i class="bi bi-arrow-{{ $view === 'history' ? 'down' : 'up' }}"
                                        style="font-size: 0.7rem;"></i></th>
                                <th>Kategori</th>
                                @if($view === 'history')
                                    <th>Status</th>
                                    <th>Poin</th>
                                @else
                                    <th>Antrian</th>
                                @endif
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td><strong>{{ $booking->booking_code }}</strong></td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>
                                        {{ $booking->scheduled_date->format('d M Y') }}
                                        @if($view !== 'history' && $booking->scheduled_date->isToday())
                                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">HARI INI</span>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($booking->wasteCategories as $cat)
                                            <span class="badge bg-light text-dark me-1"
                                                style="font-size: 0.72rem;">{{ $cat->name }}</span>
                                        @endforeach
                                    </td>
                                    @if($view === 'history')
                                        <td>
                                            <span class="badge-status badge-{{ $booking->status }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($booking->status === 'verified')
                                                <strong class="text-success">{{ number_format($booking->total_points) }}</strong>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    @else
                                        <td>
                                            <span class="badge bg-light text-dark" style="font-size: 0.78rem;">
                                                <i
                                                    class="bi bi-people-fill me-1"></i>#{{ $booking->queue_number }}/{{ $booking->total_queue }}
                                            </span>
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-rc"
                                            style="font-size: 0.78rem; padding: 0.3rem 0.7rem;">
                                            <i class="bi bi-eye"></i>
                                            @if($booking->status === 'pending')
                                                Proses
                                            @else
                                                Detail
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                @if($bookings->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            Halaman {{ $bookings->currentPage() }} dari {{ $bookings->lastPage() }}
                        </small>
                        <div class="pagination-sm m-0">
                            {{ $bookings->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .admin-tab {
                padding: 0.65rem 1.25rem;
                font-weight: 600;
                font-size: 0.9rem;
                color: var(--rc-text-muted);
                text-decoration: none;
                border-bottom: 3px solid transparent;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
            }

            .admin-tab:hover {
                color: var(--rc-primary);
            }

            .admin-tab.active {
                color: var(--rc-primary);
                border-bottom-color: var(--rc-primary);
            }

            .tab-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 20px;
                height: 20px;
                padding: 0 6px;
                border-radius: 50px;
                background: linear-gradient(135deg, #E76F51, #E63946);
                color: #fff;
                font-size: 0.7rem;
                font-weight: 700;
                margin-left: 0.35rem;
            }

            .filter-bar {
                border: 1px solid rgba(45, 106, 79, 0.1);
            }

            .filter-bar .form-control-rc,
            .filter-bar .form-select-rc {
                font-size: 0.88rem;
                padding: 0.55rem 0.85rem;
            }

            .pagination .page-link {
                color: var(--rc-primary);
                border-color: rgba(45, 106, 79, 0.15);
                font-size: 0.85rem;
                padding: 0.35rem 0.75rem;
            }

            .pagination .page-item.active .page-link {
                background-color: var(--rc-primary);
                border-color: var(--rc-primary);
                color: #fff;
            }
        </style>
    @endpush
@endsection