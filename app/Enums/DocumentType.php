<?php

namespace App\Enums;

enum DocumentType: string
{
    case PDF = 'pdf';
    case DOCX = 'docx';
    case TXT = 'txt';
    case CSV = 'csv';
    case XLSX = 'xlsx';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
