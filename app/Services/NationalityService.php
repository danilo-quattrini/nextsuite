<?php

namespace App\Services;

class NationalityService
{
    private function flagEmoji(string $code): string
    {
        // Ensure we only try to build flags for two-letter ISO codes
        $clean = strtoupper(trim($code));
        if (!preg_match('/^[A-Z]{2}$/', $clean)) {
            return '';
        }

        return mb_chr(0x1F1E6 + (ord($clean[0]) - 65))
            . mb_chr(0x1F1E6 + (ord($clean[1]) - 65));
    }

    /**
     * Return an array of nationalities (code, name, flag).
     *
     * @return array<int, array{code:string, name:string, flag:string}>
     */
    public function all(): array
    {
        return cache()->rememberForever('nationalities', function () {
            return collect(countries())
                ->map(function ($country, $code) {
                    $codeStr = (string) $code;
                    return [
                        'code' => $codeStr,
                        'name' => $country['name'] ?? $codeStr,
                        'flag' => $this->flagEmoji($codeStr),
                    ];
                })
                ->sortBy('name')
                ->values()
                ->toArray();
        });
    }
}
