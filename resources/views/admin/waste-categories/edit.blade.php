@extends('layouts.app')

@section('title', 'Edit Kategori Sampah')

@section('content')
<div class="page-container" style="max-width: 600px;">
    <div class="page-header">
        <a href="{{ route('admin.waste-categories.index') }}" class="text-decoration-none" style="color: var(--rc-primary); font-size: 0.85rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <h1 class="mt-2"><i class="bi bi-pencil me-2"></i>Edit Kategori Sampah</h1>
    </div>

    <div class="card-rc p-4">
        <form method="POST" action="{{ route('admin.waste-categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label form-label-rc">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-control form-control-rc @error('name') is-invalid @enderror"
                       value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="unit" class="form-label form-label-rc">Satuan</label>
                <input type="text" name="unit" id="unit" class="form-control form-control-rc @error('unit') is-invalid @enderror"
                       value="{{ old('unit', $category->unit) }}" required>
                @error('unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="points_per_unit" class="form-label form-label-rc">Poin per Satuan</label>
                <input type="number" name="points_per_unit" id="points_per_unit" class="form-control form-control-rc @error('points_per_unit') is-invalid @enderror"
                       value="{{ old('points_per_unit', $category->points_per_unit) }}" min="1" required>
                @error('points_per_unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label form-label-rc">
                    Deskripsi <span class="text-muted fw-normal" style="font-size: 0.78rem;">(opsional)</span>
                </label>
                <textarea name="description" id="description" rows="3" class="form-control form-control-rc">{{ old('description', $category->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-rc w-100">
                <i class="bi bi-check-circle me-1"></i> Perbarui Kategori
            </button>
        </form>
    </div>
</div>
@endsection
