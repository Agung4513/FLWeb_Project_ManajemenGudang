<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RestockOrder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())
                     ->orderBy('is_active', 'asc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.users.index', compact('users'));
    }

    public function approve(User $user)
    {
        $user->update(['is_active' => true]);
        return redirect()->back()
            ->with('success', "Akun {$user->name} ({$user->role}) berhasil diaktifkan!");
    }

    public function destroy(User $user)
    {
        if (in_array($user->role, ['admin', 'manager', 'staff'])) {
            return back()->with('error', 'AKSES DITOLAK: Anda hanya diperbolehkan menghapus akun Supplier. Akun internal (Manager/Staff) dilindungi untuk integritas data.');
        }

        if ($user->role === 'supplier') {
            $activeOrders = RestockOrder::where('supplier_id', $user->id)
                ->where('status', '!=', 'received')
                ->exists();

            if ($activeOrders) {
                return back()->with('error', 'GAGAL HAPUS: Supplier ini masih memiliki pesanan Restock yang sedang berjalan (Belum Diterima). Selesaikan pesanan terlebih dahulu.');
            }
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()
            ->with('success', "Supplier {$userName} berhasil dihapus karena tidak memiliki tanggungan aktif.");
    }
}
