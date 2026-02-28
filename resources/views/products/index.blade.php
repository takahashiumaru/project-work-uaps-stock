@extends('layout.admin')

@section('title', 'Manajemen Produk')

@section('styles')
<style>
:root{
    --accent:#5661f8;
    --muted:#6b7280;
    --card-bg:#fff;
    --card-border:#eef2f6;
    --shadow:0 6px 18px rgba(16,24,40,.06);
}
.header-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:16px;
    flex-wrap: wrap;
}
.header-row > div{ min-width: 0; }
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}
.card-clean{border-radius:12px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);background:#fff;border-top-left-radius:12px;border-top-right-radius:12px;}
.btn-create{
    background:linear-gradient(90deg,var(--accent),#3b5afe);border:none;color:#fff;padding:8px 14px;border-radius:10px;box-shadow:0 6px 18px rgba(86,97,248,.12);font-weight:700;
    display:inline-flex;align-items:center;justify-content:center;white-space: nowrap;
    max-width: 100%;min-width: 0;
}
.btn-create:hover{transform:translateY(-2px);}
.table thead th{background:#fbfcff;font-weight:800;color:#111827;border-bottom:1px solid #eef2f6;}
.table td{vertical-align:middle;}
.badge-soft{background:rgba(86,97,248,.12); color:#3b5afe; border:1px solid rgba(86,97,248,.18); font-weight:700;}
.stock-pill{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;font-weight:800;}
.stock-ok{background:rgba(16,185,129,.12); color:#059669; border:1px solid rgba(16,185,129,.18);}
.stock-low{background:rgba(239,68,68,.12); color:#dc2626; border:1px solid rgba(239,68,68,.18);}
.mini-progress{height:6px;border-radius:999px;background:#eef2f6;overflow:hidden;min-width:70px;}
.mini-progress > div{height:6px;border-radius:999px;}

.action-btn{
    display:inline-flex;align-items:center;justify-content:center;
    width:36px;height:36px;border-radius:10px;
    background:#667eea;color:#fff;text-decoration:none;
    transition:all .15s ease; box-shadow:0 6px 14px rgba(102,126,234,.14);
}
.action-btn:hover{transform:translateY(-1px);filter:brightness(.98);color:#fff;}
.action-btn.view{background:linear-gradient(135deg,#5dbede,#2f9fbf);}
.action-btn.edit{background:linear-gradient(135deg,var(--accent),#3b5afe);}
.action-btn.del{background:linear-gradient(135deg,#ef4444,#f97373);}

.input-group .btn{font-weight:700;}

.header-actions{
    flex: 0 0 auto;
    max-width: 100%;
    display:flex;
    align-items:center;
    gap:10px;
}

/* Search kecil di header atas */
.header-search-form{
    max-width:260px;
    width:100%;
}
.header-search-form .form-control{
    height:36px;
    font-size:.9rem;
    padding:6px 10px;
}
.header-search-form .btn{
    height:36px;
    font-size:.85rem;
    padding:6px 10px;
}

/* Search di area card-header (ganti breadcrumb) */
.card-search-form{
    max-width:260px;
    width:100%;
}
.card-search-form .form-control{
    height:38px;
    font-size:.9rem;
    padding:6px 10px;
    border-radius:8px 0 0 8px;
    border-color: var(--card-border);
    background-color: var(--input-bg);
    color: var(--text);
}
.card-search-form .form-control:focus{
    border-color: var(--accent);
    box-shadow: 0 0 0 1px rgba(86,97,248,.25);
}
.card-search-form .btn{
    height:38px;
    font-size:.85rem;
    padding:6px 12px;
    border-radius:0 8px 8px 0;
}

/* Mobile layout: title on top, controls full width */
@media (max-width: 575.98px){
    .header-row{
        flex-direction: column;
        align-items: stretch;
    }
    .header-row h4{
        text-align:left;
    }
    .header-actions{
        flex-direction: column;
        align-items: stretch;
    }
    .header-actions .btn-create,
    .header-search-form{
        width: 100%;
    }

    .card-header-clean{
        flex-direction: column;
        align-items: flex-start !important;
        gap:8px;
    }
    .card-search-form{
        width:100%;
    }
}
</style>

{{-- Select2 CSS (samakan dengan stock_logs & create product) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="py-2">

        {{-- HEADER: Home / Inventory / Produk + Button (search dipindah ke card-header) --}}
        <div class="header-row">
            <div>
                <h4>Manajemen Produk</h4>
                <div class="subtitle">Informasi seluruh produk inventory perusahaan</div>
            </div>
            <div class="header-actions">
                <a href="{{ route('products.create') }}" class="btn-create">
                    <i class="bx bx-plus me-1"></i>Tambah Produk
                </a>
            </div>
        </div>

        <div class="card-clean">
            <div class="card-header-clean d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold" style="color:#0f172a;">
                        <i class="bx bx-package me-2"></i>Data Produk
                    </div>
                    <small class="text-muted">Kelola SKU, stok, kategori dan lokasi.</small>
                </div>

                {{-- SEARCH dipindah ke sini, breadcrumb dihapus --}}
                <form action="{{ route('products.index') }}" method="GET" class="card-search-form">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control"
                               placeholder="Cari nama / SKU..." value="{{ request('name') }}">
                               <button type="submit" class="btn" style="background-color: #5061fa; color: #fff">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="12%">SKU</th>
                                <th width="22%">Nama Barang</th>
                                <th width="16%">Kategori</th>
                                <th width="14%">Lokasi</th>
                                <th width="16%">Stok</th>
                                <th width="10%">Min</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $p)
                                @php
                                    $isLow = ($p->stock <= $p->min_stock);
                                    $percent = ($p->min_stock ?? 0) > 0 ? min(100, round(($p->stock / $p->min_stock) * 100)) : 0;
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $p->sku }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $p->name }}</div>
                                        <div class="text-muted small">{{ $p->unit }}</div>
                                    </td>
                                    <td><span class="badge badge-soft">{{ $p->category }}</span></td>
                                    <td><span class="text-muted">{{ $p->location }}</span></td>

                                    <td>
                                        <span class="stock-pill {{ $isLow ? 'stock-low' : 'stock-ok' }}">
                                            {{ $p->stock }}
                                            <span class="text-muted fw-semibold">{{ $p->unit }}</span>
                                        </span>
                                        <div class="mini-progress mt-2" title="Perbandingan terhadap min stock">
                                            <div style="width: {{ $percent }}%; background: {{ $isLow ? '#ef4444' : '#10b981' }};"></div>
                                        </div>
                                    </td>

                                    <td class="fw-semibold">{{ $p->min_stock }}</td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('products.show', $p) }}" class="action-btn view" title="Lihat Detail">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $p) }}" class="action-btn edit" title="Edit Produk">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="action-btn del border-0"
                                                    onclick="confirmDelete({{ $p->id }}, '{{ addslashes($p->name) }}')"
                                                    title="Hapus Produk">
                                                <i class="bx bx-trash"></i>
                                            </button>

                                            <form id="deleteForm-{{ $p->id }}" action="{{ route('products.destroy', $p) }}"
                                                  method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data produk</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        @if($products->total() > 0)
                            Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} data
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* updated: use CSS variables (or fallbacks) so Swal matches page card in light/dark */
function getSwalBase(isDark, confirmButtonColorFallback) {
    const root = getComputedStyle(document.documentElement);
    const cardBgVar = root.getPropertyValue('--card-bg')?.trim();
    const textVar = root.getPropertyValue('--text')?.trim();
    const cardBg = cardBgVar && cardBgVar !== 'inherit' ? cardBgVar : (isDark ? '#0b1220' : '#ffffff');
    const textColor = textVar && textVar !== 'inherit' ? textVar : (isDark ? '#e6eef8' : '#000000');

    // Pastikan iconColor selalu di-set agar ikon SweetAlert terlihat di light & dark mode.
    return {
        background: cardBg,
        color: textColor,
        confirmButtonColor: confirmButtonColorFallback,
        iconColor: textColor,
        allowOutsideClick: true
    };
}

/* NEW: map icon types to distinguishable colors */
function getIconColor(type, isDark) {
    // warna terang yang kontras pada light/dark backgrounds
    const map = {
        success: '#10b981', // green
        error:   '#ef4444', // red
        warning: '#f59e0b', // amber
        info:    '#3b82f6', // blue
        question:'#6366f1'  // indigo
    };
    return map[type] || (isDark ? '#e6eef8' : '#000000');
}

function confirmDelete(productId, productName) {
    const isDark = document.documentElement.classList.contains('dark')
        || document.body.classList.contains('dark')
        || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);

    const base = getSwalBase(isDark, '#d33');
    const cancelBtnColor = isDark ? '#6b7280' : '#8592a3';

    Swal.fire(Object.assign({}, base, {
        title: 'Hapus Produk?',
        html: `Apakah Anda yakin ingin menghapus <strong>${productName}</strong>?`,
        icon: 'warning',
        iconColor: getIconColor('warning', isDark),
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        cancelButtonColor: cancelBtnColor
    })).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm-' + productId).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    @if (session('success'))
        (function(){
            const isDark = document.documentElement.classList.contains('dark')
                || document.body.classList.contains('dark')
                || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);

            const base = getSwalBase(isDark, '#5661f8');
            Swal.fire(Object.assign({}, base, {
                icon: 'success',
                iconColor: getIconColor('success', isDark),
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true
            }));
        })();
    @endif
});
</script>

{{-- Select2 JS (kalau belum ada, tambahkan; kalau sudah, jangan duplikasi) --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(function () {
    $('#filter_category').select2({
      allowClear: true,
      width: '210px',
      theme: 'bootstrap-5',
      placeholder: 'Semua Kategori'
    });
  });
</script>
@endsection