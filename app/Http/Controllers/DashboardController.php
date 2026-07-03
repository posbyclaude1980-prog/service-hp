<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function index(Request $request): View
    {
        $data = $this->dashboardService->getDashboardData();

        return view('dashboard.index', [
            'stats' => $data['stats'],
            'recentOrders' => $data['recentOrders'],
            'charts' => $data['charts'],
        ]);
    }
}
