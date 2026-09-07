@extends('layouts.app')

@section('title', 'Detail Setoran')

@section('content')
<div class="page-container" style="max-width: 800px;">
    <div class="page-header">
        <a href="{{ route('admin.bookings.index') }}" class="text-decoration-none" style="color: var(--rc-primary); font-size: 0.85rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
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
                <div class="detail-label">Nama Warga</div>
                <div class="detail-value">{{ $booking->user->name }}</div>
            </div>
            <div class="col-sm-6">
                <div class="detail-label">Email Warga</div>
                <div class="detail-value">{{ $booking->user->email }}</div>
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
                <div class="detail-label">Catatan Warga</div>
                <div class="detail-value">{{ $booking->notes }}</div>
            </div>
            @endif
        </div>

        <hr style="border-color: rgba(45,106,79,0.1);">

        <!-- Waste Categories Detail -->
        <h5 class="heading mb-3" style="color: var(--rc-accent);">
            <i class="bi bi-tags me-1"></i> Kategori Sampah
        </h5>

        @if($booking->status === 'pending')
            <!-- Verification Form -->
            <form method="POST" action="{{ route('admin.bookings.verify', $booking) }}" id="verifyForm">
                @csrf
                @method('PATCH')

                <div class="table-responsive">
                    <table class="table table-rc mb-0">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Estimasi Warga</th>
                                <th>Berat Aktual (Verifikasi)</th>
                                <th>Poin/Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->wasteCategories as $category)
                            <tr>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td>{{ $category->unit }}</td>
                                <td>{{ number_format($category->pivot->estimated_weight, 2) }} {{ $category->unit }}</td>
                                <td>
                                    <input type="number" step="0.1" min="0.1"
                                           name="weights[{{ $category->id }}]"
                                           class="form-control form-control-rc form-control-sm"
                                           style="max-width: 140px;"
                                           placeholder="0.0"
                                           value="{{ old('weights.' . $category->id, $category->pivot->estimated_weight) }}"
                                           required>
                                </td>
                                <td>{{ number_format($category->points_per_unit) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($errors->any())
                    <div class="flash-message flash-error mt-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="d-flex gap-2 mt-4 justify-content-end">
                    <button type="submit" class="btn btn-rc"
                            onclick="return confirm('Verifikasi setoran ini? Pastikan berat aktual sudah benar.')">
                        <i class="bi bi-check-circle me-1"></i> Verifikasi Setoran
                    </button>
                </div>
            </form>

            <div class="mt-2 text-end">
                <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menolak setoran ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger-rc">
                        <i class="bi bi-x-circle me-1"></i> Tolak Setoran
                    </button>
                </form>
            </div>
        @else
            <!-- Read-only view for non-pending -->
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
        @endif
    </div>
</div>
@endsection
