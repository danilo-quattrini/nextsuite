<?php

namespace App\Http\Controllers;


use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.create');
    }

    public function show()
    {
        return view('customer-list');
    }
}
