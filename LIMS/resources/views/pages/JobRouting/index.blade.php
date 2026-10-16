@extends('layouts.app')

@section('title', 'Job Routing - LIMS')
@section('PageTitle', 'Job Routing')


@section('content')

@vite([
        'resources/css/pages/jobrouting.css',
        'resources/js/services/fetchAnalysisTable.js',
        'resources/js/pages/jobrouting.js'
    ])



<section class="lims-form-card">
    <div class="lims-card-grid-wrapper">
        <table id="JobRoutingTable" class="lims-card-grid-table">
            <thead>
                <tr>
                    <th>Sample</th>
                </tr>
            </thead>

            <tbody id="JobRoutingTableBody"></tbody>
        </table>
    </div>
</section>

@endsection




@section('modals')
    @include('pages.JobRouting.partials.modal1-route')
@endsection
