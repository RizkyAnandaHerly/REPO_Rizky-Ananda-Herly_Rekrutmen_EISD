@extends('layouts.app')

@section('title', 'Detail Setoran')

@section('content')
<div class="page-container" style="max-width: 720px;">
    <div class="page-header">
        <a href="{{ route('bookings.index') }}" class="text-decoration-none" style="color: var(--rc-primary); font-size: 0.85rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
        <h1 class="mt-2"><i class="bi bi-receipt me-2"></i>Detail Setoran</h1>
    </div>

    <div class="card-rc p-4 mb-4">
        <!-- Booking Info -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6">
                <div class="detail-label">Kode Booking</div>
                <div class="detail-value"><strong>{{ $booking->booking_code }}</strong></div>
            </div>
            <div class="col-sm-6">
                <div class="detail-label">Status</div>
                <div>
                    <span class="badge-status badge-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="detail-label">Tanggal Setoran</div>
                <div class="detail-value">{{ $booking->scheduled_date->format('d F Y') }}</div>
            </div>
            <div class="col-sm-6">
                <div class="detail-label">Tanggal Pengajuan</div>
                <div class="detail-value">{{ $booking->created_at->format('d F Y, H:i') }}</div>
            </div>
            @if($booking->notes)
            <div class="col-12">
                <div class="detail-label">Catatan</div>
                <div class="detail-value">{{ $booking->notes }}</div>
            </div>
            @endif
        </div>

        <hr style="border-color: rgba(45,106,79,0.1);">

        <!-- Waste Categories -->
        <h5 class="heading mb-3" style="color: var(--rc-accent);">
            <i class="bi bi-tags me-1"></i> Kategori Sampah
        </h5>
        <div class="table-responsive">
            <table class="table table-rc mb-0">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Estimasi Berat</th>
                        <th>Berat Terverifikasi</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->wasteCategories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ $category->unit }}</td>
                        <td>{{ number_format($category->pivot->estimated_weight, 2) }} {{ $category->unit }}</td>
                        <td>
                            @if($category->pivot->verified_weight !== null)
                                <strong class="text-success">{{ number_format($category->pivot->verified_weight, 2) }} {{ $category->unit }}</strong>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($category->pivot->verified_weight !== null)
                                <strong>{{ number_format($category->pivot->verified_weight * $category->points_per_unit) }}</strong>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @if($booking->status === 'verified')
                <tfoot>
                    <tr style="background: var(--rc-primary-bg);">
                        <td colspan="4" class="text-end"><strong style="color: var(--rc-accent);">Total Poin:</strong></td>
                        <td><strong style="color: var(--rc-primary); font-size: 1.1rem;">{{ number_format($booking->total_points) }}</strong></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- Cancel Button (only for pending) -->
        @if($booking->status === 'pending')
            <div class="mt-4 text-end">
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin membatalkan setoran ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger-rc">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Setoran
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
