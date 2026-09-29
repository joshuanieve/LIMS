@extends('layouts.app')

@section('title', 'LIMS - Laboratory Request')
@section('PageTitle', 'Request Form')

@section('content')

@vite([
        'resources/css/pages/requestform.css',
        'resources/js/pages/requestform.js'
    ])

{{-- <form
    method="POST"
    action="{{ route('requestform.storeRequestForm') }}"
    class="lims-form"
> --}}
<form
    method="POST"
    id="requestForm"
    class="lims-form"
>
    @csrf
    @include('RequestforAnalysis.partials.tab1-requestor-information')
    @include('RequestforAnalysis.partials.tab2-laboratory-analysis')
    @include('RequestforAnalysis.partials.tab3-sample-information')
    @include('RequestforAnalysis.partials.tab4-submission-information')
    @include('RequestforAnalysis.partials.tab5-general-information')
    @include('RequestforAnalysis.partials.tab6-table')
    @include('RequestforAnalysis.partials.tab7-actions')
    
</form>
@endsection





@section('modals')
    @include('RequestforAnalysis.partials.modal1-analysismodal')
    @include('RequestforAnalysis.partials.modal2-locationmodal')
@endsection



