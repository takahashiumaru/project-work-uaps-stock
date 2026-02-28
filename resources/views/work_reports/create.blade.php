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
    --input-bg: #ffffff;
    --input-border: #e6e9ef;
    --input-color: #0f172a;
    --input-btn-bg: #f3f4f6;
    --input-btn-border: #e6e9ef;
    --input-btn-color: #0f172a;
    --input-focus-ring: rgba(59,92,254,0.12);
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

/* ===========================
   File input: unified theme-aware styles
   Replace previous file-input rules with these
   =========================== */

/* ensure variables exist (these are already set earlier in this file; kept for clarity) */
:root {
    --input-bg: #ffffff;
    --input-border: #e6e9ef;
    --input-color: #0f172a;
    --input-btn-bg: #f3f4f6;
    --input-btn-border: #e6e9ef;
    --input-btn-color: #0f172a;
    --input-focus-ring: rgba(59,92,254,0.12);
}

/* Dark-mode overrides */
@media (prefers-color-scheme: dark) {
    :root {
        --input-bg: #0b1220;
        --input-border: #273143;
        --input-color: #e6eef8;
        --input-btn-bg: #0f172a;
        --input-btn-border: #273143;
        --input-btn-color: #e6eef8;
        --input-focus-ring: rgba(99,102,241,0.06);
    }
}
.dark {
    --input-bg: #0b1220;
    --input-border: #273143;
    --input-color: #e6eef8;
    --input-btn-bg: #0f172a;
    --input-btn-border: #273143;
    --input-btn-color: #e6eef8;
    --input-focus-ring: rgba(99,102,241,0.06);
}

