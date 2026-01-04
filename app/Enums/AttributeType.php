<?php

namespace App\Enums;

enum AttributeType: string
{
    case STRING = 'string';
    case NUMBER = 'number';
    case BOOLEAN = 'boolean';
    case DATE = 'date';
    case SELECT = 'select';

    public static function randomValue(): string
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }
}
