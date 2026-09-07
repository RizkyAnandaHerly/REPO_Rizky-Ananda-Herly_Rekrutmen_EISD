@extends('layouts.app')

@section('title', 'Semua Setoran')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1><i class="bi bi-inbox me-2"></i>Semua Setoran</h1>
        <p>Daftar seluruh setoran dari warga</p>
    </div>

    <!-- Stat Card -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="stat-card stat-warning">
                <div class="stat-value">{{ $pendingCount }}</div>
                <div class="stat-label"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card-rc p-0 overflow-hidden">
        @if($bookings->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--rc-primary-pale);"></i>
                <p class="mt-3 text-muted">Belum ada setoran masuk.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-rc mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Warga</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Poin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td><strong>{{ $booking->booking_code }}</strong></td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->scheduled_date->format('d M Y') }}</td>
                            <td>
                                @foreach($booking->wasteCategories as $cat)
                                    <span class="badge bg-light text-dark me-1" style="font-size: 0.72rem;">{{ $cat->name }}</span>
                                @endforeach
                            </td>
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
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-rc" style="font-size: 0.78rem; padding: 0.3rem 0.7rem;">
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
@endsection
