@extends('layout.admin')

@section('styles')
<style>
:root{--accent:#5661f8;--muted:#6b7280;--card-bg:#fff;--card-border:#eef2f6;--shadow:0 6px 18px rgba(16,24,40,.06);}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;}
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}
.card-clean{border-radius:10px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);font-weight:800;color:#0f172a;background:#fff;border-top-left-radius:10px;border-top-right-radius:10px;}
.btn-create{background:linear-gradient(90deg,var(--accent),#3b5afe);border:none;color:#fff;padding:8px 14px;border-radius:8px;box-shadow:0 6px 18px rgba(86,97,248,.12);font-weight:600;}
.btn-create:hover{transform:translateY(-2px);}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="header-row">
        <div>
            <h4>Tambah Produk</h4>
            <div class="subtitle">Input data produk inventory</div>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-clean mb-4">
        <div class="card-header-clean">Formulir Tambah Produk</div>
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="sku" class="form-label">Kode SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                            id="sku" name="sku" value="{{ old('sku') }}"  placeholder="Cth: ATK-001" required>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" placeholder="Cth: Kertas A4" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Lokasi Penyimpanan</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                        id="location" name="location" value="{{ old('location') }}" placeholder="Cth: Rak A-02" required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="user_id" class="form-label">Satuan (Unit)</label>
                    <select class="form-select select1 @error('unit') is-invalid @enderror" id="unit" name="unit"
                        required>
                        <option value="">-- Pilih Satuan --</option>
                        <option value="Pcs" {{ old('unit') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="Unit" {{ old('unit') == 'Unit' ? 'selected' : '' }}>Unit</option>
                        <option value="Box" {{ old('unit') == 'Box' ? 'selected' : '' }}>Box</option>
                        <option value="Rim" {{ old('unit') == 'Rim' ? 'selected' : '' }}>Rim</option>
                        <option value="Pack" {{ old('unit') == 'Pack' ? 'selected' : '' }}>Pack</option>
                        <option value="Botol" {{ old('unit') == 'Botol' ? 'selected' : '' }}>Botol</option>
                    </select>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select class="form-select select2 @error('category') is-invalid @enderror" id="category"
                        name="category" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Office Supply" {{ old('category') == 'Office Supply' ? 'selected' : '' }}>Office
                            Supply (ATK)</option>
                        <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Elektronik &
                            IT</option>
                        <option value="Pantry" {{ old('category') == 'Pantry' ? 'selected' : '' }}>Pantry & Dapur</option>
                        <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                        <option value="Others" {{ old('category') == 'others' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok Awal</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                            id="stock" name="stock" value="{{ old('stock') }}"  required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="min_stock" class="form-label">Min Stock (Alert)</label>
                        <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                            id="min_stock" name="min_stock" value="{{ old('min_stock') }}" required>
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn-create">Simpan Produk</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- CSS Select2 terbaru --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Tema Bootstrap 5 (opsional) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    {{-- JS Select2 terbaru --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#category').select2({
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5' // jika kamu pakai tema bootstrap-5
            });
            $('#unit').select2({
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5' // jika kamu pakai tema bootstrap-5
            });
        });
    </script>
@endsection
