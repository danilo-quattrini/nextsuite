<?php

namespace App\Enums;

enum CategoryType: string
{
    case HEALTH = 'health';
    case BUSINESS = 'business';
    case TECHNOLOGY = 'technology';
    case EDUCATION = 'education';
    case WELLNESS = 'wellness';
    case HUMAN_RESOURCES = 'human_resources';
    case PROFESSIONAL_SERVICES = 'professional_services';
    case FINANCE = 'finance';
    case LOGISTICS = 'logistics';
    case FOOD = 'food';
    case BEAUTY = 'beauty';


    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
