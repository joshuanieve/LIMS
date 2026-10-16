@extends('layouts.app')

@section('title', 'Request Form - LIMS')
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
    @include('pages.RequestforAnalysis.partials.tab1-requestor-information')
    @include('pages.RequestforAnalysis.partials.tab2-laboratory-analysis')
    @include('pages.RequestforAnalysis.partials.tab3-sample-information')
    @include('pages.RequestforAnalysis.partials.tab4-submission-information')
    @include('pages.RequestforAnalysis.partials.tab5-general-information')
    @include('pages.RequestforAnalysis.partials.tab6-table')
    @include('pages.RequestforAnalysis.partials.tab7-actions')
    
</form>
@endsection





@section('modals')
    @include('pages.RequestforAnalysis.partials.modal1-analysismodal')
    @include('pages.RequestforAnalysis.partials.modal2-locationmodal')
@endsection



