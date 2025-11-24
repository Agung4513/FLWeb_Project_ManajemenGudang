@extends('layouts.app')
@section('title', 'Detail PO - ' . $restockOrder->po_number)

@section('content')
<div class="max-w-7xl mx-auto py-12 px-6">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-700 to-pink-700 text-white p-12 text-center">
            <h1 class="text-7xl font-bold">{{ $restockOrder->po_number }}</h1>
            <p class="text-4xl mt-4">PURCHASE ORDER - GUDANG JAYA</p>
        </div>

        <!-- Status -->
        <div class="text-center py-10">
            <span class="inline-block px-12 py-6 rounded-full text-3xl font-bold
                @if($restockOrder->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($restockOrder->status == 'confirmed_by_supplier') bg-blue-100 text-blue-800
                @elseif($restockOrder->status == 'received') bg-green-100 text-green-800 @endif">
                {{ strtoupper(str_replace('_', ' ', $restockOrder->status)) }}
            </span>
        </div>

        <!-- Form Konfirmasi -->
        @if($restockOrder->status === 'pending')
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-8 border-amber-400 rounded-3xl p-12 mb-12 text-center">
            <h3 class="text-5xl font-bold text-amber-800 mb-8">KONFIRMASI PENERIMAAN PESANAN</h3>
            <form action="{{ route('supplier.restock-orders.supplier-confirm', $restockOrder) }}" method="POST">
                @csrf
                <textarea name="supplier_notes" ...></textarea>
                <button type="submit">YA, SAYA TERIMA & AKAN KIRIM</button>
            </form>
                @csrf
                <textarea name="supplier_notes" rows="5" placeholder="Catatan (opsional): Akan dikirim tanggal 28 Nov via JNE, resi akan diupdate..."
                          class="w-full p-8 border-4 border-amber-300 rounded-3xl text-xl mb-8 focus:border-amber-500"></textarea>
                <button type="submit"
                        class="bg-gradient-to-r from-green-600 to-emerald-700 text-white font-bold text-4xl px-24 py-12 rounded-3xl shadow-3xl hover:scale-105 transition">
                    YA, SAYA TERIMA & AKAN KIRIM
                </button>
            </form>
        </div>
        @elseif($restockOrder->status === 'confirmed_by_supplier')
        <div class="bg-blue-100 border-8 border-blue-500 rounded-3xl p-12 text-center mb-12">
            <p class="text-5xl font-bold text-blue-800">SUDAH DIKONFIRMASI</p>
            <p class="text-3xl mt-6 text-blue-700 font-bold">
                {{ $restockOrder->confirmed_by_supplier_at }}
            </p>
            @if($restockOrder->supplier_notes)
            <div class="mt-8 bg-white p-8 rounded-2xl shadow-xl">
                <p class="text-xl font-semibold">Catatan Anda:</p>
                <p class="text-lg text-gray-700 mt-3">{{ nl2br(e($restockOrder->supplier_notes)) }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Info PO -->
        <div class="grid grid-cols-2 gap-8 p-10">
            <div class="bg-gray-50 p-8 rounded-2xl">
                <h3 class="text-2xl font-bold mb-4">Estimasi Tiba</h3>
                <p class="text-4xl font-bold text-orange-600">
                    {{ $restockOrder->expected_delivery_date->format('d F Y') }}
                </p>
            </div>
            <div class="bg-gray-50 p-8 rounded-2xl">
                <h3 class="text-2xl font-bold mb-4">Dibuat Oleh</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $restockOrder->manager->name }}</p>
            </div>
        </div>

        <!-- Tabel Item — HARGA MUNCUL DARI buy_price -->
        <div class="p-10 bg-gray-50 rounded-2xl mx-10 mb-10">
            <h3 class="text-4xl font-bold text-center mb-10 text-gray-800">DAFTAR ITEM DIPESAN</h3>
            <table class="w-full bg-white rounded-xl shadow-lg">
                <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                    <tr>
                        <th class="px-8 py-6 text-left">Produk</th>
                        <th class="px-8 py-6 text-center">Jumlah</th>
                        <th class="px-8 py-6 text-right">Harga Beli</th>
                        <th class="px-8 py-6 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restockOrder->items as $item)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-8 py-6 font-semibold text-lg">{{ $item->product->name }}</td>
                        <td class="px-8 py-6 text-center font-bold text-xl">{{ $item->quantity }}</td>
                        <td class="px-8 py-6 text-right font-mono text-green-700 font-bold text-lg">
                            Rp {{ number_format($item->product->buy_price, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-6 text-right font-bold text-purple-600 text-xl">
                            Rp {{ number_format($item->quantity * $item->product->buy_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-gray-500 text-xl">Belum ada item</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gradient-to-r from-purple-100 to-pink-100">
                        <td colspan="3" class="px-8 py-8 text-right text-3xl font-bold text-gray-800">TOTAL NILAI PO</td>
                        <td class="px-8 py-8 text-right text-4xl font-bold text-purple-700">
                            Rp {{ number_format($restockOrder->items->sum(fn($i) => $i->quantity * $i->product->buy_price), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center py-10 text-gray-600">
            <p class="text-2xl">Terima kasih atas kerjasamanya</p>
            <p class="text-4xl font-bold text-purple-700 mt-4">GUDANG JAYA</p>
        </div>
    </div>
</div>
@endsection
