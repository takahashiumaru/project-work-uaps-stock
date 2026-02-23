<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Request;
use Illuminate\Support\Carbon;


class RequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = Product::query();

        if ($request->filled('name')) {
            $search = $request->name;

            $requests->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $requests = $requests
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString(); // supaya pagination tetap bawa filter

        return view('requests.index', compact('requests'));
    }
    public function createRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:requests,id',
            'qty_requested' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        DB::beginTransaction();

        try {
            $request = Request::create([
                'product_id' => $request->product_id,
                'qty_requested' => $request->qty_requested,
                'status' => 'Pending',
                'request_date' => now(),
                'note' => $request->note ?? null,
            ]);

            DB::commit();

            return response()->json(['message' => 'Request created successfully.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateRequest(Request $request, Request $req)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Approved,Completed,Rejected',
            'response_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        DB::beginTransaction();

        try {
            $req->update([
                'status' => $request->status,
                'response_note' => $request->response_note ?? null,
            ]);

            DB::commit();

            return response()->json(['message' => 'Request updated successfully.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRequest(Request $request)
    {
        $request = Request::where('product_id', $request->product_id)
            ->where('status', '!=', 'Completed')
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$request) {
            return response()->json(['error' => 'Request not found.'], 404);
        }

        return response()->json($request);
    }
}

