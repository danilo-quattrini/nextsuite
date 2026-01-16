<?php

namespace App\Services\DocumentGeneration;

use App\Jobs\GenerateCustomerDocument;

class RenderFactory
{

    public static function make(string $type)
    {
        return match ($type){
            "pdf" => new PdfRenderer(),
            'docx' => new DocxRenderer(),
            default => throw new \Exception("Unsupported document type: {$type}")
        };
    }
}