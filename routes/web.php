<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TemplateController;
use App\Livewire\Customer\CreateCustomer;
use App\Livewire\Customer\CustomerReport;
use App\Livewire\Customer\CustomerTable;
use App\Livewire\Customer\EditCustomer;
use App\Livewire\Customer\ShowCustomer;
use App\Livewire\Customer\ShowCustomerReport;
use App\Livewire\Sidebar\Changelog;
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

    Route::get('/template/create', 'create')
        ->name('template.create');

    Route::get('/template/{template}/layout', 'layout')
        ->name('template.layout');
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

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    /* DASHBOARD ROUTE */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    /* CUSTOMER ROUTES */
    Route::get('/customer/create', CreateCustomer::class)
        ->name('customer.create');

    Route::get('/customer', CustomerTable::class)
        ->name('customer.list');

    Route::get('/customer/{customer}', ShowCustomer::class)
        ->name('customer.show');

    Route::get('/customer/edit/{customer}', EditCustomer::class)
        ->name('customer.edit');

    /* REPORT ROUTES */
    Route::get('/report', CustomerReport::class)
        ->name('report.index');

    Route::get('/report/{customer}', ShowCustomerReport::class)
        ->name('report.show');

    /* COMPANY ROUTES */
    Route::livewire('/company', 'pages::company.view')
        ->name('company.show');

    /* SKILL SCHEMA ROUTES*/
    Route::livewire('skill-schema', 'pages::customer.view' )
        ->name('skill-schema');

    Route::livewire('skill-schema/create/{customer}', 'pages::skill.schema.create')
        ->name('skill-schema.create');

    /* CHANGELOG ROUTE*/
    Route::get('/changelog', Changelog::class)->name('changelog');
});