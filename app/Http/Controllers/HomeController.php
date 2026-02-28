<?php

namespace App\Http\Controllers;

use App\Models\Flights;
use App\Models\Leave;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Models\Request as StockRequest;
use App\Models\WorkReport;

class HomeController extends Controller
{
    /**
     * Menampilkan data dashboard utama.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Menghitung total staff Porter Anda
        $userCount = User::where('station', $user->station)->count();

        $userKehadiranCount = User::count();

        $totalSku = DB::table('products')->count();

        $totalStock = DB::table('products')->sum('stock');

        $lowStock = DB::table('products')
            ->whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->count();

        // Info card: kontrak dan PAS yang akan segera habis
        $twoMonthsFromNow = Carbon::today()->addMonths(2);

        $contractsExpiringSoon = User::whereDate('contract_end', '<=', $twoMonthsFromNow)
            ->whereDate('contract_end', '>=', Carbon::today())
            ->get();

        $totalContractStaff = $contractsExpiringSoon->count();

        $pasExpiringSoon = User::whereDate('pas_expired', '<=', $twoMonthsFromNow)
            ->whereDate('pas_expired', '>=', Carbon::today())
            ->get();

        $lowStockProducts = DB::table('products')
            ->whereColumn('stock', '<=', 'min_stock')
            ->limit(100)
            ->get();

        $totalPasStaff = $pasExpiringSoon->count();

        // Doughnut Chart: Distribusi SKU berdasarkan Location
        $productLocationData = DB::table('products')
            ->whereNotNull('location')
            ->select('location', DB::raw('COUNT(*) as total'))
            ->groupBy('location')
            ->orderByDesc('total')
            ->get();

        $productLocationLabels = $productLocationData
            ->pluck('location')
            ->values()
            ->toArray();

        $productLocationTotals = $productLocationData
            ->pluck('total')
            ->map(fn($v) => (int) $v)
            ->values()
            ->toArray();

        // =================================================================
        // BAGIAN 3: MENYIAPKAN DATA UNTUK SEMUA CHART
        // =================================================================
        $lineChartLabels = [];
        $lineChartData = [];
        $barChartLabels = [];
        $sickData = [];
        $leaveData = [];

        // Tren Laporan Pekerjaan (Approved) - 7 hari terakhir
        $start = Carbon::today()->subDays(6);
        $end   = Carbon::today();

        $workReportCounts = WorkReport::query()
            ->where('status', '=', 'Approved')
            ->whereDate('work_date', '>=', $start)
            ->whereDate('work_date', '<=', $end)
            ->selectRaw('DATE(work_date) as d, COUNT(*) as total')
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('total', 'd');

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $key = $date->format('Y-m-d');
            $lineChartLabels[] = $date->translatedFormat('d M');
            $lineChartData[] = (int) ($workReportCounts[$key] ?? 0);
        }

        // Data Doughnut Chart: Distribusi staff berdasarkan role
        $doughnutData = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        $doughnutChartLabels = $doughnutData->pluck('role');
        $doughnutChartData = $doughnutData->pluck('total');

        // Ambil request pending untuk ditampilkan di dashboard (ambil 10 terbaru)
        $pendingRaw = StockRequest::with('product')
            ->where('status', 'Pending')
            ->orderBy('request_date', 'desc')
            ->limit(10)
            ->get();

        $pendingRequests = $pendingRaw->map(function ($r) {
            return (object) [
                'name' => $r->product->name ?? ('Product #' . $r->product_id),
                'qty' => $r->qty_requested,
                'note' => $r->note ?? '-',
                'status' => $r->status,
            ];
        });

        $totalFlightPerDay = $pendingRaw->count();

        // SweetAlert pemberitahuan PAS
        if ($user) {
            if (empty($user->pas_expired)) {
                Alert::warning('Peringatan', '⚠️ Belum ada data masa berlaku PAS Anda. Harap isi segera.');
            } else {
                $expiredDate = Carbon::parse($user->pas_expired);
                $today = Carbon::today();
                $diffMonths = ceil($today->diffInDays($expiredDate) / 30);

                if ($diffMonths <= 2 && $expiredDate->greaterThanOrEqualTo($today)) {
                    Alert::warning('Peringatan', '⚠️ Masa berlaku PAS Anda akan habis dalam '.$diffMonths.' bulan lagi. Harap segera perpanjang.');
                }
            }
        }

        return view('home', compact(
            'userCount',
            'totalSku',
            'totalStock',
            'lowStock',
            'lowStockProducts',
            'totalContractStaff',
            'totalPasStaff',
            'lineChartLabels',
            'lineChartData',
            'productLocationLabels',
            'productLocationTotals',
            'doughnutChartLabels',
            'doughnutChartData',
            'barChartLabels',
            'sickData',
            'leaveData',
            'pendingRequests',
            'totalFlightPerDay'
        ));
    }

    /**
     * Method lain Anda (tidak diubah).
     */
    public function Document(): View
    {
        return view('document');
    }

    public function generatePDF()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->withErrors('Silakan login terlebih dahulu.');
        }

        $karyawan = DB::table('users')
            ->select('id', 'fullname', 'role', 'alamat')
            ->where('id', $user->id)
            ->first();

        if (! $karyawan) {
            return back()->withErrors('Data karyawan tidak ditemukan.');
        }

        $tanggal_surat = now()->translatedFormat('d F Y');

        $logoPath = public_path('storage/photo/JAS Airport Services.png');
        $base64Logo = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

        $pdf = Pdf::loadView('template', [
            'nama_karyawan' => $karyawan->fullname,
            'nik_karyawan' => $karyawan->id,
            'posisi_karyawan' => $karyawan->role,
            'alamat_karyawan' => $karyawan->alamat,
            'tanggal_surat' => $tanggal_surat,
            'base64' => $base64Logo,
        ]);

        return $pdf->download("Surat-Pengganti-ID-Card-{$karyawan->fullname}.pdf");
    }
}
