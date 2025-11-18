<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('supplier.dashboard');
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
