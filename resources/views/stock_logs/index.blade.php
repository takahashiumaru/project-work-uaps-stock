@extends('layout.admin')

@section('title', 'Riwayat Keluar Masuk Barang')

@section('styles')
<style>
:root{--accent:#5661f8;--muted:#6b7280;--card-bg:#fff;--card-border:#eef2f6;--shadow:0 6px 18px rgba(16,24,40,.06);}
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
.card-clean{border-radius:12px;border:1px solid var(--card-border);background:var(--card-bg);box-shadow:var(--shadow);}
.card-header-clean{padding:14px 16px;border-bottom:1px solid var(--card-border);background:#fff;border-top-left-radius:12px;border-top-right-radius:12px;}

/* MATCH products.index / requests.index btn-create */
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

/* EXPORT buttons */
.btn-export{
    border-radius:10px;
    font-weight:600;
    padding:8px 12px;
    display:inline-flex;
    align-items:center;
    gap:4px;
    font-size:.85rem;
}
.btn-export-excel{
    color:#16a34a;
    border:1px solid rgba(22,163,74,.3);
    background:rgba(22,163,74,.05);
}
.btn-export-pdf{
    color:#dc2626;
    border:1px solid rgba(220,38,38,.3);
    background:rgba(220,38,38,.05);
}

/* header actions wrapper */
.header-actions{
    flex:0 0 auto;
    max-width:100%;
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
}

.table thead th{background:#fbfcff;font-weight:800;color:#111827;border-bottom:1px solid #eef2f6;}
.table td{vertical-align:middle;}
.badge-soft{font-weight:800;border-radius:999px;padding:6px 12px;display:inline-flex;align-items:center;gap:.35rem;}
.badge-in{background:rgba(16,185,129,.12);color:#059669;border:1px solid rgba(16,185,129,.18);}
.badge-out{background:rgba(239,68,68,.12);color:#dc2626;border:1px solid rgba(239,68,68,.18);}
.badge-adj{background:rgba(245,158,11,.14);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.pagination{margin-bottom:0;}

/* ACTION BUTTONS – match style from products.index */
.action-btn{
    display:inline-flex;align-items:center;justify-content:center;
    width:36px;height:36px;border-radius:10px;
    color:#fff;text-decoration:none;border:0;
    transition:all .15s ease; box-shadow:0 6px 14px rgba(16,24,40,.08);
}
.action-btn:hover{
    transform:translateY(-1px);filter:brightness(.98);color:#fff;
}
.action-btn.view{background:linear-gradient(135deg,#5dbede,#2f9fbf);}
.action-btn.del{background:linear-gradient(135deg,#ef4444,#f97373);}

/* FILTER wrapper – pill penuh */
.filter-wrapper{
    display:flex;
    align-items:stretch;
    gap:0;
    border-radius:999px;
    overflow:hidden;
    box-shadow:0 4px 10px rgba(15,23,42,0.06);
    border:1px solid var(--card-border);
    background:var(--card-bg);
}
.filter-wrapper .form-select{
    border:none;
    border-radius:999px 0 0 999px;
    padding:8px 12px;
    font-size:.9rem;
    min-width:210px;
}
.filter-wrapper .form-select:focus{
    box-shadow:none;
}
.filter-wrapper .btn{
    border-radius:0 999px 999px 0;
    padding:8px 16px;
    font-weight:600;
    font-size:.85rem;
    background:linear-gradient(90deg,var(--accent),#3b5afe);
    border:none;
    display:inline-flex;
    align-items:center;
    gap:4px;
}
.filter-wrapper .btn i{
    font-size:1rem;
}

/* Select2 – pastikan border ikut rounded pill */
#filter_type{
    min-width:210px;
}
/* Hanya styling untuk combobox ini, supaya tidak ganggu Select2 lain */
.filter-wrapper .select2-container--bootstrap-5 .select2-selection--single{
    border-radius:999px 0 0 999px !important;
    border:none !important;
    background-color:var(--input-bg);
}
.filter-wrapper .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered{
    color:var(--text);
    padding:6px 12px;
    font-size:.9rem;
}
.filter-wrapper .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow{
    height:100%;
}
.filter-wrapper .select2-container--bootstrap-5.select2-container--focus .select2-selection{
    border:none !important;
    box-shadow:none !important;
}
/* Container juga ikut radius kiri */
.filter-wrapper .select2-container{
    border-radius:999px 0 0 999px;
    overflow:hidden;
}

/* MOBILE */
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
    .filter-wrapper{
        width:100%;
    }
}
</style>

{{-- Select2 CSS seperti di product create --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="py-2">

    <div class="header-row">
      <div>
        <h4>Riwayat Keluar Masuk Barang</h4>
        <div class="subtitle">Audit trail transaksi stok</div>
      </div>
      <div class="header-actions">
        <a href="{{ route('stock-logs.create') }}" class="btn-create">
          <i class="bx bx-plus me-1"></i>Catat Transaksi
        </a>

        {{-- EXPORT buttons – ikut filter type saat ini --}}
        <a href="{{ route('stock-logs.export.excel', ['type' => request('type')]) }}"
           class="btn-export btn-export-excel">
            <i class="bx bx-spreadsheet"></i><span>Excel</span>
        </a>
        <a href="{{ route('stock-logs.export.pdf', ['type' => request('type')]) }}"
           class="btn-export btn-export-pdf">
            <i class="bx bxs-file-pdf"></i><span>PDF</span>
        </a>
      </div>
    </div>

    <div class="card-clean">
      <div class="card-header-clean d-flex justify-content-between align-items-center">
        <div>
          <div class="fw-bold" style="color:#0f172a;"><i class="bx bx-history me-2"></i>Stock Logs</div>
          <small class="text-muted">Masuk / Keluar / Adjustment</small>
        </div>

        <form method="GET" class="filter-wrapper">
          <select name="type" id="filter_type" class="form-select">
            <option value="">Semua Tipe</option>
            <option value="In" {{ request('type')==='In' ? 'selected' : '' }}>In</option>
            <option value="Out" {{ request('type')==='Out' ? 'selected' : '' }}>Out</option>
            <option value="Adjustment" {{ request('type')==='Adjustment' ? 'selected' : '' }}>Adjustment</option>
          </select>
          <button class="btn btn-primary btn-sm" type="submit">
            <i class="bx bx-filter-alt"></i><span>Filter</span>
          </button>
        </form>
      </div>

      <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        @if ($errors->any())
          <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Waktu</th>
                <th>Tipe</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>User</th>
                <th width="110">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($logs as $l)
                <tr>
                  <td class="text-muted small">{{ optional($l->created_at)->format('d/m/Y H:i') ?? '-' }}</td>
                  <td>
                    @if($l->type === 'In')
                      <span class="badge-soft badge-in"><i class="bi bi-arrow-down-left"></i>Masuk</span>
                    @elseif($l->type === 'Out')
                      <span class="badge-soft badge-out"><i class="bi bi-arrow-up-right"></i>Keluar</span>
                    @else
                      <span class="badge-soft badge-adj"><i class="bi bi-sliders"></i>Adjust</span>
                    @endif
                  </td>
                  <td>
                    <div class="fw-bold">{{ $l->product->name ?? '-' }}</div>
                    <small class="text-muted">{{ $l->product->sku ?? '' }}</small>
                  </td>
                  <td class="fw-bold">{{ $l->qty }} {{ $l->product->unit ?? '' }}</td>
                  <td class="text-muted small">{{ $l->note ?: '-' }}</td>
                  <td class="text-muted small"><i class="bi bi-person-circle me-1"></i>{{ $l->user }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <a href="{{ route('stock-logs.show', $l) }}" class="action-btn view" title="Lihat Detail">
                        <i class="bx bx-show"></i>
                      </a>
                      <form method="POST"
                            action="{{ route('stock-logs.destroy', $l) }}"
                            onsubmit="return confirm('Hapus log ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn del" title="Hapus Log">
                          <i class="bx bx-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada log</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted small">
            @if($logs->total() > 0)
              Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} log
            @else
              Tidak ada data
            @endif
          </div>
          <div>{{ $logs->links('pagination::bootstrap-5') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
{{-- Select2 JS seperti product create --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function () {
    $('#filter_type').select2({
      allowClear: true,
      width: '210px',
      theme: 'bootstrap-5',
      placeholder: 'Semua Tipe'
    });
  });
</script>
@endsection
