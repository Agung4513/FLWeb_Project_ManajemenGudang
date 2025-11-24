<?php

namespace App\Http\Controllers;

use App\Models\RestockOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestockOrderController extends Controller
{
    public function supplierIndex()
    {
        $orders = RestockOrder::where('supplier_id', auth()->id())
            ->with(['items.product', 'manager'])
            ->latest('order_date')
            ->paginate(10);

        return view('supplier.restock-orders.index', compact('orders'));
    }

    public function index()
    {
        $orders = RestockOrder::with(['supplier', 'manager', 'items.product'])
            ->latest()
            ->paginate(15);

        return view('restock-orders.index', compact('orders'));
    }

    public function show(RestockOrder $restockOrder)
    {
        $restockOrder->load('items.product', 'supplier', 'manager');
        return view('restock-orders.show', compact('restockOrder'));
    }

    public function supplierShow(RestockOrder $restockOrder)
    {
        if ($restockOrder->supplier_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Ini bukan PO Anda.');
        }

        $restockOrder->load(['items.product', 'manager']);

        return view('supplier.restock-orders.show', compact('restockOrder'));
    }

    public function create()
    {
        $suppliers = User::where('role', 'supplier')->get();
        $products = Product::all();
        return view('restock-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'expected_delivery_date' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

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
            $product = Product::find($item['product_id']);
            $restockOrder->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('restock-orders.index')->with('success', 'Restock Order berhasil dibuat!');
    }

    public function supplierConfirm(Request $request, RestockOrder $restockOrder)
    {
        if ($restockOrder->supplier_id !== auth()->id()) {
            abort(403);
        }

        if ($restockOrder->status !== 'pending') {
            return back()->with('error', 'PO ini sudah dikonfirmasi sebelumnya.');
        }

        $request->validate([
            'supplier_notes' => 'nullable|string|max:1000'
        ]);

        $restockOrder->update([
            'status' => 'confirmed_by_supplier',
            'confirmed_by_supplier_at' => 'Dikonfirmasi pada ' . now()->format('d/m/Y H:i') . ' WIB',
            'supplier_notes' => $request->supplier_notes
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi!');
    }

    public function receive(RestockOrder $restockOrder)
    {
        if (!in_array($restockOrder->status, ['confirmed_by_supplier', 'in_transit'])) {
            return back()->with('error', 'Status tidak memungkinkan.');
        }

        $restockOrder->update([
            'status' => 'received',
            'received_at' => now()
        ]);

        foreach ($restockOrder->items as $item) {
            $item->product->increment('current_stock', $item->quantity);
        }

        return back()->with('success', 'Barang berhasil diterima dan stok diperbarui!');
    }
}
