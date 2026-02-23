@extends('layout.admin')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                @php
                    $hour = date('H');
                    if ($hour < 12) {
                        $timeGreeting = 'Pagi';
                    } elseif ($hour < 18) {
                        $timeGreeting = 'Siang';
                    } else {
                        $timeGreeting = 'Malam';
                    }
                @endphp

                <h2 class="fw-bold mb-4">
                    Hi {{ Auth::user()->fullname }}, Selamat {{ $timeGreeting }}
                </h2>
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light">Dashboard /</span> Overview
                    </h4>
                </div>
            </div>
        </div>

        {{-- Panel Atas --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fas fa-box me-2"></i> Jenis Barang (SKU)
                    </div>
                    <div class="panel-body">
                        <p>
                            <strong class="counter text-primary" data-target="{{ $totalSku ?? 0 }}">0</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <i class="fas fa-layer-group me-2"></i> Total Fisik Unit
                    </div>
                    <div class="panel-body">
                        <p>
                            <strong class="counter text-success" data-target="{{ $totalStock ?? 0 }}">0</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fas fa-exclamation-circle me-2"></i> Request Pending Ho
                    </div>
                    <div class="panel-body">
                        <p>
                            <strong class="counter text-warning" data-target="{{ $totalFlightPerDay ?? 0 }}">0</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <i class="fas fa-chart-line me-2"></i> Stok Menipis
                    </div>
                    <div class="panel-body">
                        <p>
                            <strong class="counter text-danger" data-target="{{ $lowStock ?? 0 }}">0</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Layout Chart (Atas) --}}
        <div class="row mt-4">

            {{-- KIRI --}}
            <div class="col-lg-6 col-md-12">
                {{-- Komposisi Gudang --}}
                <div class="card chart-card-custom mb-4">
                    <div class="card-header chart-card-header-custom">
                        Komposisi Gudang
                    </div>
                    <div class="card-body">
                        <div class="chart-canvas-wrapper">
                            <canvas id="doughnutChart"></canvas>
                        </div>
                    </div>
                </div>
                {{-- Tren Laporan --}}
                <div class="card chart-card-custom ">
                    <div class="card-header chart-card-header-custom">
                        Tren Laporan Pekerjaan (7 Hari)
                    </div>
                    <div class="card-body">
                        <div class="chart-canvas-wrapper">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN --}}
            <div class="col-lg-6 col-md-12 mt-4 mt-lg-0">

                <div class="card pending-card shadow-sm border-0 h-100">
                    <div class="card-header pending-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color: #f59e0b">
                            <i class="fas fa-clock me-2" style="color: #FFC107"></i>
                            Request Pending HO
                        </h5>

                        <span class="badge" style="background-color: #FFC107;">
                            {{ $totalFlightPerDay ?? 0 }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive pending-table-wrapper">
                            <table class="table table-hover align-middle mb-0 pending-table">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Catatan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingRequests ?? [] as $key => $row)
                                        <tr>
                                            <td class="fw-semibold">{{ $row->name ?? '-' }}</td>
                                            <td>{{ $row->qty ?? '0' }}</td>
                                            <td>{{ $row->note ?? '0' }}</td>
                                            <td>
                                                <span class="badge status-pending">
                                                    {{ $row->status ?? '' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                Tidak ada request pending
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        {{-- Tabel Data Penerbangan Hari Ini --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">

                    {{-- Header --}}
                    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                        <h5 class="mb-0 fw-bold text-danger">
                            <i class="bi bi-shield-exclamation me-2"></i>
                            Peringatan: Stok Barang Menipis
                        </h5>

                        <span class="badge bg-danger">
                            {{ $lowStock ?? 0 }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th>Kode (SKU)</th>
                                        <th>Nama Barang</th>
                                        <th>Lokasi</th>
                                        <th width="20%">Sisa Stok</th>
                                        <th>Min. Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($lowStockProducts as $row)
                                        @php
                                            $percentage =
                                                $row->min_stock > 0 ? ($row->stock / $row->min_stock) * 100 : 0;
                                        @endphp

                                        <tr class="table-light">
                                            <td class="fw-semibold text-primary">
                                                {{ $row->sku }}
                                            </td>

                                            <td>
                                                {{ $row->name }}
                                            </td>

                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $row->location }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="fw-bold text-danger">
                                                    {{ $row->stock }} {{ $row->unit }}
                                                </div>

                                                {{-- Progress Bar --}}
                                                <div class="progress mt-1 custom-progress">
                                                    <div class="progress-bar bg-danger animated-progress"
                                                        data-percentage="{{ $percentage }}" style="width: 0%">
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                {{ $row->min_stock }}
                                            </td>

                                            <td>
                                                <span class="badge bg-danger">
                                                    Critical
                                                </span>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-success fw-semibold">
                                                    🎉 Semua stok aman
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
            // Line Chart: Performa Pengerjaan Pesawat
            const ctxLine = document.getElementById('lineChart');
            if (ctxLine) {
                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: lineChartLabels,
                        datasets: [{
                            label: 'Jumlah Penerbangan',
                            data: lineChartData,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 10
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 13
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
