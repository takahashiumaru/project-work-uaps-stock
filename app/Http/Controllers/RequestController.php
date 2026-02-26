<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Request as StockRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    // Tampilkan daftar request (with product info), dengan optional search mirip UI sebelumnya
    public function index(Request $request)
    {
        $query = StockRequest::with('product')->orderBy('request_date', 'desc');

        if ($request->filled('name')) {
            $search = $request->name;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(10)->withQueryString();

        return view('requests.index', compact('requests'));
    }

    // Tampilkan form buat request
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('requests.create', compact('products'));
    }

    // Simpan request baru (store)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'qty_requested' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            StockRequest::create([
                'product_id' => $request->product_id,
                'qty_requested' => $request->qty_requested,
                'status' => 'Pending',
                'request_date' => now(),
                'note' => $request->note ?? null,
            ]);

            DB::commit();
            return redirect()->route('requests.index')->with('success', 'Request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create request: ' . $e->getMessage());
        }
    }

    // Tampilkan form edit/approval
    public function edit(StockRequest $request)
    {
        return view('requests.edit', compact('request'));
    }

    // Tampilkan halaman detail request
    public function show(StockRequest $request)
    {
        // pastikan relasi product sudah dimuat
        $request->load('product');
        return view('requests.show', compact('request'));
    }

    // Buat request baru (role SBY pada contoh prosedural)
    public function createRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'qty_requested' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            StockRequest::create([
                'product_id' => $request->product_id,
                'qty_requested' => $request->qty_requested,
                'status' => 'Pending',
                'request_date' => now(),
                'note' => $request->note ?? null,
            ]);

            DB::commit();

            return redirect()->route('requests.index')->with('success', 'Request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create request: ' . $e->getMessage());
        }
    }

    // Update request (approve / reject) — menggunakan route dengan model binding StockRequest $req
    public function updateRequest(Request $request, StockRequest $req)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Approved,Completed,Rejected',
            'response_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $oldStatus = $req->status;
            $newStatus = $request->status;

            $req->update([
                'status' => $newStatus,
                'response_note' => $request->response_note ?? null,
            ]);

            // Jika transisi ke Approved (dari bukan Approved) -> tambah stok produk aman dengan lock
            if ($oldStatus !== 'Approved' && $newStatus === 'Approved') {
                $product = Product::where('id', $req->product_id)->lockForUpdate()->first();
                if ($product) {
                    $product->stock = ($product->stock ?? 0) + ($req->qty_requested ?? 0);
                    $product->save();
                }
            }

            DB::commit();
            return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update request: ' . $e->getMessage());
        }
    }

    // Update request (approve/reject/status change)
    public function update(Request $request, StockRequest $req)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Approved,Completed,Rejected',
            'response_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $oldStatus = $req->status;
            $newStatus = $request->status;

            $req->update([
                'status' => $newStatus,
                'response_note' => $request->response_note ?? null,
            ]);

            // Jika transisi ke Approved (dari bukan Approved) -> tambah stok produk aman dengan lock
            if ($oldStatus !== 'Approved' && $newStatus === 'Approved') {
                $product = Product::where('id', $req->product_id)->lockForUpdate()->first();
                if ($product) {
                    $product->stock = ($product->stock ?? 0) + ($req->qty_requested ?? 0);
                    $product->save();
                }
            }

            DB::commit();
            return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update request: ' . $e->getMessage());
        }
    }

    // Ambil latest non-completed request untuk product tertentu (API-like)
    public function getRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $req = StockRequest::where('product_id', $request->product_id)
            ->where('status', '!=', 'Completed')
            ->orderBy('request_date', 'desc')
            ->first();

        if (!$req) {
            return response()->json(['error' => 'Request not found.'], 404);
        }

        return response()->json($req);
    }

    // New: approve via AJAX (adds stock) — use $id to avoid accidental model-insert
    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'response_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $req = StockRequest::findOrFail($id); // <-- explicit fetch

        DB::beginTransaction();
        try {
            // avoid double-approve
            if ($req->status === 'Approved') {
                return response()->json(['message' => 'Already approved.'], 200);
            }

            $req->status = 'Approved';
            $req->response_note = $request->response_note ?? null;
            $req->save(); // will perform UPDATE because model was loaded

            // increment product stock with row lock
            $product = Product::where('id', $req->product_id)->lockForUpdate()->first();
            if ($product) {
                $product->stock = ($product->stock ?? 0) + ($req->qty_requested ?? 0);
                $product->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Request approved and stock updated.',
                'product_id' => $product->id ?? null,
                'new_stock' => $product->stock ?? null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // New: reject via AJAX (set status Rejected, store response_note)
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'response_note' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $req = StockRequest::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($req->status === 'Rejected') {
                return response()->json(['message' => 'Already rejected.'], 200);
            }

            $req->status = 'Rejected';
            $req->response_note = $request->response_note;
            $req->save(); // UPDATE

            DB::commit();

            return response()->json(['message' => 'Request rejected.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Hapus request
    public function destroy($id)
    {
        $req = StockRequest::findOrFail($id);

        DB::beginTransaction();
        try {
            $req->delete();
            DB::commit();
            return redirect()->route('requests.index')->with('success', 'Request berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('requests.index')->with('error', 'Gagal menghapus request: ' . $e->getMessage());
        }
    }
}

