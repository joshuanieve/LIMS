@extends('layouts.app')

@section('title', 'Dashboard - LIMS')
@section('PageTitle', 'Job Routing')


@section('content')

@vite([
        // 'resources/css/dashboard.css',
        // 'resources/css/pages/modal-request.css',
        'resources/js/services/fetchAnalysisTable.js',
        'resources/js/pages/jobrouting.js'
    ])



<section class="lims-form-card">
    <div class="lims-sample-table-wrapper">
        <table id="JobRoutingTable" class="lims-sample-table">
            <thead>
                <tr>
                    <th>Laboratory No.</th>
                    <th>Customer</th>
                    <th>Sample Type</th>
                    <th>Laboratory Analysis</th>
                    <th>Target Release</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="JobRoutingTableBody"></tbody>
            
        </table>
    </div>
</section>

@endsection




@section('modals')

@endsection