<?php

namespace App\Utils;

use Ramsey\Uuid\Uuid;

class UidHelper
{
    static function generateUUIDBase36($letter = ''): string
    {
        // Générer un ID unique de 12 caractères avec de 0 à z
        $random = Uuid::uuid4()->toString();
        $random = str_replace('-', '', $random);
        $random = base_convert($random, 16, 36);
        $random = substr($random, 0, 11);
        // add a letter to the start of the string
        $random = $letter . $random;
        return strtoupper($random);
    }
}