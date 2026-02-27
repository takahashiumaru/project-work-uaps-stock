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

/* tombol export excel – samakan dengan stock-logs */
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

/* search input di card-header */
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
}
.card-search-form .btn{
    height:38px;
    font-size:.85rem;
    padding:6px 12px;
    border-radius:0 8px 8px 0;
}

/* badge status – sedikit curve supaya match request index */
.badge-status{
    border-radius:999px;      /* chip style */
    padding:6px 10px;
    font-weight:600;
    font-size:.8rem;
}

/* ACTION BUTTON: samakan dengan request index (approve/reject/delete) */
.action-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:36px;
    height:36px;
    border-radius:10px;
    color:#fff;
    text-decoration:none;
    border:0;
    transition:all .15s ease;
    box-shadow:0 6px 14px rgba(16,24,40,.08);
}
.action-btn:hover{
    transform:translateY(-1px);
    filter:brightness(.98);
    color:#fff;
}
.action-btn.approve{
    background:linear-gradient(135deg,#10b981,#06b06c);
}
.action-btn.reject{
    background:linear-gradient(135deg,#ff6b6b,#ef4444);
}

/* tabel */
.table thead th{background:#fbfcff;font-weight:800;color:#111827;border-bottom:1px solid #eef2f6;}
.table td{vertical-align:middle;}

/* DataTables custom styling to match template */
.dataTables_wrapper .dataTables_paginate .paginate_button{
    padding:4px 10px;
    margin:0 2px;
    border-radius:999px;
    border:1px solid #e5e7eb !important;
    background:#ffffff !important;
    color:#4b5563 !important;
    font-size:.8rem;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current{
    background:linear-gradient(90deg,var(--accent),#3b5afe) !important;
    color:#fff !important;
    border-color:transparent !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover{
    background:#eef2ff !important;
    color:#111827 !important;
}
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input{
    border-radius:8px;
    border:1px solid var(--card-border);
    padding:4px 8px;
    font-size:.8rem;
}
.dataTables_wrapper .dataTables_filter{
    text-align:right;
}
.dataTables_wrapper .dataTables_info{
    font-size:.8rem;
    color:#6b7280;
}
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label{
    font-size:.8rem;
    color:#6b7280;
}

/* Mobile layout */
@media (max-width: 575.98px){
    .header-row{
        flex-direction: column;
        align-items: stretch;
    }
    .header-actions{
        flex-direction: column;
        align-items: stretch;
    }
    .header-actions .btn-create,
    .header-actions .btn-export{
        width: 100%;
        justify-content:center;
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

{{-- Select2 CSS (jika dibutuhkan, sudah seragam dgn stock_logs) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"/>

{{-- SweetAlert2 untuk notifikasi success --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        {{-- SUSUNAN TOMBOL: CREATE (biru) -> EXCEL (hijau) --}}
        <div class="header-actions d-flex flex-wrap gap-2 justify-content-end">
            <a href="{{ route('work-reports.create') }}" class="btn-create">
                <i class="bi bi-plus-lg"></i> Catat Laporan
            </a>

            <a href="{{ route('work-reports.export-csv') }}"
               class="btn-export btn-export-excel">
                <i class="bx bx-spreadsheet"></i><span>Excel</span>
            </a>
        </div>
    </div>

    {{-- HAPUS alert bootstrap success agar tidak dobel UI
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    --}}

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

            {{-- SEARCH di dalam card-header, UI sama seperti product index --}}
            <form action="{{ route('work-reports.index') }}" method="GET" class="card-search-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                           placeholder="Cari nama / deskripsi..." value="{{ request('q') }}">
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
                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($r->description, 80) }}
                                    </small>
                                </td>
                                <td>
                                    @if($r->file_path)
                                        <a href="{{ asset('work_reports/'.$r->file_path) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-paperclip"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($r->status === 'Pending')
                                        <span class="badge bg-warning text-dark badge-status">
                                            Menunggu Approval
                                        </span>
                                    @elseif($r->status === 'Approved')
                                        <span class="badge bg-success badge-status">
                                            <i class="bi bi-check-circle"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="badge bg-danger badge-status">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($r->status === 'Pending')
                                        {{-- FORM APPROVE --}}
                                        <form method="POST"
                                              action="{{ route('work-reports.update-status', $r) }}"
                                              class="d-inline form-approve-report">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="button"
                                                    class="action-btn approve btn-approve-report"
                                                    data-title="{{ addslashes($r->title) }}"
                                                    title="Approve">
                                                <i class="bx bx-check"></i>
                                            </button>
                                        </form>

                                        {{-- FORM REJECT --}}
                                        <form method="POST"
                                              action="{{ route('work-reports.update-status', $r) }}"
                                              class="d-inline form-reject-report">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <button type="button"
                                                    class="action-btn reject btn-reject-report"
                                                    data-title="{{ addslashes($r->title) }}"
                                                    title="Reject">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        </form>
                                    @else
                                        <small class="text-muted">Selesai</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $role === 'JKT' ? 5 : 4 }}">
                                    <div class="text-center py-3 text-muted">
                                        Belum ada laporan yang diinput.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- DataTables JS --}}
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // SweetAlert success
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        // Inisialisasi DataTables
        $('#workReportsTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[0, 'desc']], // urutkan berdasarkan tanggal (kolom pertama)
            columnDefs: [
                { orderable: false, targets: [2, 3, 4] } // non-sort kolom non relevan
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            }
        });

        // Konfirmasi APPROVE
        document.querySelectorAll('.btn-approve-report').forEach(function(btn) {
            btn.addEventListener('click', function () {
                const form   = this.closest('form');
                const title  = this.dataset.title || 'laporan ini';

                Swal.fire({
                    title: 'Approve Laporan?',
                    html: `Yakin ingin <strong>APPROVE</strong> <br><em>${title}</em>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Approve',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Konfirmasi REJECT
        document.querySelectorAll('.btn-reject-report').forEach(function(btn) {
            btn.addEventListener('click', function () {
                const form   = this.closest('form');
                const title  = this.dataset.title || 'laporan ini';

                Swal.fire({
                    title: 'Reject Laporan?',
                    html: `Yakin ingin <strong>REJECT</strong> <br><em>${title}</em>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Reject',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
