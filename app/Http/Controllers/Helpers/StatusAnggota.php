<?php

namespace App\Http\Controllers\Helpers;

class StatusAnggota
{
    public static function get($code)
    {
        if (!$code || $code == "") {
            return "Menunggu Persetujuan";
        }

        if ($code == "1") {
            return "Diterima";
        }

        return "Menunggu Persetujuan";
    }

    public static function getHTML($code)
    {
        $status = self::get($code);
        if ($status == 'Diterima') {
            return "<div class='badge bg-success-subtle fs-12 text-success'>$status</div>";
        }
        return "<div class='badge bg-warning-subtle fs-12 text-warning'>$status</div>";
    }
}
