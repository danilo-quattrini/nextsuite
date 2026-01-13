<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCustomerDocument;
use App\Models\Customer;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Storage;


class PDFController extends Controller
{
    public function index()
    {
        $customers = Customer::with('skills')
                        ->paginate(10);
        $documents = DocumentRequest::with('customer')
            ->paginate(5);
        return view('documents.index', compact('customers', 'documents'));
    }

    public function create(Customer $customer)
    {
        $documentRequest = DocumentRequest::create([
            'customer_id' => $customer->id,
            'status' => 'processing',
            'type' => 'curriculum'
        ]);
        GenerateCustomerDocument::dispatch($customer, $documentRequest->type, $documentRequest->id);

        return view('documents.generation',[
            'requestId' => $documentRequest->id,
            'customer' => $documentRequest->customer
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
