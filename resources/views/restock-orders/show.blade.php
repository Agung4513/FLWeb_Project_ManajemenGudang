@extends('layouts.app')
@section('title', 'Detail PO ' . $restockOrder->po_number)
@section('page-title', 'Detail Purchase Order')

@section('content')
<div class="max-w-5xl mx-auto py-8">

    @php
        $backRoute = auth()->user()->role == 'supplier' ? route('supplier.restock-orders.index') : route('restock-orders.index');
    @endphp
    <a href="{{ $backRoute }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-start animate-fade-in-down">
            <i class="fa-solid fa-check-circle text-emerald-500 text-xl mr-3 mt-0.5"></i>
            <div>
                <h3 class="text-emerald-800 font-bold">Berhasil</h3>
                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-start animate-fade-in-down">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3 mt-0.5"></i>
            <div>
                <h3 class="text-red-800 font-bold">Terjadi Kesalahan</h3>
                <p class="text-red-700 text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm animate-fade-in-down">
            <div class="flex items-center mb-2">
                <i class="fa-solid fa-circle-xmark text-red-500 mr-2"></i>
                <h3 class="text-red-800 font-bold">Validasi Gagal</h3>
            </div>
            <ul class="list-disc list-inside text-red-700 text-sm ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 text-white p-8 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-black">{{ $restockOrder->po_number }}</h1>
                <p class="text-slate-400 mt-1 text-sm">
                    Dibuat pada {{ $restockOrder->order_date->format('d F Y') }} oleh
                    <span class="text-white font-bold">{{ $restockOrder->manager->name ?? 'Unknown Manager' }}</span>
                </p>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <p class="text-xs uppercase font-bold text-slate-400 mb-1">Status Pesanan</p>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-500',
                        'confirmed_by_supplier' => 'bg-blue-500',
                        'in_transit' => 'bg-indigo-500',
                        'received' => 'bg-emerald-500',
                    ];
                    $color = $statusColors[$restockOrder->status] ?? 'bg-gray-500';
                @endphp
                <span class="px-4 py-2 rounded-lg {{ $color }} text-white font-bold text-sm shadow-lg">
                    {{ strtoupper(str_replace('_', ' ', $restockOrder->status)) }}
                </span>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Informasi Supplier</h3>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl mr-4">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-lg">{{ $restockOrder->supplier->name ?? 'Supplier Terhapus' }}</p>
                        <p class="text-slate-500 text-sm">{{ $restockOrder->supplier->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Jadwal Pengiriman</h3>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl mr-4">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-lg">{{ $restockOrder->expected_delivery_date->format('d F Y') }}</p>
                        <p class="text-slate-500 text-sm">Estimasi Tiba</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Rincian Barang & Biaya</h3>
            <div class="overflow-hidden rounded-xl border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center">Jumlah</th>
                            <th class="px-6 py-4 text-right">Harga Satuan</th>
                            <th class="px-6 py-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @php $grandTotal = 0; @endphp
                        @foreach($restockOrder->items as $item)
                        @php
                            $productName = $item->product->name ?? 'Produk Terhapus';
                            $productSku = $item->product->sku ?? 'UNKNOWN';
                            $productUnit = $item->product->unit ?? 'Unit';

                            $price = $item->product->buy_price ?? 0;
                            $subtotal = $price * $item->quantity;
                            $grandTotal += $subtotal;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $productName }}
                                <span class="text-xs text-slate-400 font-normal ml-1">({{ $productSku }})</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-indigo-600 bg-indigo-50/30">
                                {{ $item->quantity }} {{ $productUnit }}
                            </td>
                            <td class="px-6 py-4 text-right text-slate-600">
                                Rp {{ number_format($price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-600 uppercase text-xs tracking-wider">Total Estimasi Tagihan</td>
                            <td class="px-6 py-4 text-right font-black text-xl text-emerald-600">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <p class="text-xs text-slate-400 mt-2 italic">* Harga berdasarkan data master produk saat ini.</p>
        </div>

        @if($restockOrder->notes || $restockOrder->supplier_notes)
        <div class="px-8 pb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($restockOrder->notes)
            <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-600 border border-gray-100">
                <strong class="block mb-1 text-gray-800 flex items-center"><i class="fa-solid fa-note-sticky mr-2 text-slate-400"></i> Catatan Manager:</strong>
                {{ $restockOrder->notes }}
            </div>
            @endif
            @if($restockOrder->supplier_notes)
            <div class="bg-blue-50 p-4 rounded-xl text-sm text-blue-800 border border-blue-100">
                <strong class="block mb-1 flex items-center"><i class="fa-solid fa-comment-dots mr-2"></i> Catatan Supplier:</strong>
                {{ $restockOrder->supplier_notes }}
            </div>
            @endif
        </div>
        @endif

        <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex justify-end gap-4">

            @if(auth()->user()->role === 'supplier' && $restockOrder->status === 'pending')
                <form action="{{ route('restock-orders.supplier-confirm', $restockOrder) }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Catatan Konfirmasi</label>
                        <textarea name="supplier_notes" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400 outline-none transition" placeholder="Contoh: Barang siap dikirim besok..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-700 shadow-lg transition transform hover:scale-105" onclick="return confirm('Konfirmasi pesanan ini?')">
                        <i class="fa-solid fa-check-double mr-2"></i> Terima Pesanan
                    </button>
                </form>
            @endif

            @if(auth()->user()->role === 'manager' && in_array($restockOrder->status, ['confirmed_by_supplier', 'in_transit']))
                <form action="{{ route('restock-orders.receive', $restockOrder) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-emerald-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-emerald-700 shadow-lg transition transform hover:scale-105" onclick="return confirm('Tandai barang sudah sampai? Ingat: Stok belum bertambah otomatis.')">
                        <i class="fa-solid fa-box-open mr-2"></i> Barang Diterima
                    </button>
                </form>
            @endif

        </div>
    </div>

    @if(auth()->user()->role === 'manager' && $restockOrder->status === 'received')
    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-xl text-yellow-800 shadow-sm flex items-start">
        <i class="fa-solid fa-lightbulb text-xl mr-3 mt-1 text-yellow-600"></i>
        <div>
            <p class="font-bold">Barang sudah diterima secara administratif.</p>
            <p class="text-sm mt-1">Silakan instruksikan Staff untuk menghitung fisik dan membuat <strong>Transaksi Masuk</strong> agar stok di sistem bertambah sesuai jumlah yang diterima.</p>
        </div>
    </div>
    @endif
</div>

<style>
    .animate-fade-in-down { animation: fadeInDown 0.5s ease-out; }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
