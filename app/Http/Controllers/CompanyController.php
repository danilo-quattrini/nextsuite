<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        return view('auth.register-business');
    }
}
