<?php

namespace App\Http\Controllers;


use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.create');
    }

    public function info(Customer $customer)
    {
        return view('customer.show', compact('customer'));
    }

}
