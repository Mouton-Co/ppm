<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function dashboard()
    {
        switch (auth()->user()->role->landing_page) {
            case 'Projects':
                $route = 'projects.index';
                break;
            case 'Design':
                $route = 'submissions.index';
                break;
            case 'Procurement':
                $route = 'parts.procurement.index';
                break;
            case 'Warehouse':
                $route = 'parts.warehouse.index';
                break;
            case 'Purchase Orders':
                $route = 'orders.index';
                break;
            default:
                $route = 'projects.index';
        }

        return redirect()->route($route);
    }
}
