<?php

namespace App\Helpers;

class HashHelper
{
    public static function generate(int $groups = 5): string
    {
        $parts = [];

        for ($i = 0; $i < $groups; $i++) {
            $parts[] = strtoupper(str_pad(dechex(random_int(0, 255)), 2, '0', STR_PAD_LEFT));
        }

        return implode('-', $parts);
    }
}