@extends('layouts.app')

@section('title', 'Requests Sample - LIMS')
@section('PageTitle', 'Requests Sample')


@section('content')

@vite([
        // 'resources/css/dashboard.css',
        'resources/css/pages/modal-request.css',
        'resources/js/services/fetchAnalysisTable.js',
        'resources/js/pages/requestsample.js'
    ])



<section class="lims-form-card">
    <div class="lims-list-table-wrapper">
        <table id="requestTable" class="lims-list-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Laboratory Analysis</th>
                    <th>Target Release</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="requestTableBody"></tbody>

        </table>
    </div>
</section>

@endsection




@section('modals')
    @include('pages.RequestSample.partials.modal-request')
@endsection