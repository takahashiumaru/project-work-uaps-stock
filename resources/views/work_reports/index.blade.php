@extends('layout.admin')

@section('title', 'Laporan Pekerjaan')

@section('styles')
<style>
:root{
    --accent:#5661f8;
    --muted:#6b7280;
    --card-bg:#fff;
    --card-border:#eef2f6;
    --shadow:0 6px 18px rgba(16,24,40,.06);
}
/* ...existing CSS (kept concise) ... */
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.header-row > div{ min-width: 0; }
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}

/* NEW: make header actions a proper flex group and normalize button heights */
.header-actions {
    display: flex;
    gap: 8px;
    align-items: center;   /* vertical center */
    flex-wrap: wrap;      /* allow wrapping on narrow screens */
}

/* normalize primary and export button sizing so they appear aligned */
.btn-create,
.btn-export {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 40px;          /* consistent visual height */
    min-height: 40px;
    padding-top: 0.35rem;  /* slightly adjust vertical padding if needed */
    padding-bottom: 0.35rem;
    line-height: 1;
}

/* small tweak: slightly reduce icon offset */
.btn-create i,
.btn-export i { margin-right:6px; line-height:1; }

.card-clean{border-radius:12px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);background:#fff;border-top-left-radius:12px;border-top-right-radius:12px;}

