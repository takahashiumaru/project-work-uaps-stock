@extends('layout.admin')

@section('title', 'Catat Transaksi Stok')

@section('styles')
<style>
:root{--accent:#5661f8;--muted:#6b7280;--card-bg:#fff;--card-border:#eef2f6;--shadow:0 6px 18px rgba(16,24,40,.06);}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}
.card-clean{border-radius:10px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);font-weight:800;color:#0f172a;background:#fff;border-top-left-radius:10px;border-top-right-radius:10px;}

/* SAMAKAN dengan btn-create di products.index */
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
    color:#fff;
}

/* label spacing only */
.form-section-title{
    font-weight:700;
    margin-bottom:6px;
}

/* Pastikan wrapper kanan header responsif juga */
@media (max-width: 575.98px){
    .header-row{
        flex-direction:column;
        align-items:stretch;
    }
    .header-row .btn-create,
    .header-row .btn.btn-outline-secondary{
        width:100%;
        white-space:normal;
        text-align:center;
    }
}
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="header-row">
        <div>
            <h4>Catat Transaksi Stok</h4>
            <div class="subtitle">Input keluar / masuk / adjustment stok produk</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stock-logs.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-clean mb-4">
        <div class="card-header-clean d-flex justify-content-between align-items-center">
            <span>Formulir Transaksi Stok</span>
            <nav aria-label="breadcrumb" class="mb-0">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item">Inventory</li>
                    <li class="breadcrumb-item"><a href="{{ route('stock-logs.index') }}">Stock Logs</a></li>
                    <li class="breadcrumb-item active">Catat Transaksi</li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('stock-logs.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label form-section-title fw-semibold">Jenis Transaksi</label>
                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="Out" {{ old('type')==='Out'?'selected':'' }}>Barang Keluar (Dipakai)</option>
                        <option value="In" {{ old('type')==='In'?'selected':'' }}>Barang Masuk (Terima)</option>
                        <option value="Adjustment" {{ old('type')==='Adjustment'?'selected':'' }}>Adjustment (Set Stok)</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label form-section-title fw-semibold">Pilih Barang</label>
                    <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ (string)old('product_id')===(string)$p->id ? 'selected' : '' }}>
                                {{ $p->sku }} — {{ $p->name }} (Sisa: {{ $p->stock }} {{ $p->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label form-section-title fw-semibold">Jumlah</label>
                        <input type="number"
                               class="form-control @error('qty') is-invalid @enderror"
                               name="qty" min="1" value="{{ old('qty', 1) }}" required>
                        <small class="text-muted">Untuk Adjustment, nilai ini akan menjadi stok baru.</small>
                        @error('qty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label form-section-title fw-semibold">Keterangan</label>
                        <textarea name="note"
                                  class="form-control @error('note') is-invalid @enderror"
                                  rows="2"
                                  placeholder="Contoh: Dipakai divisi Marketing...">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-create">
                    <i class="bx bx-save me-1"></i>Simpan Transaksi
                </button>
                <a href="{{ route('stock-logs.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#type').select2({
            allowClear: false,
            width: '100%',
            theme: 'bootstrap-5'
        });
        $('#product_id').select2({
            allowClear: true,
            width: '100%',
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Barang --'
        });
    });
</script>
@endsection
