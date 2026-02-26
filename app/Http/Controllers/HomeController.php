<?php

namespace App\Http\Controllers;

use App\Models\Flights; // Pastikan nama model 'flights' sudah benar (biasanya 'Flight' singular)
use App\Models\Leave;
use App\Models\User; // Asumsi Anda punya model 'Leave' untuk data cuti/sakit
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Models\Request as StockRequest; // <-- tambah import model Request sebagai alias

class HomeController extends Controller
{
    /**
     * Menampilkan data dashboard utama.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        // =================================================================
        // BAGIAN 1: MENGAMBIL DATA UTAMA (SESUAI LOGIKA ANDA)
        // =================================================================
        // $flights = flights::whereDate('created_at', Carbon::today())->get();

        // Menghitung total staff Porter Anda
        $userCount = User::where('station', $user->station)->count();

        $userKehadiranCount = User::count();

        // Menghitung staff yang sedang bekerja (sesuai query Anda)
        $workingManpowers = DB::table('flight_details')
            ->join('flights', 'flight_details.flight_id', '=', 'flights.id')
            ->where('flights.status', 0)
            ->whereDate('flights.created_at', Carbon::today())
            ->count();

        $totalSku = DB::table('products')
            ->count();

        $totalStock = DB::table('products')
            ->sum('stock');

        $lowStock = DB::table('products')
            ->whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->count();

        // Menghitung penerbangan yang selesai hari ini (sesuai query Anda)
        // $totalFlightPerDay = flights::where('status', true)
        //     ->whereDate('created_at', Carbon::today())
        //     ->count();

        // =================================================================
        // BAGIAN 2: MENYIAPKAN DATA UNTUK INFO CARD
        // =================================================================
        // Sesuaikan nama kolom ('status_kontrak', 'status_pas') dengan database Anda
        $twoMonthsFromNow = Carbon::today()->addMonths(2);

        // Cari user yang kontraknya habis dalam 2 bulan ke depan
        $contractsExpiringSoon = User::whereDate('contract_end', '<=', $twoMonthsFromNow)
            ->whereDate('contract_end', '>=', Carbon::today()) // masih aktif
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
        // $absentToday = $userCount - $totalAbsent;
        // $attendancePercentage = ($userCount > 0) ? round(($totalAbsent / $userCount) * 100) : 0;

        $absentUsers = DB::table('leaves')
            ->join('users', 'leaves.user_id', '=', 'users.id')
            ->whereDate('leaves.start_date', '<=', Carbon::today())
            ->whereDate('leaves.end_date', '>=', Carbon::today())
            ->where('leaves.status', 'approved')
            ->select('users.id', 'users.fullname', 'leaves.leave_type', 'leaves.status')
            ->get();

        // Total absent
        $totalAbsent = $absentUsers->count();

        // Total hadir
        $presentToday = $userKehadiranCount - $totalAbsent;

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
// dd($productLocationData);

        // Persentase hadir
        $attendancePercentage = $userKehadiranCount > 0
            ? round(($presentToday / $userKehadiranCount) * 100, 2) // 2 angka di belakang koma
            : 0;

        // =================================================================
        // BAGIAN 3: MENYIAPKAN DATA UNTUK SEMUA CHART
        // =================================================================
        $lineChartLabels = [];
        $lineChartData = [];
        $barChartLabels = [];
        $sickData = [];
        $leaveData = [];

        // for ($i = 6; $i >= 0; $i--) {
        //     $date = Carbon::now()->subDays($i);
        //     $dateString = $date->format('Y-m-d');
        //     $dayName = $date->locale('id')->dayName;

        //     // Label untuk Line Chart dan Bar Chart
        //     $lineChartLabels[] = $dayName;
        //     $barChartLabels[] = $dayName;

        //     // Data Line Chart: Total penerbangan per hari
        //     $lineChartData[] = flights::whereDate('created_at', $dateString)->count();

        //     // Data Bar Chart: Cuti & Sakit per hari (SUDAH DIPERBAIKI)
        //     $sickData[] = Leave::whereDate('start_date', $dateString)->where('leave_type', 'Cuti Sakit')->count();
        //     $leaveData[] = Leave::whereDate('start_date', $dateString)->where('leave_type', 'Cuti Tahunan')->count();
        // }

        // Data Doughnut Chart: Distribusi staff berdasarkan role
        $doughnutData = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        $doughnutChartLabels = $doughnutData->pluck('role');
        $doughnutChartData = $doughnutData->pluck('total');

        // Ambil request pending untuk ditampilkan di dashboard (ambil 10 terbaru mis.)
        $pendingRaw = StockRequest::with('product')
            ->where('status', 'Pending')
            ->orderBy('request_date', 'desc')
            ->limit(10)
            ->get();

        // Map ke bentuk yang digunakan di home.blade.php (name, qty, note, status)
        $pendingRequests = $pendingRaw->map(function ($r) {
            return (object) [
                'name' => $r->product->name ?? ('Product #' . $r->product_id),
                'qty' => $r->qty_requested,
                'note' => $r->note ?? '-',
                'status' => $r->status,
            ];
        });

        // Total pending count (dipakai di badge)
        $totalFlightPerDay = $pendingRaw->count();

        // =================================================================
        // BAGIAN 4: LOGIKA SWEETALERT ANDA (TETAP DIPERTAHANKAN)
        // =================================================================

        if ($user) {
            // Gunakan empty() untuk menangani null, string kosong, atau tanggal tidak valid
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

        // =================================================================
        // BAGIAN 5: MENGIRIM SEMUA VARIABEL KE VIEW
        // =================================================================
        return view('home', compact(
            // Variabel lama Anda
            'userCount',
            'workingManpowers',
            'totalSku',
            'totalStock',
            'lowStock',
            'lowStockProducts',
            // 'flights',
            // 'totalFlightPerDay',

            // Variabel baru untuk Info Card
            'totalContractStaff',
            'totalPasStaff',
            'totalAbsent',
            'attendancePercentage',
            'presentToday',

            // Variabel baru untuk Line Chart
            'lineChartLabels',
            'lineChartData',

            'productLocationLabels',
            'productLocationTotals',

            // Variabel baru untuk Doughnut Chart
            'doughnutChartLabels',
            'doughnutChartData',

            // Variabel baru untuk Bar Chart
            'barChartLabels',
            'sickData',
            'leaveData',

            // Variabel baru untuk Pending Requests
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

        // Format tanggal surat
        $tanggal_surat = now()->translatedFormat('d F Y');

        // Ambil logo dan ubah menjadi base64
        $logoPath = public_path('storage/photo/JAS Airport Services.png');
        $base64Logo = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

        // Generate PDF
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
