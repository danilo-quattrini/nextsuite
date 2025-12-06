<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        return view('auth.register-business');
    }
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['min:5', 'max:255', 'string', 'required'],
            'employees' => 'required',
            'phone' => 'required',
            'business_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imageName = strtolower(str_replace(' ', '_', $validated['name'])).'.'.$request->business_photo->extension();
        $request->business_photo->move(public_path('storage/business-profile-photos'), $imageName);

        Company::create([
            'name' => $validated['name'],
            'employees' => $validated['employees'],
            'phone' => $validated['phone'],
            'business_photo' => $validated['business_photo']
        ]);

        return redirect('/');
    }
}
