<?php

namespace App\Enums;

enum DocumentCategory: string
{
    case INVOICE = 'invoice';
    case CONTRACT = 'contract';
    case CURRICULUM = 'curriculum';
    case REPORT = 'report';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function defaults(): array
    {
        return match ($this) {
            self::INVOICE => [
                'structure' => [],
                'settings' => [
                    'page_size' => 'A4',
                    'orientation' => 'portrait',
                ],
                'blade_template' => 'documents.invoice',
            ],

            self::CONTRACT => [
                'structure' => [],
                'settings' => [
                    'page_size' => 'A4',
                    'orientation' => 'portrait',
                ],
                'blade_template' => 'documents.contract',
            ],

            self::CURRICULUM => [
                'structure' => [],
                'settings' => [
                    'page_size' => 'A4',
                    'orientation' => 'portrait',
                ],
                'blade_template' => 'documents.pdf.base-template',
            ],

            self::REPORT => [
                'structure' => [],
                'settings' => [
                    'page_size' => 'A4',
                    'orientation' => 'landscape',
                ],
                'blade_template' => null,
            ],
        };
    }
}
