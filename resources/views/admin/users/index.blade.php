@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-20">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 p-8 text-white shadow-xl group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10 transition group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-2xl backdrop-blur-md">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <p class="text-sm font-bold uppercase text-slate-400 tracking-wider">Total User</p>
                </div>
                <p class="mt-4 text-4xl font-black">{{ $users->count() }}</p>
            </div>
        </div>

        @php $pendingCount = $users->where('is_active', false)->count(); @endphp
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 to-red-600 p-8 text-white shadow-xl group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/20 transition group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-2xl backdrop-blur-md">
                        <i class="fa-solid fa-user-clock"></i>
                    </div>
                    <p class="text-sm font-bold uppercase text-orange-100 tracking-wider">Butuh Approval</p>
                </div>
                <p class="mt-4 text-4xl font-black">{{ $pendingCount }}</p>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-3xl bg-white p-8 shadow-xl border border-slate-100 group">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-indigo-50 transition group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <p class="text-sm font-bold uppercase text-slate-400 tracking-wider">Dominasi Role</p>
                </div>
                <p class="mt-4 text-2xl font-bold text-slate-800 capitalize">
                    {{ $users->groupBy('role')->sort()->keys()->last() ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">

        <div class="px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-slate-50/50">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800">Daftar Pengguna Sistem</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola akses Staff, Manager, dan Supplier.</p>
            </div>

            <div class="mt-4 md:mt-0 flex flex-col gap-2">
                @if(session('success'))
                <div class="px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-bold rounded-lg flex items-center shadow-sm">
                    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="px-4 py-2 bg-red-100 text-red-700 text-sm font-bold rounded-lg flex items-center shadow-sm animate-pulse">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-8 py-6">Pengguna</th>
                        <th class="px-8 py-6">Role</th>
                        <th class="px-8 py-6">Status Akun</th>
                        <th class="px-8 py-6 text-right">Bergabung</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition duration-200 group">

                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white flex items-center justify-center font-bold text-lg shadow-md mr-4 group-hover:scale-110 transition">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 text-base">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-400 font-medium">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-5">
                            @php
                                $roleStyles = [
                                    'admin' => 'bg-slate-800 text-white shadow-slate-500/30',
                                    'manager' => 'bg-purple-100 text-purple-700 border border-purple-200',
                                    'staff' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                    'supplier' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                ];
                                $style = $roleStyles[$user->role] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide {{ $style }} shadow-sm">
                                {{ $user->role }}
                            </span>
                        </td>

                        <td class="px-8 py-5">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-200">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200 animate-pulse">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span> Menunggu Approval
                                </span>
                            @endif
                        </td>

                        <td class="px-8 py-5 text-right text-sm text-slate-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-3 opacity-80 group-hover:opacity-100 transition">
                                @if(!$user->is_active)
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="bg-emerald-500 text-white px-4 py-2 rounded-xl font-bold text-xs hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 transition transform hover:scale-105 flex items-center">
                                            <i class="fa-solid fa-check mr-2"></i> Approve
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user {{ $user->name }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 px-3 py-2 rounded-xl font-bold transition text-xs" title="Hapus User">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-user-slash text-4xl mb-3"></i>
                            <p>Tidak ada pengguna lain ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
