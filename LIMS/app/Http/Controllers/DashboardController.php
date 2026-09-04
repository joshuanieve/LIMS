<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Temporary dashboard data for frontend development
        $stats = [
            'totalRequests' => 24,
            'totalSamples' => 58,
            'ongoingAnalysis' => 12,
            'completedRequests' => 38,
        ];

        $recentRequests = [
            [
                'request_no' => 'LIMS-2026-001',
                'client' => 'Juan Dela Cruz',
                'samples' => 3,
                'date' => 'Sep 01, 2026',
                'status' => 'Processing',
            ],
            [
                'request_no' => 'LIMS-2026-002',
                'client' => 'Maria Santos',
                'samples' => 2,
                'date' => 'Aug 31, 2026',
                'status' => 'Pending',
            ],
            [
                'request_no' => 'LIMS-2026-003',
                'client' => 'Pedro Reyes',
                'samples' => 4,
                'date' => 'Aug 30, 2026',
                'status' => 'Completed',
            ],
        ];

        return view('dashboard', compact(
            'stats',
            'recentRequests'
        ));
    }
}