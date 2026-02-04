<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class ReportController extends Controller
{
    public function index()
    {
        return view('customer.report.views');
    }

    public function show(Customer $customer)
    {
        return view('customer.report.show', compact('customer'));
    }
}
