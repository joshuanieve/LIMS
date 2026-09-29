<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // CHECK LOGIN FIRST
        if ($request->session()->get('logged_in') !== true) {
            return redirect()->route('welcome')->with('status', 'Please login first.');}

        $roleId = (int) $request->session()->get('role_id');
        // TEMPORARY DASHBOARD DATA
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


        // ROLE-BASED DASHBOARD
        if ($roleId === 1) {
            return $this->adminView(
                $request,
                $stats,
                $recentRequests
            );
        }

        if ($roleId === 2) {
            return $this->userView(
                $request,
                $stats,
                $recentRequests
            );
        }


        // UNKNOWN ROLE
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('welcome')
            ->with('status', 'Unauthorized role.');
    }


    private function adminView(
        Request $request,
        array $stats,
        array $recentRequests
    ) {
        return view('dashboard.admin', compact(
            'stats',
            'recentRequests'
        ));
    }


    private function userView(
        Request $request,
        array $stats,
        array $recentRequests
    ) {
        return view('dashboard.user', compact(
            'stats',
            'recentRequests'
        ));
    }


    
}