<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>.</title>
    <style>
        body{font-family:DejaVu Sans,Arial,sans-serif;font-size:11px;color:#111827;margin:16px;}
        h2{margin-bottom:4px;}
        .subtitle{color:#6b7280;font-size:10px;margin-bottom:12px;}
        .toolbar{margin-bottom:12px;font-size:11px;}
        .toolbar a{margin-right:8px;text-decoration:none;color:#2563eb;}
        table{width:100%;border-collapse:collapse;}
        th,td{border:1px solid #e5e7eb;padding:4px 6px;}
        th{background:#f3f4f6;font-weight:700;font-size:10px;text-align:left;}
        td{font-size:10px;}
        .header-brand{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:12px;
        }
        .header-brand-left{
            display:flex;
            align-items:center;
            gap:10px;
        }
        .header-brand img{
            height:32px;
        }
        .header-brand-title{
            font-weight:700;
            font-size:12px;
        }
        .header-brand-sub{
            font-size:10px;
            color:#6b7280;
        }
        @media print{
            .toolbar{display:none;}
            body{margin:0;}
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('stock-logs.index') }}">&larr; Kembali ke Stock Logs</a>
        <span>|</span>
        <span>Dialog print akan muncul otomatis. Pilih "Save as PDF" jika ingin menyimpan.</span>
    </div>

    {{-- HEADER dengan logo PT APS (diambil dari storage/app/public/aps.jpeg) --}}
    <div class="header-brand">
        <div class="header-brand-left">
            <img src="{{ asset('storage/aps.jpeg') }}" alt="PT Angkasa Pratama Sejahtera">
            <div>
                <div class="header-brand-title">PT Angkasa Pratama Sejahtera</div>
                <div class="header-brand-sub">Sistem Monitoring Stok & Request Barang</div>
            </div>
        </div>
        <div class="header-brand-sub">
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <h1 style="text-align:center;">
        {{ $title ?? 'Laporan Stok Log' }}
    </h1>

    <h2>Riwayat Keluar Masuk Barang</h2>
    <div class="subtitle">
       
    </div>

    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Tipe</th>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>User</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
        @forelse($logs as $l)
            <tr>
                <td>{{ optional($l->created_at)->format('Y-m-d H:i:s') ?? '-' }}</td>
                <td>{{ $l->type }}</td>
                <td>{{ $l->product->sku ?? '' }}</td>
                <td>{{ $l->product->name ?? '' }}</td>
                <td>{{ $l->qty }}</td>
                <td>{{ $l->product->unit ?? '' }}</td>
                <td>{{ $l->user }}</td>
                <td>{{ $l->note }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center;color:#9ca3af;">Tidak ada data</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <script>
        // Panggil print otomatis saat halaman selesai dimuat
        window.addEventListener('load', function () {
            // Sedikit delay agar rendering table selesai
            setTimeout(function () {
                window.print();
            }, 300);
        });

        // Setelah dialog print ditutup (apapun pilihan user),
        // redirect kembali ke halaman index stock logs.
        window.onafterprint = function () {
            window.location.href = "{{ route('stock-logs.index') }}";
        };
    </script>
</body>
</html>
