@extends('layouts.app')

@section('title', 'Ajukan Setoran Drop-off')

@section('content')
<div class="page-container" style="max-width: 720px;">
    <div class="page-header">
        <a href="{{ route('bookings.index') }}" class="text-decoration-none" style="color: var(--rc-primary); font-size: 0.85rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
        <h1 class="mt-2"><i class="bi bi-plus-circle me-2"></i>Ajukan Setoran Drop-off</h1>
        <p>Isi formulir berikut untuk mengajukan setoran sampah terpilah</p>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="flash-message flash-error mb-3" id="validation-errors">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Data setoran tidak valid:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-rc p-4">
        <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
            @csrf

            <!-- Scheduled Date -->
            <div class="mb-4">
                <label for="scheduled_date" class="form-label form-label-rc">
                    <i class="bi bi-calendar3 me-1"></i> Tanggal Setoran
                </label>
                <input type="date" name="scheduled_date" id="scheduled_date"
                       class="form-control form-control-rc @error('scheduled_date') is-invalid @enderror"
                       value="{{ old('scheduled_date', date('Y-m-d')) }}"
                       min="{{ date('Y-m-d') }}" required>
                @error('scheduled_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Queue Preview (AJAX) -->
                <div id="queuePreview" class="mt-2 p-2 d-none" style="background: rgba(42,157,143,0.08); border-radius: 8px; border: 1px solid rgba(42,157,143,0.15); font-size: 0.85rem;">
                    <i class="bi bi-people-fill me-1" style="color: #2A9D8F;"></i>
                    <span id="queuePreviewText">Memuat info antrian...</span>
                </div>
            </div>

            <!-- Waste Categories -->
            <div class="mb-4">
                <label class="form-label form-label-rc d-block">
                    <i class="bi bi-tags me-1"></i> Kategori Sampah
                    <span class="text-muted fw-normal" style="font-size: 0.78rem;">(pilih minimal 1)</span>
                </label>

                <div class="row g-3" id="categoriesContainer">
                    @foreach($categories as $index => $category)
                        <div class="col-md-6">
                            <div class="category-check" id="cat-card-{{ $category->id }}">
                                <div class="form-check mb-2">
                                    <input class="form-check-input category-checkbox" type="checkbox"
                                           id="cat-{{ $category->id }}"
                                           data-category-id="{{ $category->id }}"
                                           data-index="{{ $index }}"
                                           {{ is_array(old('waste_categories')) && collect(old('waste_categories'))->contains('id', $category->id) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="cat-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-light text-dark" style="font-size: 0.75rem;">
                                        <i class="bi bi-rulers me-1"></i>{{ $category->unit }}
                                    </span>
                                    <span class="badge bg-light text-dark" style="font-size: 0.75rem;">
                                        <i class="bi bi-star me-1"></i>{{ number_format($category->points_per_unit) }} poin/{{ $category->unit }}
                                    </span>
                                </div>
                                @if($category->description)
                                    <small class="text-muted">{{ $category->description }}</small>
                                @endif
                                <div class="weight-input mt-2" style="display: none;" id="weight-{{ $category->id }}">
                                    <label class="form-label-rc" style="font-size: 0.78rem;">
                                        Perkiraan Berat ({{ $category->unit }})
                                    </label>
                                    <input type="number" step="0.1" min="0.1"
                                           class="form-control form-control-rc form-control-sm"
                                           placeholder="0.0"
                                           name=""
                                           id="weight-input-{{ $category->id }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label for="notes" class="form-label form-label-rc">
                    <i class="bi bi-chat-text me-1"></i> Catatan
                    <span class="text-muted fw-normal" style="font-size: 0.78rem;">(opsional)</span>
                </label>
                <textarea name="notes" id="notes" rows="3"
                          class="form-control form-control-rc"
                          placeholder="Catatan tambahan untuk petugas...">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-rc w-100" id="btnSubmit">
                <i class="bi bi-send me-1"></i> Ajukan Setoran
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.category-checkbox');
        let selectedCount = 0;

        // Rebuild form data based on checkbox state
        function updateFormFields() {
            // Remove all existing hidden inputs
            document.querySelectorAll('.dynamic-cat-input').forEach(el => el.remove());

            const form = document.getElementById('bookingForm');
            let idx = 0;

            checkboxes.forEach(function(cb) {
                const catId = cb.dataset.categoryId;
                const card = document.getElementById('cat-card-' + catId);
                const weightDiv = document.getElementById('weight-' + catId);
                const weightInput = document.getElementById('weight-input-' + catId);

                if (cb.checked) {
                    card.classList.add('selected');
                    weightDiv.style.display = 'block';
                    weightInput.required = true;

                    // Add hidden fields
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'waste_categories[' + idx + '][id]';
                    idInput.value = catId;
                    idInput.className = 'dynamic-cat-input';
                    form.appendChild(idInput);

                    // Set weight input name
                    weightInput.name = 'waste_categories[' + idx + '][estimated_weight]';

                    idx++;
                } else {
                    card.classList.remove('selected');
                    weightDiv.style.display = 'none';
                    weightInput.required = false;
                    weightInput.name = '';
                }
            });
        }

        checkboxes.forEach(function(cb) {
            cb.addEventListener('change', updateFormFields);
        });

        // Initialize on page load (for old() data)
        updateFormFields();

        // Shake animation on validation error
        @if($errors->any())
            gsap.from('#validation-errors', { x: -10, duration: 0.1, repeat: 5, yoyo: true, ease: 'power2.inOut' });
        @endif

        // ── AJAX Queue Preview ──
        const dateInput = document.getElementById('scheduled_date');
        const queuePreview = document.getElementById('queuePreview');
        const queuePreviewText = document.getElementById('queuePreviewText');

        function fetchQueueCount(date) {
            if (!date) {
                queuePreview.classList.add('d-none');
                return;
            }
            queuePreviewText.textContent = 'Memuat info antrian...';
            queuePreview.classList.remove('d-none');

            fetch('/api/queue-count?date=' + encodeURIComponent(date), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.count === 0) {
                    queuePreviewText.textContent = 'Belum ada setoran terdaftar di tanggal ini. Anda akan menjadi antrian #1!';
                    queuePreview.style.background = 'rgba(42,157,143,0.08)';
                } else if (data.count < 5) {
                    queuePreviewText.textContent = 'Sudah ada ' + data.count + ' setoran terdaftar. Antrian Anda: #' + (data.count + 1);
                    queuePreview.style.background = 'rgba(42,157,143,0.08)';
                } else {
                    queuePreviewText.textContent = 'Sudah ada ' + data.count + ' setoran terdaftar. Antrian Anda: #' + (data.count + 1) + ' — Tanggal ini cukup ramai!';
                    queuePreview.style.background = 'rgba(233,196,106,0.12)';
                }
            })
            .catch(() => {
                queuePreviewText.textContent = 'Gagal memuat info antrian.';
            });
        }

        dateInput.addEventListener('change', function() {
            fetchQueueCount(this.value);
        });

        // Fetch on page load for default date
        fetchQueueCount(dateInput.value);
    });
</script>
@endsection
