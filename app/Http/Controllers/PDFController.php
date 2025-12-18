<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;


class PDFController extends Controller
{
    public function show()
    {

        $customer = auth()->user()->company->customers()->with('skills')->firstOrFail();
        $skills = $customer->skills->map(function ($skill){
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'description' => $skill->description,
                'years' => $skill->pivot->years ?? 0,
                'level' => $skill->pivot->level ?? null,
            ];
        });

        $pdf = Pdf::loadView('pdf.document-example', compact('customer', 'skills'));

        return $pdf->stream('document-example.pdf');
    }
}
