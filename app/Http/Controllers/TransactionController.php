<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transactions.index');
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function edit()
    {
        return view('transactions.edit');
    }

    public function show()
    {
        return view('transactions.show');
    }
}
