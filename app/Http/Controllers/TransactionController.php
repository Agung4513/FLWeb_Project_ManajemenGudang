<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\RestockOrder;
use App\Models\RestockItem;
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

    public function create(Request $request)
    {
        if (auth()->user()->role !== 'staff') {
            abort(403, 'AKSES DITOLAK. Hanya Staff Gudang yang boleh mencatat transaksi fisik.');
        }

        $products = Product::orderBy('name')->get();
        $suppliers = User::where('role', 'supplier')->where('is_active', true)->get();

        $restockOrder = null;
        if ($request->has('restock_id')) {
            $restockOrder = RestockOrder::with('items')->where('id', $request->restock_id)
                ->where('status', 'received')
                ->whereDoesntHave('transaction')
                ->first();

            if (!$restockOrder) {
                return redirect()->route('staff.transactions.create')
                    ->with('error', 'Restock Order tidak valid atau sudah diproses.');
            }
        }

        return view('transactions.create', compact('products', 'suppliers', 'restockOrder'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'staff') abort(403);
        if ($request->type === 'incoming' && !$request->restock_order_id) {
            return back()->with('error', 'GAGAL: Transaksi Barang Masuk (Pembelian) hanya boleh dibuat melalui proses Notifikasi Restock Order.')->withInput();
        }

        $rules = [
            'type' => 'required|in:incoming,outgoing',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'restock_order_id' => 'nullable|exists:restock_orders,id',
        ];

        if ($request->type === 'outgoing') {
            $rules['customer_name'] = 'required|string|max:255';
        } else {
            $rules['supplier_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            if ($request->restock_order_id) {
                if (Transaction::where('restock_order_id', $request->restock_order_id)->exists()) {
                    throw new \Exception("Transaksi untuk PO ini sudah pernah dibuat sebelumnya!");
                }

                $poItems = RestockItem::where('restock_order_id', $request->restock_order_id)
                    ->get()
                    ->keyBy('product_id');

                foreach ($request->items as $inputItem) {
                    $prodId = $inputItem['product_id'];
                    $inputQty = $inputItem['quantity'];

                    if (!isset($poItems[$prodId])) {
                        $prodName = Product::find($prodId)->name ?? 'ID: '.$prodId;
                        throw new \Exception("ERROR: Produk '$prodName' tidak ada dalam daftar PO asli.");
                    }

                    $maxQty = $poItems[$prodId]->quantity;
                    if ($inputQty > $maxQty) {
                        $prodName = Product::find($prodId)->name ?? 'Unknown';
                        throw new \Exception("ERROR: Jumlah input '$prodName' ($inputQty) melebihi jumlah di PO ($maxQty).");
                    }
                }
            }

            if ($request->type === 'outgoing') {
                foreach ($request->items as $itemData) {
                    $productCheck = Product::find($itemData['product_id']);

                    if (!$productCheck) {
                        throw new \Exception("Produk ID {$itemData['product_id']} tidak ditemukan.");
                    }

                    if ($productCheck->current_stock < $itemData['quantity']) {
                        throw new \Exception("ERROR: Stok produk '{$productCheck->name}' tidak cukup. (Tersedia: {$productCheck->current_stock}, Diminta: {$itemData['quantity']})");
                    }
                }
            }

            $dateCode = date('Ymd');
            $prefix = 'TRX-' . $dateCode . '-';

            $lastTransaction = DB::table('transactions')
                ->where('transaction_number', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            if ($lastTransaction) {
                $lastSequence = (int) substr($lastTransaction->transaction_number, -4);
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }

            do {
                $trxNumber = $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
                $exists = DB::table('transactions')->where('transaction_number', $trxNumber)->exists();
                if ($exists) {
                    $nextSequence++;
                }
            } while ($exists);

            $transaction = Transaction::create([
                'transaction_number' => $trxNumber,
                'type' => $request->type,
                'date' => $request->date,
                'user_id' => auth()->id(),
                'supplier_id' => $request->type === 'incoming' ? $request->supplier_id : null,
                'customer_name' => $request->type === 'outgoing' ? $request->customer_name : null,
                'restock_order_id' => $request->restock_order_id,
                'status' => 'pending',
                'notes' => $request->description,
                'total_amount' => 0,
                'total_profit' => 0,
            ]);

            $totalAmount = 0;

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
                ->with('success', "Berhasil! Transaksi $trxNumber telah dibuat.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function approve(Transaction $transaction) {
        if (auth()->user()->role !== 'manager') abort(403);
        if ($transaction->status !== 'pending') return back()->with('error', 'Transaksi sudah diproses!');

        $transaction->load(['items.product' => function ($query) {
            if (method_exists(\App\Models\Product::class, 'bootSoftDeletes')) {
                $query->withTrashed();
            }
        }]);

        DB::beginTransaction();
        try {
            foreach ($transaction->items as $item) {
                $product = $item->product;
                if (!$product) throw new \Exception("Produk ID {$item->product_id} telah dihapus permanen.");

                if ($transaction->type === 'outgoing') {
                    if($product->current_stock < $item->quantity) {
                        throw new \Exception("Stok '{$product->name}' tidak cukup!");
                    }
                    $product->decrement('current_stock', $item->quantity);
                } else {
                    $product->increment('current_stock', $item->quantity);
                }
            }
            $transaction->update(['status' => 'approved']);
            DB::commit();
            return back()->with('success', 'Transaksi disetujui! Stok diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Transaction $transaction) {
        if (auth()->user()->role !== 'manager') abort(403);
        $transaction->delete();
        return redirect()->route('manager.transactions.index')->with('success', 'Ditolak.');
    }

    private function getTransactionQuery() {
        return Transaction::with(['user', 'items.product' => function($q) {
            if (method_exists(\App\Models\Product::class, 'bootSoftDeletes')) {
                $q->withTrashed();
            }
        }]);
    }

    public function staffIndex() {
        $transactions = $this->getTransactionQuery()->where('user_id', auth()->id())->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }
    public function managerIndex() {
        if (auth()->user()->role !== 'manager') abort(403);
        $transactions = $this->getTransactionQuery()->latest()->paginate(20);
        return view('transactions.index', compact('transactions'));
    }
    public function adminIndex() {
        if (auth()->user()->role !== 'admin') abort(403);
        $transactions = $this->getTransactionQuery()->latest()->paginate(30);
        return view('transactions.index', compact('transactions'));
    }
    public function show(Transaction $transaction) {
        $transaction->load(['items.product' => function($q){
            if (method_exists(\App\Models\Product::class, 'bootSoftDeletes')) {
                $q->withTrashed();
            }
        }, 'user', 'supplier', 'restockOrder']);
        return view('transactions.show', compact('transaction'));
    }
    public function managerShow(Transaction $transaction) { return $this->show($transaction); }
    public function staffShow(Transaction $transaction) { return $this->show($transaction); }
    public function adminShow(Transaction $transaction) { return $this->show($transaction); }
    public function exportExcel() { /* ... */ }
}
