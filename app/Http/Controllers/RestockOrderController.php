<?php

namespace App\Http\Controllers;

use App\Models\RestockOrder;
use App\Models\Product;
use App\Models\RestockItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RestockOrderController extends Controller
{
    public function index()
    {
        $orders = RestockOrder::with(['supplier', 'manager', 'items'])
            ->latest()
            ->paginate(15);

        return view('restock-orders.index', compact('orders'));
    }

    public function supplierIndex()
    {
        $orders = RestockOrder::where('supplier_id', auth()->id())
            ->with(['items.product', 'manager'])
            ->latest()
            ->paginate(10);

        return view('restock-orders.index', compact('orders'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'manager') abort(403);

        $suppliers = User::where('role', 'supplier')->where('is_active', true)->get();
        $products = Product::orderBy('name')->get();

        return view('restock-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'manager') abort(403);

        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'expected_delivery_date' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $poNumber = 'PO-' . strtoupper(Str::random(6));

            $restockOrder = RestockOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'manager_id' => auth()->id(),
                'order_date' => now(),
                'expected_delivery_date' => $request->expected_delivery_date,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            foreach ($request->items as $item) {
                RestockItem::create([
                    'restock_order_id' => $restockOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('restock-orders.index')->with('success', "PO $poNumber berhasil dibuat! Menunggu konfirmasi Supplier.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat PO: ' . $e->getMessage());
        }
    }

    public function show(RestockOrder $restockOrder)
    {
        if (auth()->user()->role === 'supplier' && $restockOrder->supplier_id !== auth()->id()) {
            abort(403);
        }

        $restockOrder->load(['items.product', 'supplier', 'manager']);
        return view('restock-orders.show', compact('restockOrder'));
    }

    public function supplierShow(RestockOrder $restockOrder)
    {
        return $this->show($restockOrder);
    }

    public function supplierConfirm(Request $request, RestockOrder $restockOrder)
    {
        if (auth()->user()->role !== 'supplier' || $restockOrder->supplier_id !== auth()->id()) {
            abort(403);
        }

        $restockOrder->update([
            'status' => 'confirmed_by_supplier',
            'confirmed_by_supplier_at' => now(),
            'supplier_notes' => $request->supplier_notes
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi! Silakan kirim barang.');
    }
    public function receive(RestockOrder $restockOrder)
    {
        if (auth()->user()->role !== 'manager') abort(403);

        if (!in_array($restockOrder->status, ['confirmed_by_supplier', 'in_transit'])) {
            return back()->with('error', 'Status PO tidak valid untuk diterima.');
        }

        $restockOrder->update([
            'status' => 'received',
        ]);

        return back()->with('success', 'Status PO diubah menjadi DITERIMA. Silakan minta Staff input Transaksi Masuk.');
    }
}
