<?php

namespace App\Helpers;

class Misc
{
    public static function setupHeaders(): void
    {
        header('Content-Type: image/svg+xml');
        header('Cache-Control: s-maxage=1');
    }
}
