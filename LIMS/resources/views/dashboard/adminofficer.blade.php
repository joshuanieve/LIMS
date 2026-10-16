@extends('layouts.app')

@section('title', 'Dashboard - LIMS')
@section('PageTitle', 'Dashboard')


@section('content')

@vite(['resources/css/pages/dashboard.css'])

{{-- //////////////////////////////// WELCOME ////////////////////////////////////////// --}}
<div class="dashboard-panelheader">
    <div class="lims-welcome-card">
        <div class="lims-welcome-content">
            <p class="lims-welcome-label">
                Welcome Back · Administrative Officer
            </p>
            <h2 class="lims-welcome-title">
                Good day, {{ session('user_name') ?? 'Admin Officer' }}.
            </h2>
            <p class="lims-welcome-description">
                Review incoming requests, assign them to a laboratory, and keep the request
                pipeline moving.
            </p>
        </div>

        <a href="{{ route('requestsample.index') }}" class="lims-request-button">
            <span>
                Review Requests
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>


{{-- //////////////////////////////// STATS ////////////////////////////////////////// --}}
<div class="lims-stats-grid">
    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M9 8h2m-5 13h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v15a2 2 0 002 2z" />
            </svg>
        </div>
        <p class="lims-stat-label">Total Requests</p>
        <p class="lims-stat-value">{{ $stats['totalRequests'] }}</p>
        <p class="lims-stat-description">Laboratory service requests</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3h6m-5 0v6l-5 9a2 2 0 001.74 3h10.52A2 2 0 0019 18l-5-9V3M8 15h8" />
            </svg>
        </div>
        <p class="lims-stat-label">Samples</p>
        <p class="lims-stat-value">{{ $stats['totalSamples'] }}</p>
        <p class="lims-stat-description">Registered samples</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3v18h18M7 16v-4m5 4V8m5 8v-7" />
            </svg>
        </div>
        <p class="lims-stat-label">Ongoing Analysis</p>
        <p class="lims-stat-value">{{ $stats['ongoingAnalysis'] }}</p>
        <p class="lims-stat-description">Currently being processed</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="lims-stat-label">Completed</p>
        <p class="lims-stat-value">{{ $stats['completedRequests'] }}</p>
        <p class="lims-stat-description">Completed laboratory requests</p>
    </div>
</div>


{{-- //////////////////////////////// AWAITING REVIEW (Step 2) ////////////////////////////////////////// --}}
<section class="lims-panel lims-queue-panel">
    <div class="lims-panel-header">
        <div>
            <p class="lims-panel-eyebrow">Step 2 · Your Queue</p>
            <h2 class="lims-panel-title">Awaiting Your Review</h2>
        </div>
        <a href="{{ route('requestsample.index') }}" class="lims-panel-link">Review all</a>
    </div>

    @if (count($pendingReview))
        <div class="lims-queue-list">
            @foreach ($pendingReview as $item)
                <div class="lims-queue-row">
                    <div>
                        <p class="lims-queue-row-title">{{ $item['request_no'] }} &middot; {{ $item['client'] }}</p>
                        <p class="lims-queue-row-meta">{{ $item['sampleType'] }} sample &middot; submitted {{ $item['date'] }}</p>
                    </div>
                    <span class="lims-status lims-status-pending">Pending</span>
                </div>
            @endforeach
        </div>
    @else
        <p class="lims-queue-empty">Nothing waiting for review.</p>
    @endif
</section>


{{-- //////////////////////////////// CONTENT 2 ////////////////////////////////////////// --}}
<div class="lims-dashboard-bottom">

    {{-- RECENT REQUESTS --}}
    <section class="lims-panel lims-recent-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Laboratory Requests</p>
                <h2 class="lims-panel-title">Recent Requests</h2>
            </div>
            <a href="{{ route('requestsample.index') }}" class="lims-panel-link">View all</a>
        </div>

        <div class="lims-table-wrapper">
            <table class="lims-dashboard-table">
                <thead>
                    <tr>
                        <th>Request No.</th>
                        <th>Client</th>
                        <th>Samples</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentRequests as $r)
                        <tr>
                            <td>{{ $r['request_no'] }}</td>
                            <td>{{ $r['client'] }}</td>
                            <td>{{ $r['samples'] }}</td>
                            <td>{{ $r['date'] }}</td>
                            <td>
                                <span class="lims-status lims-status-{{ strtolower($r['status']) }}">
                                    {{ $r['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>


    {{-- RECENT ACTIVITY --}}
    <section class="lims-panel lims-quick-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Logs</p>
                <h2 class="lims-panel-title">Recent Activity</h2>
            </div>
            <a href="{{ route('logs') }}" class="lims-panel-link">View all</a>
        </div>

        <div class="lims-table-wrapper">
            @foreach ($recentActivity as $activity)
                <div class="lims-activity-item">
                    <p class="lims-activity-message">{{ $activity['message'] }}</p>
                    <p class="lims-activity-time">{{ $activity['time'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>


{{-- //////////////////////////////// INSIGHTS ////////////////////////////////////////// --}}
<div class="lims-dashboard-insights">

    {{-- CATEGORY BREAKDOWN --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Laboratory Analysis</p>
                <h2 class="lims-panel-title">Requests by Category</h2>
            </div>
        </div>

        <div class="lims-insight-category-list">
            @foreach ($categoryBreakdown as $category)
                <div class="lims-insight-category-row">
                    <div class="lims-insight-category-head">
                        <span class="lims-grid-category-pill {{ $category['class'] }}">{{ $category['label'] }}</span>
                        <span class="lims-insight-category-count">{{ $category['count'] }} requests</span>
                    </div>
                    <div class="lims-grid-progress-track">
                        <div class="lims-grid-progress-fill {{ $category['class'] === 'chemical' ? '' : 'complete' }}" style="width:{{ $category['percent'] }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- NEEDS ATTENTION --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Needs Attention</p>
                <h2 class="lims-panel-title">Overdue Requests</h2>
            </div>
            <a href="{{ route('reports') }}" class="lims-panel-link">View all</a>
        </div>

        @if (count($overdueRequests))
            <div class="lims-insight-overdue-list">
                @foreach ($overdueRequests as $request)
                    <div class="lims-insight-overdue-row">
                        <div>
                            <p class="lims-insight-overdue-rfa">{{ $request['rfaNo'] }}</p>
                            <p class="lims-insight-overdue-customer">{{ $request['customer'] }} &middot; {{ $request['sampleType'] }}</p>
                        </div>
                        <span class="lims-status lims-status-overdue">
                            {{ $request['daysOverdue'] }}d overdue
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="lims-insight-empty">Nothing overdue right now.</p>
        @endif
    </section>

    {{-- QUICK LINKS --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Shortcuts</p>
                <h2 class="lims-panel-title">Quick Links</h2>
            </div>
        </div>

        <div class="lims-insight-quicklink-grid">
            <a href="{{ route('requestsample.index') }}" class="lims-insight-quicklink">
                <i class="bx bx-test-tube"></i>
                <span>Request Sample</span>
            </a>
            <a href="{{ route('jobrouting.index') }}" class="lims-insight-quicklink">
                <i class="bx bx-git-branch"></i>
                <span>Job Routing</span>
            </a>
            <a href="{{ route('LaboratoryRecords.index') }}" class="lims-insight-quicklink">
                <i class="bx bx-folder-open"></i>
                <span>Lab Records</span>
            </a>
            <a href="{{ route('reports') }}" class="lims-insight-quicklink">
                <i class="bx bx-bar-chart-alt-2"></i>
                <span>Reports</span>
            </a>
        </div>
    </section>
</div>

@endsection
