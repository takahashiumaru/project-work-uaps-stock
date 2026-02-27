@extends('layout.admin')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --accent: #5661f8;
            --muted: #6b7280;
            --success: #10b981;
            --warn: #f59e0b;
            --danger: #ef4444;
            --card-bg: #ffffff;
            --card-border: #eef2f6;
            --radius: 12px;
            --shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
        }

        body {
            background: #f7fbff;
            color: #0f172a;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap; /* allow wrapping */
        }

        .header-row h4 {
            margin: 0;
            font-weight: 700;
            color: var(--accent);
        }

        .header-row .subtitle {
            color: var(--muted);
            font-size: 0.95rem;
        }

        .btn-create {
            background: linear-gradient(90deg, var(--accent), #3b5afe);
            border: none;
            color: #fff;
            padding: 8px 14px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(86, 97, 248, 0.12);
            font-weight: 600;
            max-width: 100%;
            min-width: 0;
        }

        .btn-create:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 575.98px) {
            .btn-create {
                width: 100%;
                white-space: normal;
            }
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        @media(max-width: 991px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 14px 16px;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .stat-left {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .stat-label {
            color: var(--muted);
            font-weight: 600;
            font-size: 0.88rem;
        }

        .stat-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0b1220;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .stat-icon.sku {
            background: linear-gradient(135deg, var(--accent), #3b5afe);
        }

        .stat-icon.unit {
            background: linear-gradient(135deg, #10b981, #06b06c);
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #f59e0b, #f97316);
        }

        .stat-icon.low {
            background: linear-gradient(135deg, #ef4444, #f97373);
        }

        .card-clean {
            border-radius: 10px;
            border: 1px solid var(--card-border);
            background: var(--card-bg);
            box-shadow: none;
        }

        .table thead th {
            background: #fbfcff;
            color: #111827;
            font-weight: 700;
        }

        .badge-status {
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            color: #fff;
        }

        .badge-pending {
            background: var(--warn);
        }

        .badge-critical {
            background: var(--danger);
        }

        .panel {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
            border: 1px solid #e0e0e0;
        }

        .panel-heading {
            background: linear-gradient(135deg, #667eea 0%, #4180c3 100%);
            color: white;
            padding: 16px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .panel-body {
            padding: 20px;
            text-align: center;
        }

        .panel-body p {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .panel-primary .panel-heading {
            background: linear-gradient(135deg, #667eea 0%, #4180c3 100%);
        }

        .panel-success .panel-heading {
            background: linear-gradient(135deg, #34d399 0%, #059669 100%);
        }

        .panel-warning .panel-heading {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }

        .panel-danger .panel-heading {
            background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
        }

        .top-charts-container,
        .bottom-charts-container {
            display: grid;
            gap: 24px;
            margin-top: 40px;
            margin-bottom: 24px;
        }

        @media(min-width: 768px) {
            .top-charts-container {
                grid-template-columns: 1fr 1fr;
            }

            .bottom-charts-container {
                grid-template-columns: 2fr 1fr;
            }
        }

        @media(max-width: 767px) {

            .top-charts-container,
            .bottom-charts-container {
                grid-template-columns: 1fr;
            }
        }

        .chart-card-custom,
        .info-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.05);
            padding: 24px;
            display: flex;
            flex-direction: column;
            border: 1px solid #e0e0e0;
        }

        .chart-card-header-custom {
            font-weight: 700;
            font-size: 1.125rem;
            color: #2563eb;
            margin-bottom: 16px;
        }

        .chart-canvas-wrapper {
            position: relative;
            height: 320px;
        }

        .info-card h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2563eb;
            text-align: center;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 1rem;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item i {
            margin-right: 12px;
            font-size: 1.5rem;
            color: #10b981;
            width: 24px;
            text-align: center;
        }

        .info-item .label {
            font-weight: 600;
            color: #374151;
            flex: 1;
        }

        .info-item .value {
            font-weight: 700;
            color: #2563eb;
            text-align: right;
            min-width: 80px;
        }

        .table-responsive {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
            font-weight: 600;
            color: #374151;
        }

        .clickable-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .clickable-row:hover {
            background-color: #f8f9fa;
        }

        .no-click {
            cursor: default !important;
        }

        .text-right {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #4180c3 100%);
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .countdown {
            font-weight: 600;
            color: #e53e3e;
        }

        .text-success {
            color: #38a169 !important;
        }

        .text-warning {
            color: #d69e2e !important;
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 0.875rem;
        }

        .row {
            margin: 0 -12px;
        }

        .row>[class*="col-"] {
            padding: 0 12px;
        }

        /* Custom Progress */
        .custom-progress {
            height: 6px;
            border-radius: 10px;
            background-color: #f1f5f9;
            overflow: hidden;
        }

        .custom-progress .progress-bar {
            transition: width 1.2s ease-in-out;
            border-radius: 10px;
        }

        .card-header-clean {
            padding: 14px 16px;
            border-bottom: 1px solid var(--card-border);
            font-weight: 800;
            color: #0f172a;
            background: #fff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .pending-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .pending-list li:last-child {
            border-bottom: none;
        }

        .muted-sm {
            font-size: .85rem;
            color: var(--muted);
        }

        /* FIX: header right button clipped on mobile */
        .header-row{
            flex-wrap: wrap; /* allow wrapping */
        }
        .header-actions{
            flex: 0 0 auto;
            max-width: 100%;
        }

        @media (max-width: 575.98px){
            .header-row{
                flex-direction: column;
                align-items: stretch;
            }
            .header-actions,
            .header-actions .btn-create{
                width: 100%;
            }
            .header-actions .btn-create{
                white-space: normal; /* allow label wrap instead of clipping */
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="header-row">
            <div>
                <h4>Dashboard</h4>
                <div class="subtitle">Ringkasan Inventory & Requests</div>
            </div>

            <div class="header-actions">
                <a href="{{ route('requests.create') }}" class="btn-create">+ Buat Request</a>
            </div>
        </div>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Jenis Barang (SKU)</div>
                    <div class="stat-value counter" data-target="{{ $totalSku ?? 0 }}">0</div>
                </div>
                <div class="stat-icon sku"><i class="bx bx-box" style="font-size:20px"></i></div>
            </div>

            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Total Fisik Unit</div>
                    <div class="stat-value counter" data-target="{{ $totalStock ?? 0 }}">0</div>
                </div>
                <div class="stat-icon unit"><i class="bx bx-layer" style="font-size:20px"></i></div>
            </div>

            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Request Pending HO</div>
                    <div class="stat-value counter" data-target="{{ $totalFlightPerDay ?? 0 }}">0</div>
                </div>
                <div class="stat-icon pending"><i class="bx bx-time" style="font-size:20px"></i></div>
            </div>

            <div class="stat-card">
                <div class="stat-left">
                    <div class="stat-label">Stok Menipis</div>
                    <div class="stat-value counter" data-target="{{ $lowStock ?? 0 }}">0</div>
                </div>
                <div class="stat-icon low"><i class="bx bx-trending-down" style="font-size:20px"></i></div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="card-clean mb-4">
                    <div class="card-header-clean d-flex justify-content-between align-items-center">
                        <span>Komposisi Gudang</span>
                        <span class="muted-sm">Distribusi lokasi produk</span>
                    </div>
                    <div class="p-3">
                        <div class="chart-canvas-wrapper" style="height:320px;">
                            <canvas id="doughnutChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card-clean">
                    <div class="card-header-clean d-flex justify-content-between align-items-center">
                        <span>Tren (7 Hari)</span>
                        <span class="muted-sm">Aktivitas harian</span>
                    </div>
                    <div class="p-3">
                        <div class="chart-canvas-wrapper" style="height:320px;">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-clean mb-4">
                    <div class="card-header-clean d-flex justify-content-between align-items-center">
                        <span>Request Pending HO</span>
                        <span class="badge badge-pending">{{ $totalFlightPerDay ?? 0 }}</span>
                    </div>
                    <div class="p-3">
                        <ul class="list-unstyled mb-0 pending-list">
                            @forelse($pendingRequests ?? [] as $row)
                                <li class="d-flex justify-content-between align-items-start">
                                    <div class="pe-2">
                                        <div class="fw-semibold">{{ $row->name ?? '-' }}</div>
                                        <div class="muted-sm">{{ $row->note ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">{{ $row->qty ?? 0 }}</div>
                                        <div class="muted-sm">{{ $row->status ?? '' }}</div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-muted py-2">Tidak ada request pending</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="card-clean">
                    <div class="card-header-clean d-flex justify-content-between align-items-center">
                        <span>Stok Menipis (Preview)</span>
                        <span class="badge badge-critical">{{ $lowStock ?? 0 }}</span>
                    </div>
                    <div class="p-3">
                        @php $preview = collect($lowStockProducts ?? [])->take(5); @endphp
                        @if($preview->isEmpty())
                            <div class="text-center text-muted">Semua stok aman</div>
                        @else
                            <ul class="list-unstyled mb-0 pending-list">
                                @foreach($preview as $p)
                                    <li class="d-flex justify-content-between align-items-start">
                                        <div class="pe-2">
                                            <div class="fw-semibold">{{ $p->sku }} — {{ $p->name }}</div>
                                            <div class="muted-sm">{{ $p->location }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-danger">{{ $p->stock }} {{ $p->unit }}</div>
                                            <div class="muted-sm">Min {{ $p->min_stock }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-clean mt-4">
            <div class="card-header-clean d-flex justify-content-between align-items-center">
                <span>Detail Stok Menipis</span>
                <span class="muted-sm">Lengkap</span>
            </div>
            <div class="p-3 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode (SKU)</th>
                            <th>Nama Barang</th>
                            <th>Lokasi</th>
                            <th width="25%">Sisa Stok</th>
                            <th>Min. Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts as $row)
                            @php
                                $percentage = $row->min_stock > 0 ? min(100, round(($row->stock / $row->min_stock) * 100)) : 0;
                            @endphp
                            <tr>
                                <td class="fw-semibold text-primary">{{ $row->sku }}</td>
                                <td>{{ $row->name }}</td>
                                <td><span class="badge bg-secondary">{{ $row->location }}</span></td>
                                <td>
                                    <div class="fw-bold text-danger">{{ $row->stock }} {{ $row->unit }}</div>
                                    <div class="progress mt-1 custom-progress">
                                        <div class="progress-bar bg-danger" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </td>
                                <td>{{ $row->min_stock }}</td>
                                <td><span class="badge badge-critical">Critical</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Semua stok aman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        // ===== Animated Counter =====
        const counters = document.querySelectorAll('.counter');

        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const duration = 700; // total durasi animasi (ms)
            const stepTime = 16; // sekitar 60fps
            const totalSteps = duration / stepTime;
            const increment = target / totalSteps;

            let current = 0;

            const updateCounter = () => {
                current += increment;

                if (current < target) {
                    counter.innerText = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            };

            updateCounter();
        });
        // Animate Progress Bar
        document.addEventListener("DOMContentLoaded", function() {
            const progressBars = document.querySelectorAll(".animated-progress");

            progressBars.forEach((bar, index) => {
                const percentage = bar.getAttribute("data-percentage");

                setTimeout(() => {
                    bar.style.width = percentage + "%";
                }, 200 + (index * 150)); // delay berurutan biar efeknya smooth
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Ambil data dinamis yang dikirim dari Controller
            const lineChartLabels = @json($lineChartLabels ?? []);
            const lineChartData = @json($lineChartData ?? []);

            const doughnutChartLabels = @json($doughnutChartLabels ?? []);
            const doughnutChartData = @json($doughnutChartData ?? []);


            const productLocationLabels = @json($productLocationLabels ?? []);
            const productLocationTotals = @json($productLocationTotals ?? []);

            console.log(productLocationLabels);
            console.log(productLocationTotals);

            const barChartLabels = @json($barChartLabels ?? []);
            const sickData = @json($sickData ?? []);
            const leaveData = @json($leaveData ?? []);

            // 2. Inisialisasi Chart
            // Line Chart: Aktivitas Laporan Pekerjaan (7 Hari)
            // (data sumber: WorkReport)
            const ctxLine = document.getElementById('lineChart');
            if (ctxLine) {
                const c = ctxLine.getContext('2d');

                // gradient fill (top -> bottom)
                const gradient = c.createLinearGradient(0, 0, 0, 320);
                gradient.addColorStop(0, 'rgba(86, 97, 248, 0.35)');
                gradient.addColorStop(1, 'rgba(86, 97, 248, 0.02)');

                const maxVal = Math.max(0, ...(lineChartData || []));
                const suggestedMax = maxVal <= 5 ? 6 : Math.ceil(maxVal * 1.2);

                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: lineChartLabels,
                        datasets: [{
                            label: 'Laporan Approved',
                            data: lineChartData,
                            borderColor: '#5661f8',
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.45,

                            // points
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBorderWidth: 2,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#5661f8',

                            // line thickness
                            borderWidth: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: {
                                    boxWidth: 10,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    color: '#6b7280',
                                    font: { weight: '700' }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.92)',
                                titleColor: '#fff',
                                bodyColor: '#e5e7eb',
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: (ctx) => ` ${ctx.parsed.y} laporan`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#6b7280',
                                    font: { weight: '600' }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                suggestedMax,
                                grid: {
                                    color: 'rgba(15, 23, 42, 0.06)',
                                    drawBorder: false
                                },
                                ticks: {
                                    precision: 0,
                                    color: '#6b7280',
                                    font: { weight: '600' }
                                }
                            }
                        }
                    }
                });
            }

            // Doughnut Chart: Distribusi Staff
            const ctxDoughnut = document.getElementById('doughnutChart');

            if (ctxDoughnut) {
                new Chart(ctxDoughnut, {
                    type: 'doughnut',
                    data: {
                        labels: productLocationLabels,
                        datasets: [{
                            data: productLocationTotals,
                            backgroundColor: [
                                '#667eea',
                                '#48bb78',
                                '#f59e0b',
                                '#e53e3e',
                                '#9f7aea',
                                '#ed8936',
                                '#38b2ac'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            }

            // Bar Chart: Kehadiran Staff
            const ctxBar = document.getElementById('barChart');
            if (ctxBar) {
                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: barChartLabels,
                        datasets: [{
                                label: 'Sakit',
                                data: sickData,
                                backgroundColor: '#f59e0b',
                                stack: 'Absen',
                                borderRadius: 4
                            },
                            {
                                label: 'Cuti',
                                data: leaveData,
                                backgroundColor: '#e53e3e',
                                stack: 'Absen',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 5
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });
            }

            // 3. Countdown Timer
            document.querySelectorAll('.countdown').forEach(function(el) {
                const countDownDate = new Date(el.getAttribute('data-time').replace(' ', 'T')).getTime();
                if (isNaN(countDownDate)) return;

                const interval = setInterval(function() {
                    const distance = countDownDate - new Date().getTime();
                    if (distance >= 0) {
                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 *
                            60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        el.innerHTML = `${days}h ${hours}j ${minutes}m ${seconds}d`;
                    } else {
                        clearInterval(interval);
                        el.innerHTML = "<span class='text-danger'>WAKTU HABIS</span>";
                    }
                }, 1000);
            });

            // 4. Handle clickable rows
            document.querySelectorAll('.clickable-row').forEach(row => {
                row.addEventListener('click', (e) => {
                    // Prevent click if the target is a button or form element
                    if (!e.target.closest('.no-click') &&
                        !e.target.closest('button') &&
                        !e.target.closest('a') &&
                        !e.target.closest('input')) {
                        const modalId = row.getAttribute('data-bs-target');
                        if (modalId) {
                            const modal = new bootstrap.Modal(document.querySelector(modalId));
                            modal.show();
                        }
                    }
                });
            });
        });
    </script>
@endsection
