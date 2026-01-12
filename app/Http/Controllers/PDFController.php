<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCustomerDocument;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;


class PDFController extends Controller
{
    public function index()
    {
        $customers = Customer::with('skills')
                        ->paginate(10);
        return view('documents.index', compact('customers'));
    }

    public function create(Customer $customer)
    {
        GenerateCustomerDocument::dispatch($customer, 'curriculum');

        return response()->json([
            'message' => 'Document generation started. You will be notified when ready.',
        ]);
    }

    public function show(Customer $customer)
    {
        $path = $customer->document_path;

        if (!$path || !Storage::exists($path)) {
            abort(404, 'Document not found or doesn\'t exists for this customer.');
        }

        return Storage::response($path);

    }

}
