@extends('layout.admin')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Inventory /</span> Edit Product
</h4>

<div class="card mb-4">
    <h5 class="card-header">Formulir Edit Product</h5>
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

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>

        </form>
    </div>
</div>
@endsection