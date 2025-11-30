<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\RestockOrder; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class TransactionController extends Controller
{
    private function getRedirectRoute()
    {
        return 'staff.transactions.index';
    }

    // === CREATE (HANYA STAFF) ===
    public function create(Request $request)
    {
        if (auth()->user()->role !== 'staff') abort(403);

        $products = Product::orderBy('name')->get();
        $suppliers = User::where('role', 'supplier')->where('is_active', true)->get();

        // LOGIKA AUTO-FILL DARI RESTOCK ORDER
        $restockOrder = null;

        // Jika ada parameter ?restock_id=... di URL
        if ($request->has('restock_id')) {
            $restockOrder = RestockOrder::with('items')
                ->where('id', $request->restock_id)
                ->where('status', 'received')
                // Pastikan PO ini BELUM punya transaksi (agar tidak double)
                ->whereDoesntHave('transaction')
                ->first();

            if (!$restockOrder) {
                return redirect()->route('staff.transactions.create')
                    ->with('error', 'Restock Order tidak valid atau sudah diproses sebelumnya.');
            }
        }

        return view('transactions.create', compact('products', 'suppliers', 'restockOrder'));
    }

    // === STORE (HANYA STAFF) ===
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'staff') abort(403);

        $rules = [
            'type' => 'required|in:incoming,outgoing',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'restock_order_id' => 'nullable|exists:restock_orders,id', // Validasi ID PO
        ];

        // Validasi conditional
        if ($request->type === 'outgoing') {
            $rules['customer_name'] = 'required|string|max:255';
        } else {
            $rules['supplier_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // Cek Double Input untuk PO yang sama (Safety Check)
            if ($request->restock_order_id) {
                $exists = Transaction::where('restock_order_id', $request->restock_order_id)->exists();
                if ($exists) {
                    throw new \Exception("Transaksi untuk PO ini sudah pernah dibuat!");
                }
            }

            $count = Transaction::whereDate('created_at', today())->count() + 1;
            $trxNumber = 'TRX-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Simpan Header
            $transaction = Transaction::create([
                'transaction_number' => $trxNumber,
                'type' => $request->type,
                'date' => $request->date,
                'user_id' => auth()->id(),
                'supplier_id' => $request->type === 'incoming' ? $request->supplier_id : null,
                'customer_name' => $request->type === 'outgoing' ? $request->customer_name : null,
                'restock_order_id' => $request->restock_order_id, // SIMPAN HUBUNGAN KE PO
                'status' => 'pending',
                'notes' => $request->description,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            // Simpan Detail
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $totalAmount += $subtotal;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price_at_transaction' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $transaction->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('staff.transactions.index')
                ->with('success', "Transaksi berhasil dicatat! Data telah tersinkron dengan Restock Order.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    // ... Method Index, Show, Approve, Reject, Export biarkan seperti sebelumnya ...
    public function staffIndex() {
        $transactions = Transaction::with(['user', 'items.product'])->where('user_id', auth()->id())->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }
    public function managerIndex() {
        if (auth()->user()->role !== 'manager') abort(403);
        $transactions = Transaction::with(['user', 'items.product'])->latest()->paginate(20);
        return view('transactions.index', compact('transactions'));
    }
    public function adminIndex() {
        if (auth()->user()->role !== 'admin') abort(403);
        $transactions = Transaction::with(['user', 'items.product'])->latest()->paginate(30);
        return view('transactions.index', compact('transactions'));
    }
    public function show(Transaction $transaction) {
        $transaction->load(['items.product', 'user', 'supplier', 'restockOrder']);
        return view('transactions.show', compact('transaction'));
    }
    public function managerShow(Transaction $transaction) { return $this->show($transaction); }
    public function staffShow(Transaction $transaction) { return $this->show($transaction); }
    public function adminShow(Transaction $transaction) { return $this->show($transaction); }

    public function approve(Transaction $transaction) {
        if (auth()->user()->role !== 'manager') abort(403);
        if ($transaction->status !== 'pending') return back()->with('error', 'Transaksi sudah diproses!');

        DB::transaction(function () use ($transaction) {
            foreach ($transaction->items as $item) {
                if ($transaction->type === 'outgoing') {
                    if($item->product->current_stock < $item->quantity) {
                        throw new \Exception("Stok {$item->product->name} kurang!");
                    }
                    $item->product->decrement('current_stock', $item->quantity);
                } else {
                    $item->product->increment('current_stock', $item->quantity);
                }
            }
            $transaction->update(['status' => 'approved']);
        });
        return back()->with('success', 'Transaksi disetujui.');
    }

    public function reject(Transaction $transaction, Request $request) {
        if (auth()->user()->role !== 'manager') abort(403);
        $transaction->delete();
        return redirect()->route('manager.transactions.index')->with('success', 'Transaksi ditolak dan dihapus.');
    }

    public function exportExcel() {
        if (auth()->user()->role !== 'admin') abort(403);
        return Excel::download(new TransactionsExport, 'laporan.xlsx');
    }
}
