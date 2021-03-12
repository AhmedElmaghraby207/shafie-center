<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class HomeController extends AdminController
{

    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function home(Request $request)
    {
        return view('admin.login');
    }

    public function dashboard(Request $request)
    {
        return view('admin.dashboard')->with([]);
    }

}
