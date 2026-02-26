@extends('layout.admin')

@section('styles')
<style>
:root{--accent:#5661f8;--muted:#6b7280;--card-bg:#fff;--card-border:#eef2f6;--shadow:0 6px 18px rgba(16,24,40,.06);}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;}
.header-row h4{margin:0;font-weight:800;color:var(--accent);}
.header-row .subtitle{color:var(--muted);font-size:.95rem;}
.card-clean{border-radius:12px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);background:#fff;border-top-left-radius:12px;border-top-right-radius:12px;font-weight:800;}
.btn-create{background:linear-gradient(90deg,var(--accent),#3b5afe);border:none;color:#fff;padding:8px 14px;border-radius:10px;box-shadow:0 6px 18px rgba(86,97,248,.12);font-weight:700;}
.btn-create:hover{transform:translateY(-2px);}
</style>
@endsection

@section('title', 'Update Request')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="header-row">
        <div>
            <h4>Approval Request (HO)</h4>
            <div class="subtitle">Approve atau Reject request pengadaan</div>
        </div>
        <div>
            <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="card-clean mb-4">
        <div class="card-header-clean">Ubah Status Request</div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul></div>
            @endif

            <form id="requestStatusForm" action="{{ route('requests.update', $request->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Produk</label>
                    <input type="text" class="form-control" value="{{ $request->product->sku ?? '' }} - {{ $request->product->name ?? '' }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah Diminta</label>
                    <input type="text" class="form-control" value="{{ $request->qty_requested }} {{ $request->product->unit ?? '' }}" readonly>
                </div>

                <!-- show current status readonly (informasi) -->
                <div class="mb-3">
                    <label class="form-label">Status Saat Ini</label>
                    <input type="text" class="form-control" value="{{ $request->status }}" readonly>
                </div>

                <!-- hidden status field — kept for form fallback -->
                <input type="hidden" name="status" id="status" value="{{ $request->status }}">

                <div class="mb-3">
                    <label class="form-label">Catatan Respon (HO)</label>
                    <textarea name="response_note" id="response_note" class="form-control" rows="3">{{ old('response_note', $request->response_note) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" id="btnApprove" class="btn btn-success">Approve</button>
                    <button type="button" id="btnReject" class="btn btn-danger">Reject</button>
                    <a href="{{ route('requests.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const noteEl = document.getElementById('response_note');
    const btnApprove = document.getElementById('btnApprove');
    const btnReject = document.getElementById('btnReject');

    // FIX: btnSave tidak ada di markup -> jangan dipakai
    function disableButtons(disabled) {
        btnApprove.disabled = disabled;
        btnReject.disabled = disabled;
    }

    const csrfToken = '{{ csrf_token() }}';
    const baseUrl = "{{ url('requests') }}";
    const requestId = @json($request->id);
    const approveUrl = baseUrl + '/' + requestId + '/approve';
    const rejectUrl = baseUrl + '/' + requestId + '/reject';
    const indexUrl = "{{ route('requests.index') }}";

    // Approve: tampilkan konfirmasi dulu
    btnApprove.addEventListener('click', function () {
        Swal.fire({
            title: 'Yakin ingin menyetujui?',
            text: 'Setujui permintaan ini dan tambahkan stok sesuai jumlah request.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, setujui',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) return;

            const note = noteEl.value.trim();

            disableButtons(true);
            fetch(approveUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ response_note: note })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                disableButtons(false);
                if (status >= 200 && status < 300) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: body.message || 'Request approved.' })
                        .then(() => { window.location.href = indexUrl; });
                } else if (status === 422) {
                    Swal.fire({ icon: 'warning', title: 'Validation', text: Object.values(body.errors).flat().join(' ') });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: body.error || 'Terjadi kesalahan' });
                }
            })
            .catch(err => {
                disableButtons(false);
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.' });
            });
        });
    });

    // Reject: cek note, lalu tampilkan konfirmasi sebelum kirim
    btnReject.addEventListener('click', function () {
        const note = noteEl.value.trim();
        if (!note) {
            Swal.fire({ icon: 'warning', title: 'Alasan wajib', text: 'Mohon isi catatan alasan penolakan terlebih dahulu.' });
            noteEl.focus();
            return;
        }

        Swal.fire({
            title: 'Yakin ingin menolak?',
            text: 'Menolak akan mencatat alasan dan mengubah status menjadi Rejected.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, tolak',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) return;

            disableButtons(true);
            fetch(rejectUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ response_note: note })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                disableButtons(false);
                if (status >= 200 && status < 300) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: body.message || 'Request rejected.' })
                        .then(() => { window.location.href = indexUrl; });
                } else if (status === 422) {
                    Swal.fire({ icon: 'warning', title: 'Validation', text: Object.values(body.errors).flat().join(' ') });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: body.error || 'Terjadi kesalahan' });
                }
            })
            .catch(err => {
                disableButtons(false);
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.' });
            });
        });
    });

    // fallback: disable buttons on manual submit
    const form = document.getElementById('requestStatusForm');
    form.addEventListener('submit', function () { disableButtons(true); });
});
</script>
@endsection
