@extends('layouts.app')

@section('title', 'Reports - LIMS')
@section('PageTitle', 'Reports')


@section('content')

@vite([
        'resources/css/systempage/report.css',
        'resources/js/systempage/report.js'
    ])



<section class="lims-form-card">
    <div class="lims-reports-top">
        <div class="lims-grid-header">
            <p class="lims-form-eyebrow">System</p>
            <h2 class="lims-modal-title">Reports</h2>
            <p class="lims-grid-header-subtitle">Operational metrics across the laboratory pipeline</p>
        </div>

        <div class="lims-reports-controls">
            <div class="lims-reports-period" id="reportsPeriod">
                <button type="button" class="lims-reports-period-button active" data-period="week">This Week</button>
                <button type="button" class="lims-reports-period-button" data-period="month">This Month</button>
                <button type="button" class="lims-reports-period-button" data-period="quarter">This Quarter</button>
            </div>

            <button type="button" id="reportsExportButton" class="lims-grid-outline-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                </svg>
                Export
            </button>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="lims-reports-kpi-grid">
        @foreach ($kpis as $kpi)
            <div class="lims-reports-kpi-card">
                <p class="lims-reports-kpi-label">{{ $kpi['label'] }}</p>
                <p class="lims-reports-kpi-value">{{ $kpi['value'] }}</p>
                <p class="lims-reports-kpi-note {{ $kpi['trend'] }}">{{ $kpi['note'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- PIPELINE FUNNEL --}}
    <section class="lims-reports-panel lims-reports-panel-wide">
        <div class="lims-reports-panel-header">
            <p class="lims-form-eyebrow">Workflow</p>
            <h3 class="lims-modal-section-title">Where This Month's Requests Are Right Now</h3>
        </div>

        <div class="lims-reports-pipeline">
            @foreach ($pipelineStages as $index => $stage)
                <div class="lims-reports-pipeline-stage {{ $stage['count'] === null ? 'lims-reports-pipeline-stage-marker' : '' }}">
                    <p class="lims-reports-pipeline-count">{{ $stage['count'] ?? '—' }}</p>
                    <p class="lims-reports-pipeline-label">{{ $stage['label'] }}</p>
                    <p class="lims-reports-pipeline-hint">{{ $stage['hint'] }}</p>
                </div>

                @if (!$loop->last)
                    <div class="lims-reports-pipeline-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    <div class="lims-reports-bottom">

        {{-- CATEGORY BREAKDOWN --}}
        <section class="lims-reports-panel">
            <div class="lims-reports-panel-header">
                <p class="lims-form-eyebrow">Laboratory Analysis</p>
                <h3 class="lims-modal-section-title">Requests by Category</h3>
            </div>

            <div class="lims-reports-category-list">
                @foreach ($categoryBreakdown as $category)
                    <div class="lims-reports-category-row">
                        <div class="lims-reports-category-head">
                            <span class="lims-grid-category-pill {{ $category['class'] }}">{{ $category['label'] }}</span>
                            <span class="lims-reports-category-count">{{ $category['count'] }} requests</span>
                        </div>
                        <div class="lims-grid-progress-track">
                            <div class="lims-grid-progress-fill {{ $category['class'] === 'chemical' ? '' : 'complete' }}" style="width:{{ $category['percent'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="lims-reports-panel-header" style="margin-top: 1.75rem;">
                <p class="lims-form-eyebrow">Turnaround</p>
                <h3 class="lims-modal-section-title">Average Days by Sample Type</h3>
            </div>

            <div class="lims-reports-bar-list">
                @foreach ($sampleTypeTurnaround as $row)
                    <div class="lims-reports-bar-row">
                        <span class="lims-reports-bar-label">{{ $row['label'] }}</span>
                        <div class="lims-reports-bar-track">
                            <div class="lims-reports-bar-fill" style="width:{{ $maxTurnaroundDays > 0 ? round(($row['days'] / $maxTurnaroundDays) * 100) : 0 }}%"></div>
                        </div>
                        <span class="lims-reports-bar-value">{{ $row['days'] }}d</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ANALYST WORKLOAD --}}
        <section class="lims-reports-panel">
            <div class="lims-reports-panel-header">
                <p class="lims-form-eyebrow">Job Routing &amp; Encoding</p>
                <h3 class="lims-modal-section-title">Analyst Workload</h3>
            </div>

            <div class="lims-reports-analyst-list">
                @foreach ($analystWorkload as $analyst)
                    @php
                        $pct = $analyst['assigned'] > 0
                            ? round(($analyst['completed'] / $analyst['assigned']) * 100)
                            : 0;
                    @endphp
                    <div class="lims-reports-analyst-row">
                        <div class="lims-reports-analyst-head">
                            <span class="lims-reports-analyst-name">{{ $analyst['name'] }}</span>
                            <span class="lims-reports-analyst-count">{{ $analyst['completed'] }}/{{ $analyst['assigned'] }} completed</span>
                        </div>
                        <div class="lims-grid-progress-track">
                            <div class="lims-grid-progress-fill {{ $pct === 100 ? 'complete' : '' }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="lims-reports-bottom">

        {{-- REQUESTS BY DIVISION --}}
        <section class="lims-reports-panel">
            <div class="lims-reports-panel-header">
                <p class="lims-form-eyebrow">Request for Analysis</p>
                <h3 class="lims-modal-section-title">Requests by Division</h3>
            </div>

            <div class="lims-reports-bar-list">
                @foreach ($divisionBreakdown as $row)
                    <div class="lims-reports-bar-row">
                        <span class="lims-reports-bar-label">{{ $row['label'] }}</span>
                        <div class="lims-reports-bar-track">
                            <div class="lims-reports-bar-fill" style="width:{{ $maxDivisionCount > 0 ? round(($row['count'] / $maxDivisionCount) * 100) : 0 }}%"></div>
                        </div>
                        <span class="lims-reports-bar-value">{{ $row['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- TOP REQUESTED PARAMETERS --}}
        <section class="lims-reports-panel">
            <div class="lims-reports-panel-header">
                <p class="lims-form-eyebrow">Analysis List</p>
                <h3 class="lims-modal-section-title">Most Requested Parameters</h3>
            </div>

            <div class="lims-reports-bar-list">
                @foreach ($topParameters as $row)
                    <div class="lims-reports-bar-row">
                        <span class="lims-reports-bar-label">{{ $row['label'] }}</span>
                        <div class="lims-reports-bar-track">
                            <div class="lims-reports-bar-fill" style="width:{{ $maxParameterCount > 0 ? round(($row['count'] / $maxParameterCount) * 100) : 0 }}%"></div>
                        </div>
                        <span class="lims-reports-bar-value">{{ $row['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- OVERDUE REQUESTS --}}
    <section class="lims-reports-panel lims-reports-panel-wide">
        <div class="lims-reports-panel-header">
            <p class="lims-form-eyebrow">Needs Attention</p>
            <h3 class="lims-modal-section-title">Overdue Requests</h3>
        </div>

        @if (count($overdueRequests))
            <div class="lims-reports-overdue-list">
                @foreach ($overdueRequests as $request)
                    <div class="lims-reports-overdue-row">
                        <span class="lims-flat-id">{{ $request['rfaNo'] }}</span>
                        <span class="lims-reports-overdue-customer">{{ $request['customer'] }}</span>
                        <span class="lims-reports-overdue-type">{{ $request['sampleType'] }}</span>
                        <span class="lims-reports-overdue-badge">
                            {{ $request['daysOverdue'] }} day{{ $request['daysOverdue'] === 1 ? '' : 's' }} overdue
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="lims-reports-overdue-empty">Nothing overdue right now.</p>
        @endif
    </section>
</section>

@endsection
