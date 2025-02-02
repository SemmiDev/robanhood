<?php

namespace App\Http\Controllers\Helpers;

class JenisKelamin
{
    public static function get($code) {
        if (!$code || $code == "") {
            return "-";
        }

        if ($code == "L") {
            return "Laki-laki";
        }

        return "Perempuan";
    }
}
