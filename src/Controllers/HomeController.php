<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\PlanRepository;

class HomeController
{
    private PlanRepository $planRepo;

    public function __construct()
    {
        $this->planRepo = new PlanRepository();
    }

    public function index(Request $request): Response
    {
        $plans = $this->planRepo->allActive();

        return view('home', [
            'title' => 'SaaSify - Enterprise Multi-Tenant Subscription & Billing Engine',
            'plans' => $plans,
        ], null); // Render without admin/tenant layout for high-converting marketing landing page
    }
}