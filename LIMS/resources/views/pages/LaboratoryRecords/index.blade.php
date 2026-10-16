@extends('layouts.app')

@section('title', 'Laboratory Records - LIMS')
@section('PageTitle', 'Laboratory Records')


@section('content')

@vite([
        'resources/css/pages/results.css',
        'resources/js/pages/results.js'
    ])



<section class="lims-form-card">
    <div class="lims-flat-table-wrapper">
        <table id="LaboratoryRecordsTable" class="lims-flat-table">
            <thead>
                <tr>
                    <th>RFA No.</th>
                    <th>Customer</th>
                    <th>Sample Type</th>
                    <th>Lab Numbers</th>
                    <th>Received</th>
                    <th>Released</th>
                    <th>Turnaround</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="LaboratoryRecordsBody">
                
            </tbody>
        </table>
    </div>
</section>

@endsection