/* simplified: keep your previous styles for buttons / table / pagination etc. */
/* tombol utama (sama dengan requests/stock-logs) */
.btn-create{
    background:linear-gradient(90deg,var(--accent),#3b5afe);
    border:none;
    color:#fff;
    padding:8px 18px;
    border-radius:10px;          /* ← tidak terlalu oval, ikut stock-logs */
    box-shadow:0 8px 20px rgba(86,97,248,.25);
    font-weight:700;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    white-space: nowrap;
}
.btn-create i{margin-right:6px;}
.btn-create:hover{transform:translateY(-2px);}

.btn-export{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:7px 16px;
    border-radius:10px;          /* ← sama 10px, bukan pill penuh */
    font-size:.85rem;
    font-weight:700;
    border:1px solid transparent;
    text-decoration:none;
    white-space:nowrap;
}
.btn-export span{ line-height:1; }
.btn-export-excel{
    background:rgba(22,163,74,.06);
    border-color:rgba(22,163,74,.4);
    color:#15803d;
}
.btn-export-excel i{ font-size:1.1rem; }
.badge-status{border-radius:999px;padding:6px 10px;font-weight:600;font-size:.8rem;}
.action-btn{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;color:#fff;border:0;transition:all .15s ease;box-shadow:0 6px 14px rgba(16,24,40,.08);}
.action-btn.approve{background:linear-gradient(135deg,#10b981,#06b06c);}
.action-btn.reject{background:linear-gradient(135deg,#ff6b6b,#ef4444);}

/* SweetAlert theme only (MATCH requests/edit: no manual icon sizing) */
.swal2-popup-custom,
.swal2-popup {
    background: var(--card-bg) !important;
    color: var(--muted) !important;
    border: 1px solid var(--card-border) !important;
    box-shadow: var(--shadow) !important;
    border-radius: 12px !important;
    font-family: inherit;
}

/* keep actions side-by-side */
#workReportsTable .action-group,
.action-group{
    display:inline-flex !important;
    gap:8px;
    align-items:center;
    justify-content:center;
    white-space:nowrap;
}
#workReportsTable .action-group form,
.action-group form{ margin:0; }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="header-row">
        <div>
            <h4>Data Laporan Pekerjaan</h4>
            <div class="subtitle">
                Kelola laporan pekerjaan harian dan status approval. Role aktif: <strong>{{ $role }}</strong>
            </div>
        </div>

        <div class="header-actions">
            <a href="{{ route('work-reports.create') }}" class="btn-create">
                <i class="bi bi-plus-lg"></i> Catat Laporan
            </a>

            <a href="{{ route('work-reports.export-csv') }}" class="btn-export btn-export-excel">
                <i class="bx bx-spreadsheet"></i><span>Excel</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-2">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-clean">
        <div class="card-header-clean d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <div>
                <div class="fw-bold" style="color:#0f172a;">
                    <i class="bi bi-file-earmark-text me-2"></i>Data Laporan Pekerjaan
                </div>
                <small class="text-muted">
                    Riwayat Laporan Pekerjaan :
                    <span class="text-muted small ms-1">{{ $reports->count() }} laporan</span>
                </small>
            </div>

            <form action="{{ route('work-reports.index') }}" method="GET" class="card-search-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari nama / deskripsi..." value="{{ request('q') }}">
                    <button type="submit" class="btn" style="background-color:#5061fa;color:#fff">
                        <i class="bx bx-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="workReportsTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Pekerjaan</th>
                            <th>Lampiran</th>
                            <th>Status Approval</th>
                            <th class="text-center">Aksi HO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $r)
                            <tr>
                                <td>{{ optional($r->work_date)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $r->title }}</div>
                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($r->description, 80) }}</small>
                                </td>
                                <td>
                                    @if($r->file_path)
                                        <a href="{{ asset('work_reports/'.$r->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-paperclip"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($r->status === 'Pending')
                                        <span class="badge bg-warning text-dark badge-status">Menunggu Approval</span>
                                    @elseif($r->status === 'Approved')
                                        <span class="badge bg-success badge-status"><i class="bi bi-check-circle"></i> Disetujui</span>
                                    @else
                                        <span class="badge bg-danger badge-status">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($r->status === 'Pending')
                                        <div class="action-group">
                                            <form method="POST" action="{{ route('work-reports.update-status', $r) }}" class="form-approve-report">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="button" class="action-btn approve btn-approve-report" data-title="{{ addslashes($r->title) }}" title="Approve">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('work-reports.update-status', $r) }}" class="form-reject-report">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <button type="button" class="action-btn reject btn-reject-report" data-title="{{ addslashes($r->title) }}" title="Reject">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <small class="text-muted">Selesai</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="text-center py-3 text-muted">Belum ada laporan yang diinput.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $reports->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function ensureSwal(done){
    if (typeof Swal !== 'undefined') return done();
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    s.onload = done;
    document.head.appendChild(s);
})(function(){
    // MATCH requests/edit: no custom sweetalert button classes; use built-in styling + colors
    if (typeof Swal !== 'undefined') {
        window.Swal = Swal.mixin({
            customClass: { popup: 'swal2-popup-custom' },
            buttonsStyling: true
        });
    }

    function runWhenReady(fn){
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
        else fn();
    }

    function isDarkMode() {
        return document.documentElement.classList.contains('dark')
            || document.body.classList.contains('dark')
            || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
    }

    // EXACTLY like requests/edit
    function getSwalBase(isDark, confirmButtonColorFallback) {
        const root = getComputedStyle(document.documentElement);
        const cardBgVar = root.getPropertyValue('--card-bg')?.trim();
        const textVar = root.getPropertyValue('--text')?.trim();
        const cardBg = cardBgVar && cardBgVar !== 'inherit' ? cardBgVar : (isDark ? '#0b1220' : '#ffffff');
        const textColor = textVar && textVar !== 'inherit' ? textVar : (isDark ? '#e6eef8' : '#000000');

        return {
            background: cardBg,
            color: textColor,
            confirmButtonColor: confirmButtonColorFallback,
            iconColor: textColor,
            allowOutsideClick: true
        };
    }

    runWhenReady(function(){
        if (typeof Swal === 'undefined') return;

        // session alerts (keep auto dismiss)
        @if(session('success'))
            Swal.fire(Object.assign({}, getSwalBase(isDarkMode(), '#10b981'), {
                icon: 'success',
                title: 'Berhasil',
                text: {!! json_encode(session('success')) !!},
                showConfirmButton: false,
                timer: 1600
            }));
        @endif

        @if(session('error'))
            Swal.fire(Object.assign({}, getSwalBase(isDarkMode(), '#ef4444'), {
                icon: 'error',
                title: 'Gagal',
                text: {!! json_encode(session('error')) !!},
                showConfirmButton: false,
                timer: 2400
            }));
        @endif

        @if($errors->any())
            Swal.fire(Object.assign({}, getSwalBase(isDarkMode(), '#ef4444'), {
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: {!! json_encode($errors->first()) !!},
                showConfirmButton: false,
                timer: 3000
            }));
        @endif

        // Approve confirm (copy requests/edit behavior)
        document.querySelectorAll('.btn-approve-report').forEach(function(btn){
            btn.addEventListener('click', function(){
                const form = this.closest('form');
                const isDark = isDarkMode();
                const base = getSwalBase(isDark, '#10b981');
                const cancelBtnColor = isDark ? '#6b7280' : '#6c757d';

                Swal.fire(Object.assign({}, base, {
                    title: 'Yakin ingin menyetujui?',
                    text: 'Setujui laporan ini dan ubah status menjadi Approved.',
                    icon: 'question',
                    iconColor: '#3b82f6',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, setujui',
                    cancelButtonText: 'Batal',
                    cancelButtonColor: cancelBtnColor,
                })).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Reject confirm (copy requests/edit behavior)
        document.querySelectorAll('.btn-reject-report').forEach(function(btn){
            btn.addEventListener('click', function(){
                const form = this.closest('form');
                const isDark = isDarkMode();
                const base = getSwalBase(isDark, '#ef4444');
                const cancelBtnColor = isDark ? '#6b7280' : '#6c757d';

                Swal.fire(Object.assign({}, base, {
                    title: 'Yakin ingin menolak?',
                    text: 'Menolak akan mengubah status laporan menjadi Rejected.',
                    icon: 'warning',
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, tolak',
                    cancelButtonText: 'Batal',
                    cancelButtonColor: cancelBtnColor,
                })).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
});
</script>
@endsection
