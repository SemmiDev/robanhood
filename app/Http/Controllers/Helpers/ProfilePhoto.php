<?php

namespace App\Http\Controllers\Helpers;

class ProfilePhoto
{
    public static function get($image, $name)
    {
        // Daftar variasi warna
        $colors = [
            ['background' => '0D8ABC', 'color' => 'FFFFFF'],
            ['background' => 'F39C12', 'color' => 'FFFFFF'],
            ['background' => 'E74C3C', 'color' => 'FFFFFF'],
            ['background' => '27AE60', 'color' => 'FFFFFF'],
            ['background' => '8E44AD', 'color' => 'FFFFFF'],
            ['background' => '34495E', 'color' => 'FFFFFF'],
            ['background' => '4B5563', 'color' => 'FFFFFF'],
            ['background' => '3B82F6', 'color' => 'FFFFFF'],
            ['background' => 'F3D68A', 'color' => '000000'],
            ['background' => 'A3A3D8', 'color' => '000000'],
            ['background' => 'D3D3D3', 'color' => '000000'],
            ['background' => 'F9A8D4', 'color' => '000000'],
            ['background' => 'F2C2C7', 'color' => '000000'],
            ['background' => 'EABD2E', 'color' => '000000'],
            ['background' => 'A9D700', 'color' => '000000'],
            ['background' => 'F97316', 'color' => '000000'],
        ];

        if (!$image || $image === "") {
            // Encode name to handle special characters and spaces
            $encodedName = urlencode($name);

            // Hash the name and convert it to a numeric value
            $hash = crc32($name); // Using crc32 as an example of hashing

            // Map the hash to one of the colors
            $colorIndex = abs($hash) % count($colors);

            // Return the avatar URL with the selected color
            return "https://ui-avatars.com/api/?background={$colors[$colorIndex]['background']}&color={$colors[$colorIndex]['color']}&name={$encodedName}";
        }

        return asset('storage/' . $image);
    }
}
