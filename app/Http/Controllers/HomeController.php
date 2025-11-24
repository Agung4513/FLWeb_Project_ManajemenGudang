<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestockOrder;
use App\Models\User;

class HomeController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function managerDashboard()
    {
        return view('manager.dashboard');
    }

    public function staffDashboard()
    {
        return view('staff.dashboard');
    }

    public function supplierDashboard()
    {
        $totalOrders = RestockOrder::where('supplier_id', auth()->id())->count();
        $pendingOrders = RestockOrder::where('supplier_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->count();
        $confirmedOrders = RestockOrder::where('supplier_id', auth()->id())
                                    ->whereIn('status', ['confirmed_by_supplier', 'in_transit', 'received'])
                                    ->count();
        $latestPending = RestockOrder::where('supplier_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->latest()
                                    ->take(5)
                                    ->get();

        return view('supplier.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'confirmedOrders',
            'latestPending'
        ));
    }

    public function adminReports()
    {
        return view('admin.reports');
    }

    public function managerReports()
    {
        return view('manager.reports');
    }

    public function staffStock()
    {
        return view('staff.stock');
    }

    public function supplierOrders()
    {
        return view('supplier.orders');
    }
}
