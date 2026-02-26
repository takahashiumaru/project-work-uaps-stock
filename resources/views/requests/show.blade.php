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

@section('title', 'Detail Request')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="header-row">
        <div>
            <h4>Detail Request</h4>
            <div class="subtitle">Informasi permintaan pengadaan</div>
        </div>
        <div>
            <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-clean mb-4">
        <div class="card-header-clean">Detail Request</div>
        <div class="card-body">

            <div class="row gy-3">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Request ID</label>
                    <input type="text" class="form-control" value="{{ $request->id }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Request</label>
                    <input type="text" class="form-control" value="{{ $request->request_date ? $request->request_date->format('d M Y H:i') : '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <input type="text" class="form-control" value="{{ $request->status }}" readonly>
                </div>

                <div class="col-12"><hr></div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">SKU</label>
                    <input type="text" class="form-control" value="{{ $request->product->sku ?? '-' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Produk</label>
                    <input type="text" class="form-control" value="{{ $request->product->name ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori</label>
                    <input type="text" class="form-control" value="{{ $request->product->category ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Lokasi</label>
                    <input type="text" class="form-control" value="{{ $request->product->location ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Satuan</label>
                    <input type="text" class="form-control" value="{{ $request->product->unit ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stok Saat Ini</label>
                    <input type="text" class="form-control" value="{{ $request->product->stock ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jumlah Diminta</label>
                    <input type="text" class="form-control" value="{{ $request->qty_requested }} {{ $request->product->unit ?? '' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Min Stok (Alert)</label>
                    <input type="text" class="form-control" value="{{ $request->product->min_stock ?? '-' }}" readonly>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan Request</label>
                    <textarea class="form-control" rows="3" readonly>{{ $request->note ?? '-' }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Catatan Respon (HO)</label>
                    <textarea class="form-control" rows="3" readonly>{{ $request->response_note ?? '-' }}</textarea>
                </div>

            </div>

            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('requests.index') }}" class="btn btn-secondary">Kembali</a>
                @if($request->status == 'Pending')
                    <a href="{{ route('requests.edit', $request) }}" class="btn-create">Approval (HO)</a>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection