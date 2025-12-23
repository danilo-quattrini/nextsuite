<?php

namespace App\Http\Controllers;


use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('create-customer');
    }

    public function show()
    {
        $customers = Customer::with('skills')->paginate(5);
        return view('customer-list', compact('customers'));
    }
}
