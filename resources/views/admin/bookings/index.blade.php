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
                        <div class="stat-value">{{ $bookings->count() }}</div>
                        <div class="stat-label"><i class="bi bi-archive me-1"></i> Total Riwayat</div>
                    </div>
                </div>
            @endif
        </div>
        <!-- Filter Bar (History tab only) -->
        @if($view === 'history' && !$bookings->isEmpty())
            <div class="filter-bar card-rc p-3 mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label form-label-rc mb-1">
                            <i class="bi bi-search me-1"></i> Cari
                        </label>
                        <input type="text" id="filterSearch" class="form-control form-control-rc" placeholder="Ketik kode booking, nama warga, atau tanggal...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-rc mb-1">
                            <i class="bi bi-funnel me-1"></i> Status
                        </label>
                        <select id="filterStatus" class="form-select form-select-rc">
                            <option value="">Semua Status</option>
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted" id="filterCount">Menampilkan {{ $bookings->count() }} data</small>
                    <button type="button" id="filterReset" class="btn btn-sm btn-outline-rc" style="font-size: 0.78rem; padding: 0.25rem 0.75rem;">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </button>
                </div>
            </div>
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

            tr.filter-hidden {
                display: none !important;
            }

            .no-results-row td {
                text-align: center;
                padding: 2rem !important;
                color: var(--rc-text-muted);
                font-size: 0.9rem;
            }
        </style>
    @endpush
@endsection

@section('scripts')
@if($view === 'history' && !$bookings->isEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('filterSearch');
        const statusSelect = document.getElementById('filterStatus');
        const resetBtn = document.getElementById('filterReset');
        const filterCount = document.getElementById('filterCount');
        const tbody = document.querySelector('.table-rc tbody');
        const rows = tbody.querySelectorAll('tr:not(.no-results-row)');
        const totalCount = rows.length;

        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const statusTerm = statusSelect.value.toLowerCase();
            let visibleCount = 0;

            // Remove existing no-results row
            const existingNoResults = tbody.querySelector('.no-results-row');
            if (existingNoResults) existingNoResults.remove();

            rows.forEach(function(row) {
                const code = (row.cells[0] ? row.cells[0].textContent : '').toLowerCase();
                const name = (row.cells[1] ? row.cells[1].textContent : '').toLowerCase();
                const date = (row.cells[2] ? row.cells[2].textContent : '').toLowerCase();
                const status = (row.cells[4] ? row.cells[4].textContent.trim() : '').toLowerCase();

                const matchesSearch = !searchTerm ||
                    code.includes(searchTerm) ||
                    name.includes(searchTerm) ||
                    date.includes(searchTerm);

                const matchesStatus = !statusTerm || status === statusTerm;

                if (matchesSearch && matchesStatus) {
                    row.classList.remove('filter-hidden');
                    visibleCount++;
                } else {
                    row.classList.add('filter-hidden');
                }
            });

            filterCount.textContent = 'Menampilkan ' + visibleCount + ' dari ' + totalCount + ' data';

            // Show "no results" message
            if (visibleCount === 0) {
                const noRow = document.createElement('tr');
                noRow.className = 'no-results-row';
                noRow.innerHTML = '<td colspan="7"><i class="bi bi-search me-1"></i> Tidak ada data yang cocok dengan filter.</td>';
                tbody.appendChild(noRow);
            }
        }

        searchInput.addEventListener('input', applyFilters);
        statusSelect.addEventListener('change', applyFilters);

        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusSelect.value = '';
            applyFilters();
            // Focus back on search
            searchInput.focus();
        });
    });
</script>
@endif
@endsection