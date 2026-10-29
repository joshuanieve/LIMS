@extends('layouts.app')

@section('title', 'Dashboard - LIMS')
@section('PageTitle', 'Dashboard')


@section('content')

@vite(['resources/css/pages/dashboard.css'])

{{-- //////////////////////////////// WELCOME + STATS + TURNAROUND ////////////////////////////////////////// --}}
<div class="lims-analyst-hero">
    <div class="lims-welcome-card lims-analyst-hero-welcome">
        <div class="lims-welcome-content">
            <p class="lims-welcome-label">
                Welcome Back · Analyst
            </p>
            <h2 class="lims-welcome-title">
                Good day, {{ session('user_name') ?? 'Analyst' }}.
            </h2>
            <p class="lims-welcome-description">
                Accept your assigned parameters, conduct analysis, and encode results.
            </p>

            <div class="lims-welcome-badges">
                <span class="lims-welcome-badge">
                    <span class="lims-welcome-badge-dot blue"></span>
                    {{ $stats['pendingAcceptance'] }} parameters waiting for acceptance
                </span>
                <span class="lims-welcome-badge">
                    <span class="lims-welcome-badge-dot amber"></span>
                    {{ $stats['inProgress'] }} parameters in progress
                </span>
            </div>
        </div>

        <a href="{{ route('resultforencoding.index') }}" class="lims-request-button">
            <span>
                Encode Results
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="lims-analyst-hero-stats">
        <div class="lims-stat-card lims-hero-stat-card">
            <div class="lims-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M9 8h2m-5 13h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v15a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="lims-stat-label">Assigned Analysis</p>
            <p class="lims-stat-value">{{ $stats['assigned'] }}</p>
            <p class="lims-stat-description">Total parameters this month</p>
        </div>

        <div class="lims-stat-card lims-hero-stat-card">
            <div class="lims-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="lims-stat-label">Completed</p>
            <p class="lims-stat-value">{{ $stats['completedThisMonth'] }}</p>
            <p class="lims-stat-description">Finished this month</p>
        </div>

        <div class="lims-stat-card lims-hero-stat-card">
            <div class="lims-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="lims-stat-label">Pending Acceptance</p>
            <p class="lims-stat-value">{{ $stats['pendingAcceptance'] }}</p>
            <p class="lims-stat-description">Waiting for your response</p>
        </div>

        <div class="lims-stat-card lims-hero-stat-card">
            <div class="lims-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3v18h18M7 16v-4m5 4V8m5 8v-7" />
                </svg>
            </div>
            <p class="lims-stat-label">In Progress</p>
            <p class="lims-stat-value">{{ $stats['inProgress'] }}</p>
            <p class="lims-stat-description">Being analyzed / encoded</p>
        </div>
    </div>

    <div class="lims-analyst-hero-turnaround">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Performance</p>
                <h2 class="lims-panel-title">My Turnaround</h2>
            </div>
        </div>

        <div class="lims-turnaround-summary">
            <div>
                <p class="lims-turnaround-summary-label">Average &middot; last {{ $turnaroundSummary['periodDays'] }}</p>
                <p class="lims-turnaround-summary-value">{{ $turnaroundSummary['average'] }} <span>days</span></p>
            </div>
            <div class="lims-turnaround-summary-side">
                <p>Target {{ $turnaroundSummary['target'] }} days</p>
                <p>{{ $turnaroundSummary['onTime'] }} of {{ $turnaroundSummary['totalCount'] }} on time</p>
            </div>
        </div>

        <div class="lims-turnaround-list">
            @foreach ($turnaroundEntries as $entry)
                <div class="lims-turnaround-row">
                    <div class="lims-turnaround-row-left">
                        <span class="lims-turnaround-dot {{ $entry['onTime'] ? 'on-time' : 'late' }}"></span>
                        <span>{{ $entry['labNo'] }} &middot; {{ $entry['parameter'] }}</span>
                    </div>
                    <span class="lims-turnaround-row-days">{{ $entry['days'] }}d</span>
                </div>
            @endforeach
        </div>

        <p class="lims-turnaround-footer">Working days, counted 6:00 AM - 6:00 PM</p>
    </div>
</div>


{{-- //////////////////////////////// MY ASSIGNED PARAMETERS + RECENT ACTIVITY ////////////////////////////////////////// --}}
<div class="lims-dashboard-insights lims-dashboard-insights-2col">

    {{-- MY ASSIGNED PARAMETERS (Steps 5-6) --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Steps 5-6 · Your Queue</p>
                <h2 class="lims-panel-title">My Assigned Parameters</h2>
            </div>
            <a href="{{ route('jobrouting.index') }}" class="lims-panel-link">View all</a>
        </div>

        @if (count($myAssignments))
            <div class="lims-queue-list">
                @foreach ($myAssignments as $item)
                    <div class="lims-queue-row">
                        <div>
                            <p class="lims-queue-row-title">{{ $item['parameter'] }} &middot; {{ $item['code'] }}</p>
                            <p class="lims-queue-row-meta">{{ $item['labNo'] }} &middot; {{ $item['sampleType'] }} &middot; assigned {{ $item['assignedDate'] }}</p>
                        </div>
                        <span class="lims-status lims-status-{{ strtolower($item['status']) === 'assigned' ? 'pending' : 'processing' }}">
                            {{ $item['status'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="lims-queue-empty">No parameters assigned right now.</p>
        @endif
    </section>

    {{-- RECENT ACTIVITY --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Logs</p>
                <h2 class="lims-panel-title">My Recent Activity</h2>
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

@endsection
