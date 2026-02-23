@extends('layout.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="py-4">

        {{-- Header + Breadcrumb --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Manajemen Produk</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">Inventory</li>
                    <li class="breadcrumb-item active">Produk</li>
                </ol>
            </nav>
        </div>

        {{-- Card --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 text-white">
                        <i class="bx bx-package me-2"></i>Data Produk
                    </h5>
                    <p class="mb-0 mt-1 small opacity-75">
                        Informasi seluruh produk inventory perusahaan
                    </p>
                </div>

                <a href="{{ route('products.create') }}" class="btn btn-light btn-sm">
                    <i class="bx bx-plus me-1"></i>Tambah Produk
                </a>
            </div>
            
            <div class="card-body">
            <div class="pt-3"></div>
                {{-- Search --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       placeholder="Cari nama / SKU produk..."
                                       value="{{ request('name') }}">

                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i>Cari
                                </button>

                                @if(request('name'))
                                    <a href="{{ route('products.index') }}"
                                       class="btn btn-outline-danger">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="12%">SKU</th>
                                <th width="18%">Nama Barang</th>
                                <th width="15%">Kategori</th>
                                <th width="15%">Lokasi</th>
                                <th width="10%">Stok</th>
                                <th width="10%">Min Stok</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $p)
                                <tr>
                                    <td><strong>{{ $p->sku }}</strong></td>
                                    <td>{{ $p->name }}</td>

                                    <td>
                                        <span class="badge bg-label-primary">
                                            {{ $p->category }}
                                        </span>
                                    </td>

                                    <td>{{ $p->location }}</td>

                                    <td>
                                        @if($p->stock <= $p->min_stock)
                                            <span class="badge bg-danger">
                                                {{ $p->stock }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ $p->stock }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>{{ $p->min_stock }}</td>

                                    <td class="d-flex align-items-center gap-2">

                                        {{-- View --}}
                                        <a href="{{ route('products.show', $p) }}"
                                           class="action-btn"
                                           style="background: rgb(93, 190, 222);"
                                           title="Lihat Detail">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('products.edit', $p) }}"
                                           class="action-btn"
                                           title="Edit Produk">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <button type="button"
                                                class="action-btn border-0"
                                                style="background: red;"
                                                onclick="confirmDelete({{ $p->id }}, '{{ $p->name }}')"
                                                title="Hapus Produk">
                                            <i class="bx bx-trash"></i>
                                        </button>

                                        <form id="deleteForm-{{ $p->id }}"
                                              action="{{ route('products.destroy', $p) }}"
                                              method="POST"
                                              style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Tidak ada data produk
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4">

                    <div class="text-muted small">
                        @if($products->total() > 0)
                            Menampilkan {{ $products->firstItem() }}
                            -
                            {{ $products->lastItem() }}
                            dari
                            {{ $products->total() }} data
                        @else
                            Tidak ada data
                        @endif
                    </div>

                    <div>
                        {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection


@section('styles')
<style>
.table td, .table th {
    vertical-align: middle;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 6px;
    background: #667eea;
    color: white;
    text-decoration: none;
    transition: all 0.2s ease;
}

.action-btn:hover {
    background: #5a6fd8;
    transform: translateY(-1px);
    color: white;
}

.pagination {
    margin-bottom: 0;
}
</style>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(productId, productName) {
    Swal.fire({
        title: 'Hapus Produk?',
        html: `Apakah Anda yakin ingin menghapus <strong>${productName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#8592a3',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm-' + productId).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

});
</script>
@endsection