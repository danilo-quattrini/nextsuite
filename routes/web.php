<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TemplateController;
use App\Livewire\Customer\CustomerTable;
use App\Livewire\Customer\EditCustomer;
use App\Livewire\Sidebar\Changelog;
use App\Livewire\Skill\CreateSkill;
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

/* REPORT ROUTES */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(ReportController::class)->group(function () {

    Route::get('/report', 'index')
        ->name('report.list');

    Route::get('/report/{customer}', 'show')
        ->name('report.show');

});

/* CUSTOMER ROUTES */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->controller(CustomerController::class)->group(function () {

    Route::get('/customer/create', 'index')
        ->name('customer.create');

    Route::get('/customer', CustomerTable::class)
        ->name('customer.list');

    Route::get('/customer/{customer}', 'info')
        ->name('customer.show');

    Route::get('/customer/edit/{customer}', EditCustomer::class)
        ->name('customer.edit');
});

/* CHANGELOG ROUTE*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/changelog', Changelog::class)->name('changelog');
});

/* SKILL ROUTES*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/skill/create', CreateSkill::class)->name('skill.create');
});