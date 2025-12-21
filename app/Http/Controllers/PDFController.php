<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\OpenAIService;
use Barryvdh\DomPDF\Facade\Pdf;


class PDFController extends Controller
{
    public function show(Customer $customer, OpenAIService $openAIService)
    {

        list($customer, $skills) = $this->customer_info($customer);

        $skill_names = $skills->pluck('name')->all();
        $skillSeparated = implode(', ', $skill_names);

        $summary = $openAIService->generateCustomerSummary([
            'full_name' => $customer->full_name,
            'years' => 5,
            'skills' => $skillSeparated
        ]);

        $pdf = Pdf::loadView('documents.template-1', compact('customer', 'skills', 'summary'));

        return $pdf->stream('document-example.pdf');
    }


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
