<?php

namespace App\Utils;

use Ramsey\Uuid\Uuid;

class UidHelper
{
    static function generateUUIDBase36($letter = '', $levelOfLength = 1): string
    {

        // Générer un ID unique de 12 caractères avec de 0 à z
        $random = Uuid::uuid4()->toString();
        $random = str_replace('-', '', $random);

        switch ($levelOfLength) {
            case 1:
                $random = base_convert($random, 16, 36);
                $random = substr($random, 0, 11);
                $random = $letter . $random;
                $random = strtoupper($random);
                break;
            case 2:
                $random = base_convert($random, 16, 36);
                $random = substr($random, 0, 15);
                $random = $letter . $random;
                $random = strtoupper($random);
                break;
            default:
                break;
        }
        // add a letter to the start of the string

        return $random;
    }
}