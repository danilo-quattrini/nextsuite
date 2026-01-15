<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TemplateController;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('home');


/* DOCUMENT ROUTES */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(DocumentController::class)->group(function(){

    Route::get('/document/', 'index')
        ->name('document.index');

    Route::get('/document/create/{customer}', 'create')
        ->name('document.create');

    Route::get('/document/{document}', 'show')
        ->name('document.show');
});

/* TEMPLATE ROUTES */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(TemplateController::class)->group(function(){
    Route::get('/template/', 'index')
        ->name('template.index');

});
/* COMPANY ROUTES */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(CompanyController::class)->group(function () {

   Route::get('/onboarding/company/create', 'index')
       ->name('auth.company.create');

   Route::get('/onboarding/company/choice', 'choice')
       ->name('company.choice');

    Route::get('/company/create', 'create')
        ->name('company.create');

   Route::get('/company', 'show')
       ->name('company.show');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(CustomerController::class)->group(function () {

    Route::get('/customer/create', 'index')
        ->name('customer.create');

    Route::get('/customer', 'show')
        ->name('customer.list');

    Route::get('/customer/{customer}', 'info')
        ->name('customer.show');
});