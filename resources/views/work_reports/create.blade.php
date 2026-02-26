@extends('layout.admin')

@section('title', 'Input Laporan Pekerjaan')

@section('styles')
<style>
:root {
    --accent: #5661f8;
    --muted: #6b7280;
    --card-bg: #ffffff;
    --card-border: #eef2f6;
    --radius: 12px;
    --shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}

.card-clean{border-radius:10px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);font-weight:800;color:#0f172a;background:#fff;border-top-left-radius:10px;border-top-right-radius:10px;}

/* match btn-create di requests.create */
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
}
.btn-create i{margin-right:6px;}

@media (max-width: 575.98px){
    .header-row{
        flex-direction:column;
        align-items:stretch;
    }
    .header-row .btn{
        width:100%;
        text-align:center;
    }
}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="header-row">
        <div>
            <h4>Input Laporan Pekerjaan</h4>
            <div class="subtitle">Tambahkan laporan pekerjaan baru</div>
        </div>
        <div>
            <a href="{{ route('work-reports.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-clean">
        <div class="card-header-clean">
            Formulir Laporan Pekerjaan
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-2">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('work-reports.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Pekerjaan</label>
                    <input type="text" name="title" class="form-control"
                           placeholder="Cth: Stock Opname Rak A"
                           value="{{ old('title') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Pengerjaan</label>
                    <input type="date" name="work_date" class="form-control"
                           value="{{ old('work_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi Hasil</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Jelaskan detail pekerjaan..." required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Lampiran Bukti (Max 2MB)</label>
                    <input type="file" name="attachment" class="form-control"
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    <div class="form-text text-muted small">
                        Format yang diizinkan: PDF, JPG, PNG, DOC, DOCX.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-create">
                        <i class="bi bi-save"></i> Simpan Laporan
                    </button>
                    <a href="{{ route('work-reports.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
