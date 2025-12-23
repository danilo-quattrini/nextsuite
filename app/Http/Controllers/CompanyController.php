<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        return view('auth.register-business');
    }

    public function choice(): View
    {
        return view('auth.company-choice');
    }
}
