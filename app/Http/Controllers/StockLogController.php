<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockLogController extends Controller
{
    public function index(Request $request)
    {
        $query = StockLog::query()
            ->with(['product:id,sku,name,unit'])
            ->orderByDesc('created_at');

        if ($request->filled('type') && in_array($request->type, ['In', 'Out', 'Adjustment'], true)) {
            $query->where('type', $request->type);
        }

        $logs = $query->paginate(10)->withQueryString();
        $products = Product::select('id', 'sku', 'name', 'unit', 'stock')->orderBy('name')->get();

        return view('stock_logs.index', compact('logs', 'products'));
    }

    public function create()
    {
        $products = Product::select('id', 'sku', 'name', 'unit', 'stock')
            ->orderBy('name')
            ->get();

        return view('stock_logs.create', compact('products'));
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'type'       => 'required|in:In,Out,Adjustment',
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
            'note'       => 'nullable|string',
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        DB::beginTransaction();
        try {
            $product = Product::whereKey($request->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            $qty       = (int) $request->qty;
            $type      = $request->type;
            $oldStock  = (int) ($product->stock ?? 0);   // simpan stok sebelum update
            $newStock  = $oldStock;                      // default, akan diubah di bawah

            if ($type === 'In') {
                // MASUK -> stok bertambah
                $newStock = $oldStock + $qty;
            } elseif ($type === 'Out') {
                // KELUAR -> stok berkurang (cek tidak boleh minus)
                if ($qty > $oldStock) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->with('error', 'Stok tidak cukup untuk barang keluar.');
                }
                $newStock = $oldStock - $qty;
            } else {
                // ADJUSTMENT (Set Stock) -> perbaikan stok langsung ke nilai qty
                $newStock = $qty;
            }

            // update stok produk
            $product->stock = $newStock;
            $product->save();

            // simpan log, termasuk stok lama & baru (jika kolom tersedia)
            StockLog::create([
                'product_id' => $product->id,
                'type'       => $type,
                'qty'        => $qty,
                'note'       => $request->note,
                'user'       => Auth::user()->fullname ?? 'Admin SBY',
                'old_stock'  => $oldStock,   // optional: tambahkan kolom di DB
                'new_stock'  => $newStock,   // optional: tambahkan kolom di DB
            ]);

            DB::commit();

            return redirect()
                ->route('stock-logs.index')
                ->with('success', 'Transaksi stok berhasil dicatat dan stok diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }

    public function show(StockLog $stock_log)
    {
        $stock_log->load('product');
        return view('stock_logs.show', ['log' => $stock_log]);
    }

    public function destroy(StockLog $stock_log)
    {
        DB::beginTransaction();
        try {
            $product = null;
            if ($stock_log->product_id) {
                // kunci baris product untuk mencegah race condition
                $product = Product::whereKey($stock_log->product_id)->lockForUpdate()->first();
            }

            if ($product) {
                $currentStock = (int) ($product->stock ?? 0);
                $qty = (int) ($stock_log->qty ?? 0);
                $targetStock = $currentStock;

                if ($stock_log->type === 'In') {
                    // membalik "masuk" -> kurangi stok
                    $targetStock = $currentStock - $qty;
                } elseif ($stock_log->type === 'Out') {
                    // membalik "keluar" -> tambahkan stok
                    $targetStock = $currentStock + $qty;
                } else {
                    // Adjustment: gunakan old_stock jika tersedia
                    if (!is_null($stock_log->old_stock)) {
                        $targetStock = (int) $stock_log->old_stock;
                    } else {
                        DB::rollBack();
                        return redirect()->route('stock-logs.index')
                            ->with('error', 'Tidak dapat mengembalikan stok: data old_stock tidak tersedia pada log adjustment.');
                    }
                }

                if ($targetStock < 0) {
                    DB::rollBack();
                    return redirect()->route('stock-logs.index')
                        ->with('error', 'Tidak dapat menghapus log karena pengembalian stok akan membuat nilai stok menjadi negatif.');
                }

                $product->stock = $targetStock;
                $product->save();
            }

            // hapus log (boleh product null jika produk sudah dihapus)
            $stock_log->delete();

            DB::commit();

            return redirect()->route('stock-logs.index')->with('success', 'Log berhasil dihapus dan stok dikembalikan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('stock-logs.index')
                ->with('error', 'Gagal menghapus log: ' . $e->getMessage());
        }
    }

    /**
     * Export Stock Logs ke Excel (CSV) sesuai filter type, limit 99_999 baris.
     */
    public function exportExcel(Request $request)
    {
        $query = StockLog::with('product:id,sku,name,unit')
            ->orderByDesc('created_at');

        if ($request->filled('type') && in_array($request->type, ['In', 'Out', 'Adjustment'], true)) {
            $query->where('type', $request->type);
        }

        $logs = $query->limit(99999)->get();

        $filename = 'stock_logs_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            // Header kolom
            fputcsv($handle, ['Waktu', 'Tipe', 'SKU', 'Nama Barang', 'Qty', 'Unit', 'User', 'Keterangan']);
            foreach ($logs as $l) {
                fputcsv($handle, [
                    optional($l->created_at)->format('Y-m-d H:i:s'),
                    $l->type,
                    $l->product->sku ?? '',
                    $l->product->name ?? '',
                    $l->qty,
                    $l->product->unit ?? '',
                    $l->user,
                    $l->note,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Alihkan ke halaman export (HTML) yang bisa di-print/save as PDF.
     */
    public function exportPdf(Request $request)
    {
        // simpan filter type di query string dan kirim ke page
        return redirect()->route('stock-logs.export.pdf.page', [
            'type' => $request->type,
        ]);
    }

    /**
     * Halaman baru untuk menampilkan data yang akan di-print/save as PDF.
     */
    public function exportPdfPage(Request $request)
    {
        $query = StockLog::with('product:id,sku,name,unit')
            ->orderByDesc('created_at');

        if ($request->filled('type') && in_array($request->type, ['In', 'Out', 'Adjustment'], true)) {
            $query->where('type', $request->type);
        }

        $logs = $query->limit(99999)->get();

        // tentukan judul dinamis untuk tab & default nama file PDF (tergantung browser)
        $typeLabel = $request->type ?: 'Semua';
        $title = 'Laporan Stok Log - ' . $typeLabel . ' - ' . now()->format('Ymd_His');

        // hanya render blade biasa, tidak memaksa Content-Type PDF
        return view('stock_logs.export_pdf', compact('logs', 'title'));
    }
}
