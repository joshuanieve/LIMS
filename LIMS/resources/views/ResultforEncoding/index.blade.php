@extends('layouts.app')

@section('title', 'Dashboard - LIMS')
@section('PageTitle', 'Result for Encoding')


@section('content')

@vite([
        // 'resources/css/pages/jobrouting.css',
        'resources/js/services/fetchAnalysisTable.js',
        'resources/js/pages/resultforencoding.js'
    ])



<section class="lims-form-card">
    <div class="lims-sample-table-wrapper">
        <table id="ResultforEncodingTable" class="lims-sample-table">
            <thead>
                <tr>
                    <th>Laboratory No.</th>
                    <th>Sample</th>
                    <th>Target Release</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="ResultforEncodingBody"></tbody>

        </table>
    </div>
</section>

@endsection




@section('modals')

@endsection