/* Base area (filename + button) — scoped to override common frameworks */
.card-body input[type="file"].form-control,
.card-body input[type="file"] {
    background: var(--input-bg) !important;
    border: 1px solid var(--input-border) !important;
    color: var(--input-color) !important;
    padding: 0.35rem 0.5rem !important;
    border-radius: 0.375rem !important;
    font: inherit;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

/* Neutralize native appearance so our styles persist when clicked */
.card-body input[type="file"]::file-selector-button,
.card-body input[type="file"]::-webkit-file-upload-button {
    -webkit-appearance: none;
    appearance: none;
}

/* Choose-file button */
.card-body input[type="file"]::file-selector-button,
.card-body input[type="file"]::-webkit-file-upload-button {
    background: var(--input-btn-bg) !important;
    border: 1px solid var(--input-btn-border) !important;
    color: var(--input-btn-color) !important;
    padding: 0.35rem 0.7rem;
    margin-right: 0.5rem;
    border-radius: 0.375rem;
    cursor: pointer;
    font-weight: 600;
    transition: background .12s ease, border-color .12s ease, box-shadow .12s ease, color .12s ease;
}

/* Light-mode hover/focus/active (explicit) */
.card-body input[type="file"]::file-selector-button:hover,
.card-body input[type="file"]::-webkit-file-upload-button:hover,
.card-body input[type="file"]::file-selector-button:focus,
.card-body input[type="file"]::-webkit-file-upload-button:focus,
.card-body input[type="file"]::file-selector-button:active,
.card-body input[type="file"]::-webkit-file-upload-button:active,
.card-body input[type="file"]::file-selector-button:focus-visible,
.card-body input[type="file"]::-webkit-file-upload-button:focus-visible {
    background: #eef2ff !important; /* explicit, readable light hover */
    border-color: var(--input-btn-border) !important;
    color: var(--input-btn-color) !important;
    outline: none;
    box-shadow: 0 0 0 4px var(--input-focus-ring);
}

/* Dark-mode hover/focus/active: override to a darker shade so it doesn't invert */
@media (prefers-color-scheme: dark) {
    .card-body input[type="file"]::file-selector-button:hover,
    .card-body input[type="file"]::-webkit-file-upload-button:hover,
    .card-body input[type="file"]::file-selector-button:focus,
    .card-body input[type="file"]::-webkit-file-upload-button:focus,
    .card-body input[type="file"]::file-selector-button:active,
    .card-body input[type="file"]::-webkit-file-upload-button:active,
    .card-body input[type="file"]::file-selector-button:focus-visible,
    .card-body input[type="file"]::-webkit-file-upload-button:focus-visible {
        background: #0b1220 !important;
        border-color: #334155 !important;
        color: var(--input-btn-color) !important;
        box-shadow: 0 0 0 4px var(--input-focus-ring);
    }
}

/* fallback for .dark parent */
.dark .card-body input[type="file"]::file-selector-button:hover,
.dark .card-body input[type="file"]::-webkit-file-upload-button:hover {
    background: #0b1220 !important;
    border-color: #334155 !important;
    color: var(--input-btn-color) !important;
}

/* Ensure filename/selected-file text remains visible across browsers */
.card-body input[type="file"]::-webkit-file-upload-text,
.card-body input[type="file"]::-webkit-file-upload-button,
.card-body input[type="file"]::-ms-value,
.card-body input[type="file"]::file-selector-button {
    color: var(--input-btn-color) !important;
}
/* Also keep the filename area text dark (or light) as appropriate */
.card-body input[type="file"] {
    color: var(--input-color) !important;
}

/* Theme tweaks for cards/modals to ensure SweetAlert matches both themes */
:root {
    --swal-card-bg: var(--card-bg);
    --swal-card-border: var(--card-border);
    --swal-text: #0f172a;
    --swal-subtext: var(--muted);
}
@media (prefers-color-scheme: dark) {
    :root {
        --swal-card-bg: #071029;
        --swal-card-border: #17212b;
        --swal-text: #e6eef8;
        --swal-subtext: #9aa6b2;
    }
}
/* .dark fallback */
.dark {
    --swal-card-bg: #071029;
    --swal-card-border: #17212b;
    --swal-text: #e6eef8;
    --swal-subtext: #9aa6b2;
}

/* SweetAlert2 visual styles (use custom class names via mixin below) */
.swal2-popup-custom,
.swal2-popup {
    background: var(--swal-card-bg) !important;
    color: var(--swal-text) !important;
    border: 1px solid var(--swal-card-border) !important;
    box-shadow: var(--shadow) !important;
    border-radius: 12px !important;
    font-family: inherit;
}

/* Title and content */
.swal2-title-custom,
.swal2-title {
    color: var(--accent) !important;
    font-weight: 700 !important;
}
.swal2-content-custom,
.swal2-content {
    color: var(--swal-subtext) !important;
}

/* Buttons: confirm / cancel (styled to match app buttons) */
.swal2-confirm-custom,
.swal2-styled.swal2-confirm {
    background: linear-gradient(90deg,var(--accent),#3b5afe) !important;
    color: #fff !important;
    border: none !important;
    padding: .45rem .9rem !important;
    border-radius: 8px !important;
    box-shadow: 0 6px 18px rgba(86,97,248,.12) !important;
    margin: .25rem !important;
}
.swal2-cancel-custom,
.swal2-styled.swal2-cancel {
    background: transparent !important;
    color: var(--swal-text) !important;
    border: 1px solid var(--swal-card-border) !important;
    padding: .45rem .9rem !important;
    border-radius: 8px !important;
    margin: .25rem !important;
}

/* Ensure focus/hover remain consistent across themes */
.swal2-styled:focus {
    box-shadow: 0 0 0 6px rgba(59,92,248,0.08) !important;
    outline: none !important;
}

/* keep small screens fine */
@media (max-width: 420px) {
    .swal2-popup {
        width: calc(100% - 2rem) !important;
    }
}

/* SweetAlert icon sizing: adjusted to match requests/edit */
.swal2-popup .swal2-icon {
    width: 48px !important;
    height: 48px !important;
    font-size: 24px !important;
    line-height: 48px !important;
    border-width: 3px !important;
    margin: 0 auto 8px auto !important;
    box-shadow: none !important;
    background: transparent !important;
}
.swal2-title { font-size: 20px !important; text-align:center !important; margin-bottom:6px !important; }
.swal2-content { font-size:13px !important; text-align:center !important; margin-bottom:10px !important; }
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

@section('scripts')
<script>
/* load SweetAlert2 if missing, then init mixin and show alerts */
(function ensureSwal(done){
    if (typeof Swal !== 'undefined') { return done(); }
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    s.onload = done;
    document.head.appendChild(s);
})(function(){
    // init mixin (keeps styling consistent with CSS in this view)
    if (typeof Swal !== 'undefined') {
        window.Swal = Swal.mixin({
            customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                content: 'swal2-content-custom',
                confirmButton: 'swal2-confirm-custom',
                cancelButton: 'swal2-cancel-custom'
            },
            buttonsStyling: false
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swal === 'undefined') return;

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: {!! json_encode(session('success')) !!},
                // showConfirmButton: false,
                timer: 1800
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: {!! json_encode(session('error')) !!},
                // showConfirmButton: false,
                timer: 2800
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: {!! json_encode($errors->first()) !!},
                showConfirmButton: false,
                timer: 3500
            });
        @endif
    });
});
</script>
@endsection
