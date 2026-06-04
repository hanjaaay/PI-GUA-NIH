<?php

namespace App\Http\Controllers;

// Tambahkan use statement untuk BaseController dan traits yang diperlukan
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // <<< INI YANG PENTING

class Controller extends BaseController // <<< DAN INI
{
    // Traits ini biasanya disertakan untuk fungsionalitas otorisasi dan validasi
    use AuthorizesRequests, ValidatesRequests;
}
