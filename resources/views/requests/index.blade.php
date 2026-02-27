@extends('layout.admin')

@section('title', 'Request Pengadaan')

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
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}
.card-clean{border-radius:12px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);background:#fff;border-top-left-radius:12px;border-top-right-radius:12px;}

/* MATCH products.index btn-create */
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
    max-width:100%;
    min-width:0;
}
.btn-create:hover{
    transform:translateY(-2px);
}

.table thead th{background:#fbfcff;font-weight:800;color:#111827;border-bottom:1px solid #eef2f6;}
.table td{vertical-align:middle;}

.action-btn{
    display:inline-flex;align-items:center;justify-content:center;
    width:36px;height:36px;border-radius:10px;
    color:#fff;text-decoration:none;border:0;
    transition:all .15s ease; box-shadow:0 6px 14px rgba(16,24,40,.08);
}
.action-btn:hover{transform:translateY(-1px);filter:brightness(.98);color:#fff;}
.action-btn.view{background:linear-gradient(135deg,#5dbede,#2f9fbf);}
.action-btn.approve{background:linear-gradient(135deg,#10b981,#06b06c);}
.action-btn.reject{background:linear-gradient(135deg,#ff6b6b,#ef4444);}
.action-btn.del{background:linear-gradient(135deg,#ef4444,#f97373);}

.status-badge{font-size:.8rem;padding:6px 12px;border-radius:999px;font-weight:800;}
.pagination{margin-bottom:0;}

/* FIX: header action area safe on mobile */
.header-actions{
    flex:0 0 auto;
    max-width:100%;
    display:flex;
    align-items:center;
    gap:10px;
}

/* SEARCH di card-header seperti products.index */
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

/* MOBILE: sama pola dengan products.index */
@media (max-width: 575.98px){
  .header-row{
      flex-direction:column;
      align-items:stretch;
  }
  .header-actions{
      flex-direction:column;
      align-items:stretch;
  }
  .header-actions .btn-create{
      width:100%;
      white-space:normal;
      text-align:center;
  }

  .card-header-clean{
      flex-direction:column;
      align-items:flex-start !important;
      gap:8px;
  }
  .card-search-form{ width:100%; }
}
</style>

{{-- Select2 CSS, sama seperti product & stock logs --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="py-2">

        <div class="header-row">
            <div>
                <h4>Request Pengadaan</h4>
                <div class="subtitle">Permintaan pengadaan dari cabang / user</div>
            </div>
            <div class="header-actions">
                <a href="{{ route('requests.create') }}" class="btn-create">
                    <i class="bx bx-plus me-1"></i>Buat Request
                </a>
            </div>
        </div>

        <div class="card-clean">
            <div class="card-header-clean d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold" style="color:#0f172a;"><i class="bx bx-send me-2"></i>Daftar Request</div>
                    <small class="text-muted">Approve / Reject oleh HO</small>
                </div>

                {{-- SEARCH: UI sama seperti di products.index --}}
                <form action="{{ route('requests.index') }}" method="GET" class="card-search-form">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control"
                               placeholder="Cari nama / SKU produk..." value="{{ request('name') }}">
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
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jml</th>
                                <th>Catatan</th>
                                <th>Status</th>
                                <th>Respon HO</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $r)
                                <tr>
                                    <td>{{ $r->request_date ? $r->request_date->format('d M Y') : ($r->created_at ? $r->created_at->format('d M Y') : '-') }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $r->product->name ?? '-' }}</div>
                                        <div class="text-muted small">{{ $r->product->sku ?? '' }}</div>
                                    </td>
                                    <td class="fw-bold text-primary">{{ $r->qty_requested }} {{ $r->product->unit ?? '' }}</td>
                                    <td class="text-muted small"><i>"{{ $r->note }}"</i></td>

                                    <td>
                                        @if($r->status == 'Pending')
                                            <span class="badge bg-warning text-dark status-badge">Menunggu HO</span>
                                        @elseif($r->status == 'Approved')
                                            <span class="badge bg-success status-badge">Disetujui</span>
                                        @elseif($r->status == 'Completed')
                                            <span class="badge bg-info status-badge">Selesai</span>
                                        @else
                                            <span class="badge bg-danger status-badge">Ditolak</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($r->response_note)
                                            {{ $r->response_note }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('requests.show', $r) }}" class="action-btn view" title="Lihat Detail">
                                                <i class="bx bx-show"></i>
                                            </a>

                                            @if($r->status == 'Pending')
                                                <button type="button" class="action-btn approve btn-approve-ajax"
                                                    data-id="{{ $r->id }}"
                                                    data-qty="{{ $r->qty_requested }}"
                                                    data-name="{{ $r->product->name ?? '' }}"
                                                    title="Approve">
                                                    <i class="bx bx-check"></i>
                                                </button>

                                                <button type="button" class="action-btn reject btn-reject-ajax"
                                                    data-id="{{ $r->id }}"
                                                    data-name="{{ $r->product->name ?? '' }}"
                                                    title="Reject">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            @endif

                                            @if($r->status == 'Pending')
                                                <button type="button" class="action-btn del"
                                                    onclick="confirmDelete({{ $r->id }}, '{{ addslashes($r->product->name ?? $r->id) }}')"
                                                    title="Hapus Request">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            @endif

                                            <form id="deleteForm-{{ $r->id }}" action="{{ route('requests.destroy', $r->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada request</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        @if($requests->total() > 0)
                            Menampilkan {{ $requests->firstItem() }} - {{ $requests->lastItem() }} dari {{ $requests->total() }} request
                        @else
                            Tidak ada data
                        @endif
                    </div>
                    <div>
                        {{ $requests->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="approveForm" class="modal-content">
            @csrf
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title">Setujui Permintaan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="approve_request_id" name="request_id" value="">
                <div class="mb-2">
                    <label class="form-label fw-semibold">Produk</label>
                    <div id="approve_product_info" class="fw-bold"></div>
                    <div id="approve_product_sku" class="small text-muted"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah yang akan ditambahkan ke stok</label>
                    <div id="approve_qty" class="fw-bold text-primary"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan Respon / No. Resi (opsional)</label>
                    <textarea name="response_note" id="approve_response_note" class="form-control" rows="3" placeholder="Catatan pengiriman, no. resi, dll"></textarea>
                </div>

                <div class="alert alert-info small mb-0">
                    Menyetujui permintaan ini akan menambah stok produk sebesar nilai di atas.
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="approveSubmitBtn" class="btn btn-success">Setujui & Tambah Stok</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="rejectForm" class="modal-content">
            @csrf
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title">Tolak Permintaan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="reject_request_id" name="request_id" value="">
                <div class="mb-2">
                    <label class="form-label fw-semibold">Produk</label>
                    <div id="reject_product_info" class="fw-bold"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alasan Penolakan</label>
                    <textarea name="response_note" id="reject_response_note" class="form-control" rows="3" required placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>

                <div class="alert alert-warning small mb-0">
                    Menolak permintaan akan mencatat alasan penolakan pada request.
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="rejectSubmitBtn" class="btn btn-danger">Tolak Permintaan</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '{{ csrf_token() }}';
    const approveBase = "{{ url('requests') }}";
    const rejectBase = "{{ url('requests') }}";

    // Bootstrap modal instances
    const approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));

    // populate and show modal when clicking approve button
    document.querySelectorAll('.btn-approve-ajax').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const qty = this.dataset.qty;
            const pname = this.dataset.name;
            document.getElementById('approve_request_id').value = id;
            document.getElementById('approve_product_info').textContent = pname;
            document.getElementById('approve_product_sku').textContent = '';
            document.getElementById('approve_qty').textContent = qty;
            document.getElementById('approve_response_note').value = '';
            approveModal.show();
        });
    });

    // submit approve form via AJAX
    document.getElementById('approveForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('approve_request_id').value;
        const note = document.getElementById('approve_response_note').value || '';
        const url = approveBase + '/' + id + '/approve';
        const btn = document.getElementById('approveSubmitBtn');
        btn.disabled = true;
        fetch(url, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': csrfToken,'Content-Type': 'application/json','Accept': 'application/json'},
            body: JSON.stringify({ response_note: note })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            btn.disabled = false;
            approveModal.hide();
            if (status >= 200 && status < 300) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: body.message || 'Request approved.' });
                setTimeout(() => location.reload(), 700);
            } else if (status === 422) {
                Swal.fire({ icon: 'warning', title: 'Validation', text: Object.values(body.errors).flat().join(' ') });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: body.error || 'Terjadi kesalahan' });
            }
        })
        .catch(err => {
            btn.disabled = false;
            approveModal.hide();
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.' });
        });
    });

    // open reject modal and populate
    document.querySelectorAll('.btn-reject-ajax').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const pname = this.dataset.name;
            document.getElementById('reject_request_id').value = id;
            document.getElementById('reject_product_info').textContent = pname;
            document.getElementById('reject_response_note').value = '';
            rejectModal.show();
        });
    });

    // submit reject form via AJAX
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('reject_request_id').value;
        const note = document.getElementById('reject_response_note').value || '';
        const url = rejectBase + '/' + id + '/reject';
        const btn = document.getElementById('rejectSubmitBtn');
        btn.disabled = true;
        fetch(url, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': csrfToken,'Content-Type': 'application/json','Accept': 'application/json'},
            body: JSON.stringify({ response_note: note })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            btn.disabled = false;
            rejectModal.hide();
            if (status >= 200 && status < 300) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: body.message || 'Request rejected.' });
                setTimeout(() => location.reload(), 700);
            } else if (status === 422) {
                Swal.fire({ icon: 'warning', title: 'Validation', text: Object.values(body.errors).flat().join(' ') });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: body.error || 'Terjadi kesalahan' });
            }
        })
        .catch(err => {
            btn.disabled = false;
            rejectModal.hide();
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.' });
        });
    });

    @if (session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}', timer: 2500, showConfirmButton: false });
    @endif
});

// delete confirmation
function confirmDelete(requestId, requestName) {
    Swal.fire({
        title: 'Hapus Request?',
        html: `Apakah Anda yakin ingin menghapus request <strong>${requestName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm-' + requestId).submit();
        }
    });
}
</script>
@endsection