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
                Welcome Back
            </p>
            <h2 class="lims-welcome-title">
                Good day, {{ session('user_name') ?? 'Client' }}.
            </h2>
            <p class="lims-welcome-description">
                Submit a new laboratory request or track the status of your existing samples.
            </p>
        </div>

        <a href="{{ route('requestform.index') }}" class="lims-request-button">
            <span>
                New Laboratory Request
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
        <p class="lims-stat-label">My Requests</p>
        <p class="lims-stat-value">{{ $stats['myRequests'] }}</p>
        <p class="lims-stat-description">Total requests submitted</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3h6m-5 0v6l-5 9a2 2 0 001.74 3h10.52A2 2 0 0019 18l-5-9V3M8 15h8" />
            </svg>
        </div>
        <p class="lims-stat-label">My Samples</p>
        <p class="lims-stat-value">{{ $stats['mySamples'] }}</p>
        <p class="lims-stat-description">Submitted for testing</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3v18h18M7 16v-4m5 4V8m5 8v-7" />
            </svg>
        </div>
        <p class="lims-stat-label">In Progress</p>
        <p class="lims-stat-value">{{ $stats['inProgress'] }}</p>
        <p class="lims-stat-description">Currently being processed</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="lims-stat-label">Completed</p>
        <p class="lims-stat-value">{{ $stats['completed'] }}</p>
        <p class="lims-stat-description">Results released to you</p>
    </div>
</div>


{{-- //////////////////////////////// MY REQUESTS ////////////////////////////////////////// --}}
<section class="lims-panel lims-queue-panel">
    <div class="lims-panel-header">
        <div>
            <p class="lims-panel-eyebrow">Laboratory Requests</p>
            <h2 class="lims-panel-title">My Requests</h2>
        </div>
    </div>

    <div class="lims-table-wrapper">
        <table class="lims-dashboard-table">
            <thead>
                <tr>
                    <th>Request No.</th>
                    <th>Samples</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($myRequests as $r)
                    <tr>
                        <td>{{ $r['request_no'] }}</td>
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

@endsection
