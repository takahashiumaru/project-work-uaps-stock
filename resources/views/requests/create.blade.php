@extends('layout.admin')

@section('title', 'Buat Request')

@section('styles')
<style>
:root {
    --accent: #5661f8;
    --muted: #6b7280;
    --card-bg: #ffffff;
    --card-border: #eef2f6;
    --radius: 12px;
    --shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}
.card-clean{border-radius:10px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);font-weight:800;color:#0f172a;background:#fff;border-top-left-radius:10px;border-top-right-radius:10px;}

/* MATCH products.index style */
.btn-create{
    background:linear-gradient(90deg,var(--accent),#3b5afe);
    border:none;
    color:#fff;
    padding:8px 14px;
    border-radius:10px;
    box-shadow:0 6px 18px rgba(86,97,248,.12);
    font-weight:700;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    white-space:nowrap;
    max-width:100%;
    min-width:0;
}
.btn-create:hover{
    transform:translateY(-2px);
}

/* Responsif untuk tombol di header jika perlu */
@media (max-width: 575.98px){
    .header-row{
        flex-direction:column;
        align-items:stretch;
    }
    .header-row .btn.btn-outline-secondary{
        width:100%;
        text-align:center;
    }
}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="header-row">
        <div>
            <h4>Buat Request</h4>
            <div class="subtitle">Buat permintaan pengadaan barang ke HO</div>
        </div>
        <div>
            <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-clean">
        <div class="card-header-clean d-flex justify-content-between align-items-center">
            <span>Formulir Request</span>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('requests.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Barang</label>
                    <select name="product_id" class="form-select" id="product_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->sku }} - {{ $p->name }} (Sisa: {{ $p->stock }} {{ $p->unit }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Cari berdasarkan SKU / nama barang.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah Diminta</label>
                    <input type="number" name="qty_requested" class="form-control"
                        value="{{ old('qty_requested', 1) }}" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alasan / Catatan</label>
                    <textarea name="note" class="form-control" rows="3" required>{{ old('note') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-create">Kirim Request</button>
                    <a href="{{ route('requests.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#product_id').select2({ allowClear: true, width: '100%', theme: 'bootstrap-5' });

    function getSwalBase(isDark, confirmButtonColorFallback) {
        const root = getComputedStyle(document.documentElement);
        const cardBgVar = root.getPropertyValue('--card-bg')?.trim();
        const textVar = root.getPropertyValue('--text')?.trim();
        const cardBg = cardBgVar && cardBgVar !== 'inherit' ? cardBgVar : (isDark ? '#0b1220' : '#ffffff');
        const textColor = textVar && textVar !== 'inherit' ? textVar : (isDark ? '#e6eef8' : '#000000');

        return {
            background: cardBg,
            color: textColor,
            confirmButtonColor: confirmButtonColorFallback,
            // ensure icon contrast on both light and dark themes
            iconColor: textColor,
            allowOutsideClick: true
        };
    }

    @if(session('success'))
        (function(){
            const isDark = document.documentElement.classList.contains('dark')
                || document.body.classList.contains('dark')
                || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
            Swal.fire(Object.assign({}, getSwalBase(isDark, '#5661f8'), { icon: 'success', iconColor: '#10b981', title: 'Berhasil', text: '{{ session('success') }}', timer: 2500, showConfirmButton: false, timerProgressBar: true }));
        })();
    @endif
});
</script>
@endsection
