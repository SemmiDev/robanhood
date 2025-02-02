<?php

namespace App\Http\Controllers\Helpers;

class StatusAktif
{
    public static function get($code)
    {
        if (!$code || $code == "") {
            return "Diblokir";
        }

        if ($code == "1") {
            return "Aktif";
        }

        return "Diblokir";
    }

    public static function getHTML($code)
    {
        $status = self::get($code);
        if ($status == 'Aktif') {
            return "<div class='badge bg-success-subtle fs-12 text-success'>$status</div>";
        }
        return "<div class='badge bg-danger-subtle fs-12 text-danger'>$status</div>";
    }
}
