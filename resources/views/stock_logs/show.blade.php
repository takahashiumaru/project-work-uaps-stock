@extends('layout.admin')

@section('title', 'Detail Stock Log')

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
    flex-wrap:wrap;
}
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}
.card-clean{
    border-radius:12px;
    border:1px solid var(--card-border);
    background:var(--card-bg);
    box-shadow:var(--shadow);
}
.card-header-clean{
    padding:14px 16px;
    border-bottom:1px solid var(--card-border);
    background:#fff;
    border-top-left-radius:12px;
    border-top-right-radius:12px;
}
.badge-soft{
    font-weight:700;
    border-radius:999px;
    padding:4px 10px;
    display:inline-flex;
    align-items:center;
    gap:4px;
    font-size:.8rem;
}
.badge-in{background:rgba(16,185,129,.12);color:#059669;border:1px solid rgba(16,185,129,.18);}
.badge-out{background:rgba(239,68,68,.12);color:#dc2626;border:1px solid rgba(239,68,68,.18);}
.badge-adj{background:rgba(245,158,11,.14);color:#b45309;border:1px solid rgba(245,158,11,.22);}

.label-muted{
    font-size:.78rem;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:.04em;
    margin-bottom:3px;
}
.value-lg{font-weight:700;font-size:1.05rem;}
.section-title{
    font-weight:700;
    font-size:.9rem;
    color:#111827;
    margin-bottom:4px;
}
.meta-pill{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:4px 10px;
    border-radius:999px;
    background:#f3f4ff;
    color:#4b5563;
    font-size:.8rem;
}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="header-row">
        <div>
            <h4>Detail Stock Log</h4>
            <div class="subtitle">Ringkasan transaksi keluar/masuk/penyesuaian stok</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('stock-logs.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card-clean">
        <div class="card-header-clean d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold" style="color:#0f172a;">
                    <i class="bx bx-history me-2"></i>Log #{{ $log->id }}
                </div>
                <div class="mt-1">
                    @if($log->type === 'In')
                        <span class="badge-soft badge-in">
                            <i class="bi bi-arrow-down-left"></i><span>Masuk</span>
                        </span>
                    @elseif($log->type === 'Out')
                        <span class="badge-soft badge-out">
                            <i class="bi bi-arrow-up-right"></i><span>Keluar</span>
                        </span>
                    @else
                        <span class="badge-soft badge-adj">
                            <i class="bi bi-sliders"></i><span>Adjustment</span>
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-end small text-muted">
                <div>Dibuat pada</div>
                <div class="fw-semibold">
                    {{ optional($log->created_at)->format('d M Y, H:i') ?? '-' }}
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row gy-4">
                {{-- Kolom kiri: info produk --}}
                <div class="col-md-6">
                    <div class="section-title">Informasi Barang</div>
                    <div class="border rounded-3 p-3 mb-3">
                        <div class="label-muted">Nama Barang</div>
                        <div class="value-lg">{{ $log->product->name ?? '-' }}</div>
                        <div class="text-muted small">
                            SKU: {{ $log->product->sku ?? '-' }} • Unit: {{ $log->product->unit ?? '-' }}
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <div class="label-muted">Kategori</div>
                            <div>{{ $log->product->category ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="label-muted">Lokasi</div>
                            <div>{{ $log->product->location ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan: info transaksi & stok --}}
                <div class="col-md-6">
                    <div class="section-title">Detail Transaksi</div>
                    <div class="border rounded-3 p-3 mb-3">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="label-muted">Jenis Transaksi</div>
                                <div>
                                    @if($log->type === 'In')
                                        <span class="badge-soft badge-in">
                                            <i class="bi bi-arrow-down-left"></i><span>Barang Masuk</span>
                                        </span>
                                    @elseif($log->type === 'Out')
                                        <span class="badge-soft badge-out">
                                            <i class="bi bi-arrow-up-right"></i><span>Barang Keluar</span>
                                        </span>
                                    @else
                                        <span class="badge-soft badge-adj">
                                            <i class="bi bi-sliders"></i><span>Set Stok</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="label-muted">Jumlah</div>
                                <div class="value-lg">
                                    {{ $log->qty }} {{ $log->product->unit ?? '' }}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="label-muted">Stok Sebelum</div>
                                <div>{{ $log->old_stock ?? '—' }} {{ $log->product->unit ?? '' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="label-muted">Stok Sesudah</div>
                                <div>{{ $log->new_stock ?? '—' }} {{ $log->product->unit ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="label-muted">Dicatat oleh</div>
                    <div class="meta-pill">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ $log->user ?? '-' }}</span>
                    </div>
                </div>

                <div class="col-12"><hr></div>

                {{-- Keterangan --}}
                <div class="col-12">
                    <div class="section-title">Keterangan</div>
                    <div class="border rounded-3 p-3 bg-light-subtle">
                        <p class="mb-0 text-muted">
                            {{ $log->note ?: 'Tidak ada keterangan tambahan.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
