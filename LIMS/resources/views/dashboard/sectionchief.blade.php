@extends('layouts.app')

@section('title', 'Dashboard - LIMS')
@section('PageTitle', 'Dashboard')


@section('content')

@vite(['resources/css/pages/dashboard.css'])

{{-- //////////////////////////////// WELCOME + STATS ////////////////////////////////////////// --}}
<div class="lims-sectionchief-hero">
    <div class="lims-welcome-card lims-sectionchief-hero-welcome">
        <div class="lims-welcome-content">
            <p class="lims-welcome-label">
                Welcome Back · Section Chief
            </p>
            <h2 class="lims-welcome-title">
                Good day, {{ session('user_name') ?? 'Section Chief' }}.
            </h2>
            <p class="lims-welcome-description">
                Assign samples to your analysts and review the results they encode.
            </p>

            <div class="lims-welcome-badges">
                <span class="lims-welcome-badge">
                    <span class="lims-welcome-badge-dot blue"></span>
                    {{ $stats['awaitingAssignment'] }} samples need an analyst
                </span>
                <span class="lims-welcome-badge">
                    <span class="lims-welcome-badge-dot amber"></span>
                    {{ $stats['awaitingReview'] }} results waiting for review
                </span>
            </div>
        </div>

        <a href="{{ route('jobrouting.index') }}" class="lims-request-button">
            <span>
                Go to Job Routing
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="lims-sectionchief-hero-stats">
        <div class="lims-stat-card lims-hero-stat-card">
            <div class="lims-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M9 8h2m-5 13h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v15a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="lims-stat-label">Awaiting Assignment</p>
            <p class="lims-stat-value">{{ $stats['awaitingAssignment'] }}</p>
            <p class="lims-stat-description">Samples needing an analyst</p>
        </div>

        <div class="lims-stat-card lims-hero-stat-card">
            <div class="lims-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="lims-stat-label">Results for Review</p>
            <p class="lims-stat-value">{{ $stats['awaitingReview'] }}</p>
            <p class="lims-stat-description">Encoded results to approve</p>
        </div>

        <div class="lims-stat-card lims-hero-stat-card">
            <div class="lims-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-3.13a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
            </div>
            <p class="lims-stat-label">Active Analysts</p>
            <p class="lims-stat-value">{{ $stats['activeAnalysts'] }}</p>
            <p class="lims-stat-description">On your team</p>
        </div>

        <div class="lims-stat-card lims-hero-stat-card">
            <div class="lims-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="lims-stat-label">Approved Samples</p>
            <p class="lims-stat-value">{{ $stats['approvedThisMonth'] }}</p>
            <p class="lims-stat-description">This month</p>
        </div>
    </div>
</div>


{{-- //////////////////////////////// ANALYST WORKLOAD ////////////////////////////////////////// --}}
<section class="lims-panel lims-queue-panel">
    <div class="lims-panel-header">
        <div>
            <p class="lims-panel-eyebrow">Job Routing &amp; Encoding</p>
            <h2 class="lims-panel-title">Analyst Workload</h2>
        </div>
    </div>

    <div class="lims-workload-list">
        @foreach ($analystWorkload as $analyst)
            @php
                $pending = max(0, $analyst['assigned'] - $analyst['completed'] - $analyst['overdue']);
                $pct = $analyst['assigned'] > 0 ? round(($analyst['completed'] / $analyst['assigned']) * 100) : 0;
                $initials = collect(explode(' ', $analyst['name']))->map(fn ($part) => strtoupper($part[0]))->take(2)->implode('');
            @endphp
            <div class="lims-workload-row">
                <span class="lims-workload-avatar">{{ $initials }}</span>
                <span class="lims-workload-name">{{ $analyst['name'] }}</span>

                <div class="lims-workload-track">
                    <div class="lims-workload-fill {{ $pct === 100 ? 'complete' : '' }}" style="width:{{ $pct }}%"></div>
                </div>

                <span class="lims-workload-stats">
                    {{ $analyst['completed'] }}/{{ $analyst['assigned'] }} completed &middot; {{ $pending }} pending
                    @if ($analyst['overdue'] > 0)
                        &middot; <span class="overdue">{{ $analyst['overdue'] }} overdue</span>
                    @endif
                </span>
            </div>
        @endforeach
    </div>
</section>


{{-- //////////////////////////////// AWAITING ASSIGNMENT + FOR YOUR REVIEW ////////////////////////////////////////// --}}
<div class="lims-dashboard-insights lims-dashboard-insights-2col">

    {{-- AWAITING ASSIGNMENT (Step 4) --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Step 4 · Your Queue</p>
                <h2 class="lims-panel-title">Awaiting Parameter Assignment</h2>
            </div>
            <a href="{{ route('jobrouting.index') }}" class="lims-panel-link">Assign now</a>
        </div>

        @if (count($awaitingAssignment))
            <div class="lims-queue-list">
                @foreach ($awaitingAssignment as $item)
                    <div class="lims-queue-row">
                        <div>
                            <p class="lims-queue-row-title">{{ $item['labNo'] }}</p>
                            <p class="lims-queue-row-meta">{{ $item['sampleType'] }} sample &middot; received {{ $item['receivedDate'] }} &middot; {{ $item['analysesCount'] }} analyses</p>
                        </div>
                        <div class="lims-queue-row-pills">
                            @foreach ($item['categories'] as $cat)
                                <span class="lims-grid-category-pill {{ $cat['label'] === 'chemical' ? 'chemical' : 'microbiological' }}">
                                    {{ $cat['count'] }} {{ $cat['label'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="lims-queue-empty">Nothing waiting for parameter assignment.</p>
        @endif
    </section>

    {{-- AWAITING REVIEW (Step 7) --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Step 7 · Your Queue</p>
                <h2 class="lims-panel-title">For Your Review</h2>
            </div>
            <a href="{{ route('resultforencoding.index') }}" class="lims-panel-link">Review now</a>
        </div>

        @if (count($awaitingReview))
            <div class="lims-queue-list">
                @foreach ($awaitingReview as $item)
                    <div class="lims-queue-row">
                        <div>
                            <p class="lims-queue-row-title">{{ $item['parameter'] }} &middot; {{ $item['labNo'] }}</p>
                            <p class="lims-queue-row-meta">Encoded by {{ $item['analyst'] }} on {{ $item['encodedDate'] }}</p>
                        </div>
                        <span class="lims-status lims-status-processing">For review</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="lims-queue-empty">Nothing waiting for your review.</p>
        @endif
    </section>
</div>

@endsection
