<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // TEMPORARY role_id -> dashboard mapping, matching LoginController's $limsRoles
    // and documentation.md §2.4. Not yet confirmed against the external FFAST
    // system's real Role_id values - update both places together if that changes.
    private const ROLE_CUSTOMER = 1;
    private const ROLE_ADMIN_OFFICER = 2;
    private const ROLE_ANALYST = 3;
    private const ROLE_SECTION_CHIEF = 4;
    private const ROLE_DIRECTOR_CHIEF = 5;

    public function index(Request $request)
    {
        // CHECK LOGIN FIRST
        if ($request->session()->get('logged_in') !== true) {
            return redirect()->route('welcome')->with('status', 'Please login first.');
        }

        $roleId = (int) $request->session()->get('role_id');

        $roleId = 1;

        return match ($roleId) {
            self::ROLE_CUSTOMER => $this->customerView($request),
            self::ROLE_ADMIN_OFFICER => $this->adminOfficerView($request),
            self::ROLE_ANALYST => $this->analystView($request),
            self::ROLE_SECTION_CHIEF => $this->sectionChiefView($request),
            self::ROLE_DIRECTOR_CHIEF => $this->directorChiefView($request),
            default => $this->unauthorized($request),
        };
    }

    private function unauthorized(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('welcome')
            ->with('status', 'Unauthorized role.');
    }


    // ============================================================
    // CUSTOMER (Requesting User / Client) - Step 1
    // ============================================================
    private function customerView(Request $request)
    {
        // TEMPORARY placeholder data - scoped to "my" requests once a
        // real per-customer request lookup exists on LIMS_API.
        $stats = [
            'myRequests' => 6,
            'mySamples' => 11,
            'inProgress' => 3,
            'completed' => 3,
        ];

        $myRequests = [
            ['request_no' => 'Ref. #0421', 'samples' => 3, 'date' => 'Oct 14, 2026', 'status' => 'Processing'],
            ['request_no' => 'Ref. #0408', 'samples' => 2, 'date' => 'Oct 02, 2026', 'status' => 'Pending'],
            ['request_no' => 'Ref. #0390', 'samples' => 4, 'date' => 'Sep 20, 2026', 'status' => 'Completed'],
            ['request_no' => 'Ref. #0375', 'samples' => 2, 'date' => 'Sep 08, 2026', 'status' => 'Completed'],
        ];

        return view('dashboard.customer', compact(
            'stats', 'myRequests'
        ));
    }


    // ============================================================
    // ADMIN OFFICER (Administrative Personnel) - Steps 2-3
    // ============================================================
    private function adminOfficerView(Request $request)
    {
        // TEMPORARY placeholder data - no aggregation API exists yet.
        // Mirrors ReportsController's numbers so this dashboard and the
        // Reports page agree on the same mock system state.
        $stats = [
            'totalRequests' => 24,
            'totalSamples' => 58,
            'ongoingAnalysis' => 12,
            'completedRequests' => 38,
        ];

        $pendingReview = [
            ['request_no' => 'Ref. #0422', 'client' => 'Liza Fernandez', 'date' => 'Oct 15, 2026', 'sampleType' => 'Water'],
            ['request_no' => 'Ref. #0420', 'client' => 'Maria Santos', 'date' => 'Oct 14, 2026', 'sampleType' => 'Feeds'],
            ['request_no' => 'Ref. #0419', 'client' => 'Carlos Tan', 'date' => 'Oct 13, 2026', 'sampleType' => 'Sediments'],
        ];

        $recentRequests = [
            ['request_no' => 'Ref. #0421', 'client' => 'Juan Dela Cruz', 'samples' => 3, 'date' => 'Oct 14, 2026', 'status' => 'Processing'],
            ['request_no' => 'Ref. #0420', 'client' => 'Maria Santos', 'samples' => 2, 'date' => 'Oct 14, 2026', 'status' => 'Pending'],
            ['request_no' => 'Ref. #0418', 'client' => 'Ana Reyes', 'samples' => 1, 'date' => 'Oct 12, 2026', 'status' => 'Overdue'],
            ['request_no' => 'Ref. #0410', 'client' => 'Pedro Reyes', 'samples' => 4, 'date' => 'Oct 11, 2026', 'status' => 'Completed'],
            ['request_no' => 'Ref. #0405', 'client' => 'Mark Lopez', 'samples' => 2, 'date' => 'Oct 10, 2026', 'status' => 'Completed'],
        ];

        $recentActivity = [
            ['message' => 'Ref. #0421 submitted by Juan Dela Cruz', 'time' => 'Oct 14, 2026 · 10:42 AM'],
            ['message' => 'Lab Number LAB-2026-0133 generated for Ref. #0420', 'time' => 'Oct 14, 2026 · 9:15 AM'],
            ['message' => 'Parameter pH routed to Ana Reyes', 'time' => 'Oct 13, 2026 · 4:50 PM'],
            ['message' => 'Result encoded for LAB-2026-0128 · Total Plate Count', 'time' => 'Oct 13, 2026 · 2:10 PM'],
            ['message' => 'Ref. #0410 reviewed and approved by Section Chief', 'time' => 'Oct 13, 2026 · 11:05 AM'],
            ['message' => 'Ref. #0405 released to client', 'time' => 'Oct 12, 2026 · 3:30 PM'],
        ];

        $categoryBreakdown = [
            ['label' => 'Chemical', 'count' => 29, 'percent' => 62, 'class' => 'chemical'],
            ['label' => 'Microbiological', 'count' => 18, 'percent' => 38, 'class' => 'microbiological'],
        ];

        $overdueRequests = [
            ['rfaNo' => 'Ref. #0416', 'customer' => 'Dela Cruz, Juan P.', 'sampleType' => 'Water', 'daysOverdue' => 3],
            ['rfaNo' => 'Ref. #0417', 'customer' => 'Santos, Maria L.', 'sampleType' => 'Sediments', 'daysOverdue' => 1],
            ['rfaNo' => 'Ref. #0418', 'customer' => 'Reyes, Ana C.', 'sampleType' => 'Feeds', 'daysOverdue' => 2],
        ];

        return view('dashboard.adminofficer', compact(
            'stats', 'pendingReview', 'recentRequests', 'recentActivity', 'categoryBreakdown', 'overdueRequests'
        ));
    }


    // ============================================================
    // ANALYST - Steps 5-6
    // ============================================================
    private function analystView(Request $request)
    {
        // TEMPORARY placeholder data, scoped to "my" assignments.
        $stats = [
            'assigned' => 14,
            'pendingAcceptance' => 2,
            'inProgress' => 5,
            'completedThisMonth' => 12,
        ];

        $myAssignments = [
            ['parameter' => 'pH', 'code' => 'CH-014', 'labNo' => 'LAB-2026-0133', 'sampleType' => 'Water', 'assignedDate' => 'Oct 14, 2026', 'status' => 'Assigned'],
            ['parameter' => 'BOD', 'code' => 'CH-009', 'labNo' => 'LAB-2026-0129', 'sampleType' => 'Sediments', 'assignedDate' => 'Oct 13, 2026', 'status' => 'Accepted'],
            ['parameter' => 'Total Plate Count', 'code' => 'MB-005', 'labNo' => 'LAB-2026-0128', 'sampleType' => 'Feeds', 'assignedDate' => 'Oct 12, 2026', 'status' => 'Ongoing'],
            ['parameter' => 'Coliforms, Fecal', 'code' => 'MB-002', 'labNo' => 'LAB-2026-0124', 'sampleType' => 'Water', 'assignedDate' => 'Oct 10, 2026', 'status' => 'Ongoing'],
        ];

        $myTurnaround = [
            ['label' => 'My Average', 'days' => 7.6],
            ['label' => 'Lab Target', 'days' => 8.0],
        ];
        $maxTurnaroundDays = max(array_column($myTurnaround, 'days'));

        $recentActivity = [
            ['message' => 'Accepted parameter BOD for LAB-2026-0129', 'time' => 'Oct 13, 2026 · 4:50 PM'],
            ['message' => 'Encoded result for LAB-2026-0121 · pH', 'time' => 'Oct 12, 2026 · 2:10 PM'],
            ['message' => 'Started analysis for LAB-2026-0128 · Total Plate Count', 'time' => 'Oct 12, 2026 · 9:40 AM'],
            ['message' => 'Result for LAB-2026-0115 approved by Section Chief', 'time' => 'Oct 10, 2026 · 11:00 AM'],
        ];

        return view('dashboard.analyst', compact(
            'stats', 'myAssignments', 'myTurnaround', 'maxTurnaroundDays', 'recentActivity'
        ));
    }


    // ============================================================
    // SECTION CHIEF / SUPERVISOR - Steps 4 & 7
    // ============================================================
    private function sectionChiefView(Request $request)
    {
        // TEMPORARY placeholder data.
        $stats = [
            'awaitingAssignment' => 5,
            'awaitingReview' => 8,
            'activeAnalysts' => 4,
            'approvedThisMonth' => 31,
        ];

        $awaitingAssignment = [
            ['labNo' => 'LAB-2026-0134', 'sampleType' => 'Water', 'category' => 'Chemical', 'receivedDate' => 'Oct 15, 2026'],
            ['labNo' => 'LAB-2026-0132', 'sampleType' => 'Feeds', 'category' => 'Microbiological', 'receivedDate' => 'Oct 14, 2026'],
            ['labNo' => 'LAB-2026-0130', 'sampleType' => 'Sediments', 'category' => 'Chemical', 'receivedDate' => 'Oct 13, 2026'],
        ];

        $awaitingReview = [
            ['parameter' => 'pH', 'labNo' => 'LAB-2026-0121', 'analyst' => 'Ana Reyes', 'encodedDate' => 'Oct 14, 2026'],
            ['parameter' => 'Moisture', 'labNo' => 'LAB-2026-0119', 'analyst' => 'Mark Lopez', 'encodedDate' => 'Oct 13, 2026'],
            ['parameter' => 'Coliforms, Fecal', 'labNo' => 'LAB-2026-0115', 'analyst' => 'Joy Santos', 'encodedDate' => 'Oct 12, 2026'],
        ];

        $analystWorkload = [
            ['name' => 'Ana Reyes', 'assigned' => 14, 'completed' => 12],
            ['name' => 'Mark Lopez', 'assigned' => 11, 'completed' => 7],
            ['name' => 'Joy Santos', 'assigned' => 9, 'completed' => 9],
            ['name' => 'Ramon Villanueva', 'assigned' => 6, 'completed' => 3],
        ];

        return view('dashboard.sectionchief', compact(
            'stats', 'awaitingAssignment', 'awaitingReview', 'analystWorkload'
        ));
    }


    // ============================================================
    // DIRECTOR / DIVISION CHIEF - Step 8
    // ============================================================
    private function directorChiefView(Request $request)
    {
        // TEMPORARY placeholder data - mirrors ReportsController's KPIs so
        // this dashboard and the Reports page agree on the same mock state.
        $kpis = [
            ['label' => 'Requests This Month', 'value' => '47', 'note' => '+12% vs last month', 'trend' => 'up'],
            ['label' => 'Average Turnaround', 'value' => '8.4 days', 'note' => 'Target: 8 days', 'trend' => 'flat'],
            ['label' => 'On-Time Rate', 'value' => '86%', 'note' => '34 of 40 released on time', 'trend' => 'up'],
            ['label' => 'Overdue Right Now', 'value' => '3', 'note' => 'Needs attention', 'trend' => 'down'],
        ];

        $forFinalRelease = [
            ['rfaNo' => 'Ref. #0405', 'client' => 'Mark Lopez', 'division' => 'Aquaculture Division', 'approvedDate' => 'Oct 12, 2026'],
            ['rfaNo' => 'Ref. #0398', 'client' => 'Liza Fernandez', 'division' => 'Post-Harvest Division', 'approvedDate' => 'Oct 11, 2026'],
        ];

        $divisionBreakdown = [
            ['label' => 'Fisheries Resource Management', 'count' => 15],
            ['label' => 'Aquaculture Division', 'count' => 12],
            ['label' => 'Post-Harvest Division', 'count' => 11],
            ['label' => 'Island Fisheries Division', 'count' => 9],
        ];
        $maxDivisionCount = max(array_column($divisionBreakdown, 'count'));

        $categoryBreakdown = [
            ['label' => 'Chemical', 'count' => 29, 'percent' => 62, 'class' => 'chemical'],
            ['label' => 'Microbiological', 'count' => 18, 'percent' => 38, 'class' => 'microbiological'],
        ];

        return view('dashboard.directorchief', compact(
            'kpis', 'forFinalRelease', 'divisionBreakdown', 'maxDivisionCount', 'categoryBreakdown'
        ));
    }
}
