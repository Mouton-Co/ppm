<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function designer()
    {
        return view('dashboards.designer-dashboard');
    }
}
