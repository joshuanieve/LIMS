@extends('layouts.app')

@section('title', 'Dashboard - LIMS')
@section('PageTitle', 'Dashboard')


@section('content')

@vite(['resources/css/pages/dashboard.css'])

{{-- //////////////////////////////// WELCOME + KPIs ////////////////////////////////////////// --}}
<div class="lims-directorchief-hero">
    <div class="lims-welcome-card lims-directorchief-hero-welcome">
        <div class="lims-welcome-content">
            <p class="lims-welcome-label">
                Welcome Back · Director / Division Chief
            </p>
            <h2 class="lims-welcome-title">
                Good day, {{ session('user_name') ?? 'Director' }}.
            </h2>
            <p class="lims-welcome-description">
                Review approved results and release final laboratory reports to clients.
            </p>
        </div>

        <a href="{{ route('reports') }}" class="lims-request-button">
            <span>
                View Full Reports
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="lims-directorchief-hero-kpis">
        @foreach ($kpis as $kpi)
            <div class="lims-dash-kpi-card">
                <p class="lims-dash-kpi-label">{{ $kpi['label'] }}</p>
                <p class="lims-dash-kpi-value">{{ $kpi['value'] }}</p>
                <p class="lims-dash-kpi-note {{ $kpi['trend'] }}">{{ $kpi['note'] }}</p>
            </div>
        @endforeach
    </div>
</div>


{{-- //////////////////////////////// FOR FINAL RELEASE (Step 8) ////////////////////////////////////////// --}}
<section class="lims-panel lims-queue-panel">
    <div class="lims-panel-header">
        <div>
            <p class="lims-panel-eyebrow">Step 8 · Your Queue</p>
            <h2 class="lims-panel-title">For Final Release</h2>
        </div>
        <a href="{{ route('LaboratoryRecords.index') }}" class="lims-panel-link">View all</a>
    </div>

    @if (count($forFinalRelease))
        <div class="lims-queue-list">
            @foreach ($forFinalRelease as $item)
                <div class="lims-queue-row">
                    <div>
                        <p class="lims-queue-row-title">{{ $item['rfaNo'] }} &middot; {{ $item['client'] }}</p>
                        <p class="lims-queue-row-meta">{{ $item['division'] }} &middot; approved {{ $item['approvedDate'] }}</p>
                    </div>
                    <span class="lims-status lims-status-completed">Ready to Release</span>
                </div>
            @endforeach
        </div>
    @else
        <p class="lims-queue-empty">Nothing waiting for final release.</p>
    @endif
</section>


{{-- //////////////////////////////// INSIGHTS ////////////////////////////////////////// --}}
<div class="lims-dashboard-insights lims-dashboard-insights-2col">

    {{-- DIVISION BREAKDOWN --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Request for Analysis</p>
                <h2 class="lims-panel-title">Requests by Division</h2>
            </div>
        </div>

        <div class="lims-dash-bar-list">
            @foreach ($divisionBreakdown as $row)
                <div class="lims-dash-bar-row">
                    <span class="lims-dash-bar-label">{{ $row['label'] }}</span>
                    <div class="lims-dash-bar-track">
                        <div class="lims-dash-bar-fill" style="width:{{ $maxDivisionCount > 0 ? round(($row['count'] / $maxDivisionCount) * 100) : 0 }}%"></div>
                    </div>
                    <span class="lims-dash-bar-value">{{ $row['count'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CATEGORY BREAKDOWN --}}
    <section class="lims-panel lims-insight-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Laboratory Analysis</p>
                <h2 class="lims-panel-title">Requests by Category</h2>
            </div>
            <a href="{{ route('reports') }}" class="lims-panel-link">Full report</a>
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
</div>

@endsection
