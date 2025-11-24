<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'items.product'])->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
{
    // KITA KIRIM ARRAY SIAP PAKAI KE VIEW — TIDAK PAKAI MAP DI BLADE!
    $products = Product::select('id', 'name', 'current_stock', 'sell_price', 'buy_price')
        ->get()
        ->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name . " (Stok: {$p->current_stock})",
                'stock' => $p->current_stock,
                'sell_price' => $p->sell_price,
                'buy_price' => $p->buy_price,
            ];
        })
        ->toArray(); // ← WAJIB toArray()!

    return view('transactions.create', compact('products'));
}

    public function store(Request $request)
    {
        $user = auth()->user();
        $isSale = $request->filled('customer_name');
        $isRestock = $request->filled('supplier_id');

        if (!$isSale && !$isRestock) {
            return back()->with('error', 'Pilih pelanggan atau supplier!');
        }

        if ($isSale && !in_array($user->role, ['admin', 'manager', 'staff'])) {
            return back()->with('error', 'Akses ditolak!');
        }
        if ($isRestock && !in_array($user->role, ['admin', 'manager'])) {
            return back()->with('error', 'Hanya Manager/Admin yang bisa restock!');
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => $isSale ? 'required|string|max:255' : '',
            'supplier_id' => $isRestock ? 'required|exists:users,id' : '',
        ]);

        DB::beginTransaction();
        try {
            $type = $isSale ? 'outgoing' : 'incoming';
            $prefix = $isSale ? 'OUT' : 'IN';
            $count = Transaction::count() + 1;

            $transaction = Transaction::create([
                'transaction_number' => "INV-{$prefix}" . now()->format('Ymd') . "-" . str_pad($count, 4, '0', STR_PAD_LEFT),
                'type' => $type,
                'date' => now(),
                'user_id' => $user->id,
                'customer_name' => $isSale ? $request->customer_name : null,
                'supplier_id' => $isRestock ? $request->supplier_id : null,
                'status' => 'completed',
                'total_amount' => 0,
                'total_profit' => 0,
            ]);

            $totalAmount = 0;
            $totalProfit = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($isSale && $product->current_stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak cukup!");
                }

                $price = $isSale ? $product->sell_price : $product->buy_price;
                $subtotal = $price * $item['quantity'];
                $profit = $isSale ? ($product->sell_price - $product->buy_price) * $item['quantity'] : 0;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_transaction' => $price,
                    'subtotal' => $subtotal,
                ]);

                $isSale ? $product->decrement('current_stock', $item['quantity'])
                        : $product->increment('current_stock', $item['quantity']);

                $totalAmount += $subtotal;
                $totalProfit += $profit;
            }

            $transaction->update([
                'total_amount' => $totalAmount,
                'total_profit' => $totalProfit,
            ]);

            DB::commit();

            $msg = $isSale ? 'Penjualan' : 'Restock';
            return redirect()->route('transactions.index')
                ->with('success', "$msg berhasil! Total: Rp " . number_format($totalAmount, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('items.product', 'user', 'supplier');
        return view('transactions.show', compact('transaction'));
    }
}
