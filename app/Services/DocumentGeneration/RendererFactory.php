<?php

namespace App\Services\DocumentGeneration;

class RendererFactory
{
    public static function make(string $type): RendererInterface
    {
        return match ($type){
            'pdf' => new PdfRenderer(),
            'docx' => new DocxRenderer(),
            default => throw new \InvalidArgumentException("Unsupported renderer [$type]"),
        };
    }
}