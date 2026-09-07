@extends('layouts.app')

@section('title', 'Kategori Sampah')

@section('content')
<div class="page-container">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h1><i class="bi bi-tags me-2"></i>Kategori Sampah</h1>
            <p>Kelola kategori sampah yang tersedia</p>
        </div>
        <a href="{{ route('admin.waste-categories.create') }}" class="btn btn-rc">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    <div class="card-rc p-0 overflow-hidden">
        @if($categories->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-tags" style="font-size: 3rem; color: var(--rc-primary-pale);"></i>
                <p class="mt-3 text-muted">Belum ada kategori sampah.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-rc mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Poin/Satuan</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $category->unit }}</span>
                            </td>
                            <td><strong>{{ number_format($category->points_per_unit) }}</strong></td>
                            <td>
                                @if($category->description)
                                    <span class="text-muted" style="font-size: 0.85rem;">{{ Str::limit($category->description, 50) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.waste-categories.edit', $category) }}" class="btn btn-sm btn-outline-rc" style="font-size: 0.78rem; padding: 0.3rem 0.7rem;">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.waste-categories.destroy', $category) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger-rc" style="font-size: 0.78rem; padding: 0.3rem 0.7rem;">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
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
