<?php

namespace App\Http\Controllers;

class ReportsController extends Controller
{
    public function index()
    {
        // TEMPORARY placeholder data - no reporting/aggregation API exists
        // yet. Shapes mirror what's already tracked elsewhere in the app
        // (Laboratory Records' turnaround/on-time signal, Job Routing's
        // Chemical/Microbiological split, Result for Encoding's per-analyst
        // progress) rather than inventing new metrics from nothing.
        $kpis = [
            ['label' => 'Requests This Month', 'value' => '47', 'note' => '+12% vs last month', 'trend' => 'up'],
            ['label' => 'Average Turnaround', 'value' => '8.4 days', 'note' => 'Target: 8 days', 'trend' => 'flat'],
            ['label' => 'On-Time Rate', 'value' => '86%', 'note' => '34 of 40 released on time', 'trend' => 'up'],
            ['label' => 'Overdue Right Now', 'value' => '3', 'note' => 'Needs attention', 'trend' => 'down'],
        ];

        $categoryBreakdown = [
            ['label' => 'Chemical', 'count' => 29, 'percent' => 62, 'class' => 'chemical'],
            ['label' => 'Microbiological', 'count' => 18, 'percent' => 38, 'class' => 'microbiological'],
        ];

        $analystWorkload = [
            ['name' => 'Ana Reyes', 'assigned' => 14, 'completed' => 12],
            ['name' => 'Mark Lopez', 'assigned' => 11, 'completed' => 7],
            ['name' => 'Joy Santos', 'assigned' => 9, 'completed' => 9],
            ['name' => 'Ramon Villanueva', 'assigned' => 6, 'completed' => 3],
        ];

        $sampleTypeTurnaround = [
            ['label' => 'Water', 'days' => 7.1],
            ['label' => 'Feeds', 'days' => 8.9],
            ['label' => 'Fishery Resources / Products', 'days' => 9.4],
            ['label' => 'Sediments', 'days' => 8.0],
        ];
        $maxTurnaroundDays = max(array_column($sampleTypeTurnaround, 'days'));

        // Pipeline funnel: all 8 workflow steps, shown left to right. Request
        // Submitted and Lab Number Generated are instantaneous (not a queue a
        // request sits in) so they carry no step number and no count. The
        // remaining 5 stages are numbered 1-6 for this funnel specifically
        // (In Progress spans two) and their counts sum to 47 (the "Requests
        // This Month" KPI above).
        $pipelineStages = [
            ['label' => 'Request Submitted', 'count' => null, 'hint' => 'RFA received, reference number issued'],
            ['label' => 'Pending Review', 'count' => 6, 'hint' => 'Step 1 · awaiting Admin review'],
            ['label' => 'Lab Number Generated', 'count' => null, 'hint' => 'Sample numbering'],
            ['label' => 'Routed to Analyst', 'count' => 9, 'hint' => 'Step 2 · parameters assigned'],
            ['label' => 'In Progress', 'count' => 14, 'hint' => 'Steps 3-4 · being encoded'],
            ['label' => 'For Final Review', 'count' => 8, 'hint' => 'Step 5 · Section Chief review'],
            ['label' => 'Released', 'count' => 10, 'hint' => 'Step 6 · report sent to client'],
        ];
        $pipelineTotal = array_sum(array_filter(array_column($pipelineStages, 'count')));

        // Requests by submitting division - same division names already
        // used in Laboratory Records. Also sums to 47.
        $divisionBreakdown = [
            ['label' => 'Fisheries Resource Management', 'count' => 15],
            ['label' => 'Aquaculture Division', 'count' => 12],
            ['label' => 'Post-Harvest Division', 'count' => 11],
            ['label' => 'Island Fisheries Division', 'count' => 9],
        ];
        $maxDivisionCount = max(array_column($divisionBreakdown, 'count'));

        // Most-requested parameters this period - helps with reagent/
        // equipment planning.
        $topParameters = [
            ['label' => 'pH', 'count' => 22],
            ['label' => 'BOD', 'count' => 18],
            ['label' => 'Coliforms, Fecal', 'count' => 16],
            ['label' => 'Moisture', 'count' => 14],
            ['label' => 'Total Plate Count', 'count' => 11],
        ];
        $maxParameterCount = max(array_column($topParameters, 'count'));

        // The specific requests behind the "Overdue Right Now: 3" KPI -
        // actionable, not just a count.
        $overdueRequests = [
            ['rfaNo' => 'Ref. #0416', 'customer' => 'Dela Cruz, Juan P.', 'sampleType' => 'Water', 'daysOverdue' => 3],
            ['rfaNo' => 'Ref. #0417', 'customer' => 'Santos, Maria L.', 'sampleType' => 'Sediments', 'daysOverdue' => 1],
            ['rfaNo' => 'Ref. #0418', 'customer' => 'Reyes, Ana C.', 'sampleType' => 'Feeds', 'daysOverdue' => 2],
        ];

        return view('systempage.Reports.index', compact(
            'kpis',
            'categoryBreakdown',
            'analystWorkload',
            'sampleTypeTurnaround',
            'maxTurnaroundDays',
            'pipelineStages',
            'pipelineTotal',
            'divisionBreakdown',
            'maxDivisionCount',
            'topParameters',
            'maxParameterCount',
            'overdueRequests'
        ));
    }
}
