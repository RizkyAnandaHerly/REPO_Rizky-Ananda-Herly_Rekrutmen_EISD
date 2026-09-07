@extends('layouts.app')

@section('title', 'Riwayat Setoran')

@section('content')
<div class="page-container">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h1><i class="bi bi-list-check me-2"></i>Riwayat Setoran</h1>
            <p>Daftar semua setoran sampah yang Anda ajukan</p>
        </div>
        <a href="{{ route('bookings.create') }}" class="btn btn-rc">
            <i class="bi bi-plus-circle me-1"></i> Ajukan Setoran Baru
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="stat-card stat-warning">
                <div class="stat-value">{{ $pendingCount }}</div>
                <div class="stat-label"><i class="bi bi-hourglass-split me-1"></i> Setoran Menunggu</div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($totalPoints) }}</div>
                <div class="stat-label"><i class="bi bi-star-fill me-1"></i> Total Poin Kontribusi</div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card-rc p-0 overflow-hidden">
        @if($bookings->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--rc-primary-pale);"></i>
                <p class="mt-3 text-muted">Belum ada setoran. Yuk mulai ajukan setoran pertamamu!</p>
                <a href="{{ route('bookings.create') }}" class="btn btn-rc mt-2">
                    <i class="bi bi-plus-circle me-1"></i> Ajukan Setoran
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-rc mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Antrian</th>
                            <th>Poin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td><strong>{{ $booking->booking_code }}</strong></td>
                            <td>{{ $booking->scheduled_date->format('d M Y') }}</td>
                            <td>
                                @foreach($booking->wasteCategories as $cat)
                                    <span class="badge bg-light text-dark me-1" style="font-size: 0.75rem;">{{ $cat->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-light text-dark" style="font-size: 0.78rem;">
                                        <i class="bi bi-people-fill me-1"></i>#{{ $booking->queue_number }}/{{ $booking->total_queue }}
                                    </span>
                                    <br>
                                    <small class="text-muted" style="font-size: 0.72rem;">Sudah diproses: {{ $booking->current_served }}</small>
                                @elseif($booking->status === 'verified' || $booking->status === 'rejected')
                                    <span class="text-muted" style="font-size: 0.78rem;">#{{ $booking->queue_number }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status === 'verified')
                                    <strong class="text-success">{{ number_format($booking->total_points) }}</strong>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-rc" style="font-size: 0.78rem; padding: 0.3rem 0.7rem;">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin membatalkan setoran ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger-rc" style="font-size: 0.78rem; padding: 0.3rem 0.7rem;">
                                                <i class="bi bi-x-circle"></i> Batal
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
