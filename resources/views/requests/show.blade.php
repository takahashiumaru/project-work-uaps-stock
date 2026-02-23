@extends('layout.admin')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Produk /</span> Detail Produk
</h4>

<div class="card mb-4">
    <h5 class="card-header">Detail Produk</h5>
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

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Kembali
        </a>

    </div>
</div>
@endsection