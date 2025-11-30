<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus sesama Admin.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()
            ->with('success', "User {$userName} berhasil dihapus dari sistem.");
    }
}
