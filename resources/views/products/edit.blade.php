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
            <h4>Edit Produk</h4>
            <div class="subtitle">Perbarui informasi produk</div>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-clean mb-4">
        <div class="card-header-clean">Formulir Edit Produk</div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="sku" class="form-label">Kode SKU</label>
                        <input type="text"
                            class="form-control @error('sku') is-invalid @enderror"
                            id="sku"
                            name="sku"
                            value="{{ old('sku', $product->sku) }}"
                            placeholder="Cth: ATK-001"
                            required>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Barang</label>
                        <input type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name', $product->name) }}"
                            placeholder="Cth: Kertas A4"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Lokasi Penyimpanan</label>
                    <input type="text"
                        class="form-control @error('location') is-invalid @enderror"
                        id="location"
                        name="location"
                        value="{{ old('location', $product->location) }}"
                        placeholder="Cth: Rak A-02"
                        required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="unit" class="form-label">Satuan (Unit)</label>
                    <select class="form-select select1 @error('unit') is-invalid @enderror"
                        id="unit"
                        name="unit"
                        required>
                        <option value="">-- Pilih Satuan --</option>
                        @foreach (['Pcs','Unit','Box','Rim','Pack','Botol'] as $u)
                            <option value="{{ $u }}"
                                {{ old('unit', $product->unit) == $u ? 'selected' : '' }}>
                                {{ $u }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select class="form-select select2 @error('category') is-invalid @enderror"
                        id="category"
                        name="category"
                        required>
                        <option value="">-- Pilih Kategori --</option>

                        <option value="Office Supply"
                            {{ old('category', $product->category) == 'Office Supply' ? 'selected' : '' }}>
                            Office Supply (ATK)
                        </option>

                        <option value="Electronics"
                            {{ old('category', $product->category) == 'Electronics' ? 'selected' : '' }}>
                            Elektronik & IT
                        </option>

                        <option value="Pantry"
                            {{ old('category', $product->category) == 'Pantry' ? 'selected' : '' }}>
                            Pantry & Dapur
                        </option>

                        <option value="Furniture"
                            {{ old('category', $product->category) == 'Furniture' ? 'selected' : '' }}>
                            Furniture
                        </option>

                        <option value="Others"
                            {{ old('category', $product->category) == 'Others' ? 'selected' : '' }}>
                            Lainnya
                        </option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number"
                            class="form-control @error('stock') is-invalid @enderror"
                            id="stock"
                            name="stock"
                            value="{{ old('stock', $product->stock) }}"
                            required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="min_stock" class="form-label">Min Stock (Alert)</label>
                        <input type="number"
                            class="form-control @error('min_stock') is-invalid @enderror"
                            id="min_stock"
                            name="min_stock"
                            value="{{ old('min_stock', $product->min_stock) }}"
                            required>
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-create">Update Produk</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>

            </form>
        </div>
    </div>
</div>
@endsection