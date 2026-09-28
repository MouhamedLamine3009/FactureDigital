<?php

namespace App\Support;

class Initials
{
    /**
     * Construit les initiales d'un nom complet : première lettre du premier
     * segment (prénom) et première lettre du dernier segment (nom).
     *
     * Un nom unique sur un seul segment utilise ses premières lettres.
     */
    public static function make(?string $name, int $max = 2): string
    {
        $max = max(1, $max);

        $segments = preg_split('/\s+/u', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($segments === []) {
            return '';
        }

        if ($max < 2 || count($segments) < 2) {
            return mb_strtoupper(mb_substr($segments[0], 0, $max, 'UTF-8'));
        }

        $first = self::firstLetter($segments[0]);
        $last = self::firstLetter($segments[count($segments) - 1]);

        if ($first === '') {
            return mb_strtoupper($last);
        }

        if ($last === '') {
            return mb_strtoupper($first);
        }

        return mb_strtoupper($first.$last);
    }

    private static function firstLetter(string $segment): string
    {
        return mb_substr($segment, 0, 1, 'UTF-8');
    }
}
