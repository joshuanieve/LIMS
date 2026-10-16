@extends('layouts.app')

@section('title', 'Result for Encoding - LIMS')
@section('PageTitle', 'Result for Encoding')


@section('content')

@vite([
        'resources/css/pages/resultforencoding.css',
        'resources/js/services/fetchAnalysisTable.js',
        'resources/js/pages/resultforencoding.js'
    ])



<section class="lims-form-card">
    <div class="lims-card-grid-wrapper">
        <table id="ResultforEncodingTable" class="lims-card-grid-table">
            <thead>
                <tr>
                    <th>Sample</th>
                </tr>
            </thead>

            <tbody id="ResultforEncodingBody"></tbody>
        </table>
    </div>
</section>

@endsection




@section('modals')
    @include('pages.ResultforEncoding.partials.modal1-results')
@endsection
