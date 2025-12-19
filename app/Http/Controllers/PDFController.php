<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;


class PDFController extends Controller
{
    public function show(Customer $customer)
    {

        list($customer, $skills) = $this->customer_info($customer);

        $pdf = Pdf::loadView('pdf.document-example', compact('customer', 'skills'));

        return $pdf->stream('document-example.pdf');
    }

    /**
     * @return array
     */
    public function customer_info(Customer $customer): array
    {
        $skills = $customer->skills->map(function ($skill) {
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'description' => $skill->description,
                'years' => $skill->pivot->years ?? 0,
                'level' => $skill->pivot->level ?? null,
            ];
        });
        return array($customer, $skills);
    }
}
