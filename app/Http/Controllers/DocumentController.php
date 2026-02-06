<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCustomerDocument;
use App\Jobs\GenerateDocument;
use App\Models\Customer;
use App\Models\Document;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    public function index()
    {
        $customers = Customer::with('skills')
                        ->paginate(6);
        return view('documents.index', compact('customers'));
    }

    public function create(Customer $customer)
    {
        $documentRequest = DocumentRequest::create([
            'requested_by_id' => $customer->id,
            'requested_by_type' => get_class($customer),
            'status' => 'processing',
            'type' => 'curriculum'
        ]);

        GenerateDocument::dispatch($customer, $documentRequest->type, $documentRequest->id);

        return view('documents.generation',[
            'requestId' => $documentRequest->id,
            'customer' => $documentRequest->requestBy
        ]);
    }

    public function show(Document $document)
    {
        if (!$document->file_path || !Storage::exists($document->file_path)) {
            abort(404, 'Document not found or doesn\'t exists for this customer.');
        }

        return Storage::response($document->file_path);

    }

}
