<?php

namespace App\Helper;

use Illuminate\Support\Facades\Request;

if (! function_exists('getUserIP')) {
    function getUserIP()
    {
        $ip = Request::ip();
        return $ip;
    }
}
