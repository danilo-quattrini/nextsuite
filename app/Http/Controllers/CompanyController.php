<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        return view('auth.company-create');
    }

    public function choice(): View
    {
        return view('auth.company-choice');
    }

    public function create(): View
    {
        return view('company.create');
    }
}
