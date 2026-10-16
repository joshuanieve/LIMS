@extends('layouts.app')

@section('title', 'Logs - LIMS')
@section('PageTitle', 'Logs')


@section('content')

@vite([
        'resources/css/systempage/log.css',
        'resources/js/systempage/log.js'
    ])

{{-- No audit-log API exists yet - the data is embedded here rather than
     fetched, same "temporary" pattern as Laboratory Records. --}}
<script id="logsData" type="application/json">@json($logs)</script>

<section class="lims-form-card">
    <div class="lims-logs-top">
        <div class="lims-grid-header">
            <p class="lims-form-eyebrow">System</p>
            <h2 class="lims-modal-title">Activity Logs</h2>
            <p class="lims-grid-header-subtitle">Pick an entry to see exactly what changed</p>
        </div>

        <div class="lims-logs-search">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"/>
                <path d="M21 21l-4.3-4.3"/>
            </svg>
            <input type="text" id="logsSearchInput" placeholder="Search user, record, action">
        </div>
    </div>

    <div class="lims-logs-layout">
        <div class="lims-logs-list-panel" id="logsList"></div>
        <div class="lims-logs-detail-panel" id="logsDetail"></div>
    </div>
</section>

@endsection
