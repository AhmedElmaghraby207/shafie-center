<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;

abstract class AdminController extends BaseController
{
    function __construct()
    {
        $this->middleware(['admin.auth', 'web'], ['except' => [
            'home',
            'login',
        ]]);
    }
}
