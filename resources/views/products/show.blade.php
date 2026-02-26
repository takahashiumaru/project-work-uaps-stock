@extends('layout.admin')

@section('styles')
<style>
:root{--accent:#5661f8;--muted:#6b7280;--card-bg:#fff;--card-border:#eef2f6;--shadow:0 6px 18px rgba(16,24,40,.06);}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;}
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}
.card-clean{border-radius:10px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);font-weight:800;color:#0f172a;background:#fff;border-top-left-radius:10px;border-top-right-radius:10px;}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="header-row">
        <div>
            <h4>Detail Produk</h4>
            <div class="subtitle">Informasi lengkap produk</div>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-clean mb-4">
        <div class="card-header-clean">Detail Produk</div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Kode SKU</label>
                    <input type="text" class="form-control" 
                        value="{{ $product->sku }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Barang</label>
                    <input type="text" class="form-control" 
                        value="{{ $product->name }}" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Lokasi Penyimpanan</label>
                <input type="text" class="form-control" 
                    value="{{ $product->location }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Satuan (Unit)</label>
                <input type="text" class="form-control" 
                    value="{{ $product->unit }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori</label>
                <input type="text" class="form-control" 
                    value="{{ $product->category }}" readonly>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Stok</label>
                    <input type="number" class="form-control" 
                        value="{{ $product->stock }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Min Stock (Alert)</label>
                    <input type="number" class="form-control" 
                        value="{{ $product->min_stock }}" readonly>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection