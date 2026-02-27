<?php

namespace App\Http\Controllers;

use App\Models\WorkReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WorkReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role ?? 'SBY';

        $q = $request->query('q');

        $reportsQuery = WorkReport::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                       ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('created_at');

        $reports = $reportsQuery->paginate(10);

        return view('work_reports.index', [
            'reports' => $reports,
            'role' => $role,
        ]);
    }

    public function create(Request $request)
    {
        // sementara: buka akses untuk semua user yg login
        // $user = $request->user();
        // if (($user->role ?? 'SBY') !== 'SBY') {
        //     abort(403);
        // }

        return view('work_reports.create');
    }

    public function store(Request $request)
    {
        // sementara: buka akses untuk semua user yg login
        // $user = $request->user();
        // if (($user->role ?? 'SBY') !== 'SBY') {
        //     abort(403);
        // }

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'work_date'   => ['required', 'date'],
            'attachment'  => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf,doc,docx'],
        ]);

        $fileName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            if ($file->isValid()) {
                $ext      = $file->getClientOriginalExtension();
                $fileName = uniqid().'.'.$ext;

                // folder publik: /public/work_reports
                $targetDir = public_path('work_reports');

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0775, true);
                }

                // pindahkan file ke folder publik
                $file->move($targetDir, $fileName);

                Log::info('WorkReport upload (public)', [
                    'original'  => $file->getClientOriginalName(),
                    'stored_as' => $fileName,
                    'path'      => $targetDir.'/'.$fileName,
                ]);
            }
        }

        WorkReport::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'work_date'   => $validated['work_date'],
            'file_path'   => $fileName, // hanya nama file, folder fixed: work_reports
            'status'      => 'Pending',
        ]);

        return redirect()
            ->route('work-reports.index')
            ->with('success', 'Laporan berhasil disubmit'.($fileName ? '!' : ' tanpa lampiran.'));
    }

    public function updateStatus(Request $request, WorkReport $workReport)
    {
        // BUKA AKSES DULU: matikan pembatasan role JKT
        // $user = $request->user();
        // if (($user->role ?? 'SBY') !== 'JKT') {
        //     abort(403);
        // }

        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ]);

        $status = $validated['action'] === 'approve' ? 'Approved' : 'Rejected';
        $workReport->update(['status' => $status]);

        return redirect()
            ->route('work-reports.index')
            ->with('success', "Status laporan berhasil diperbarui menjadi {$status}.");
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'laporan_pekerjaan_'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Judul', 'Deskripsi', 'Tanggal Pengerjaan', 'Nama File', 'Status', 'Waktu Input']);

            WorkReport::orderByDesc('work_date')
                ->chunk(200, function ($chunk) use ($handle) {
                    foreach ($chunk as $row) {
                        fputcsv($handle, [
                            $row->id,
                            $row->title,
                            $row->description,
                            optional($row->work_date)->format('Y-m-d'),
                            $row->file_path,
                            $row->status,
                            $row->created_at,
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
