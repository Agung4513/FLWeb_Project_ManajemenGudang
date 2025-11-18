<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestockOrderController extends Controller
{
    public function index()
    {
        return view('restock-orders.index');
    }

    public function create()
    {
        return view('restock-orders.create');
    }

    public function edit()
    {
        return view('restock-orders.edit');
    }

    public function show()
    {
        return view('restock-orders.show');
    }
}
