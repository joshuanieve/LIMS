@extends('layouts.app')

@section('title', 'Laboratory Request - LIMS')
@section('page-title', 'Laboratory Request Form')

@if ($errors->any())
    <div class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-red-300">
        <p class="font-semibold">Please check the following:</p>

        <ul class="mt-2 list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')

@vite([
        'resources/css/requestform.css',
        'resources/js/requestform.js'
    ])

<form
    method="POST"
    action="{{ route('requestform.storeRequestForm') }}"
    class="lims-form"
>
    @csrf

    {{-- =====================================================
         REQUESTOR INFORMATION
    ====================================================== --}}
    <section class="lims-form-card">
        <div class="lims-form-card-header">
            <div>
                <p class="lims-form-eyebrow">Request Information</p>
                <h2 class="lims-form-card-title">Requestor Information</h2>
                <p class="lims-form-card-description">Provide the contact information of the requesting client.</p>
            </div>
            <div class="lims-form-step">01</div>
        </div>


        <div class="lims-form-grid lims-form-grid-3">
            <div class="lims-field">
                <label class="lims-label">Last Name</label>
                <input
                    type="text"
                    name="LastName"
                    class="lims-input"
                    placeholder="Enter last name"
                    disabled
                >
            </div>

            <div class="lims-field">
                <label class="lims-label">First Name</label>
                <input
                    type="text"
                    name="FirstName"
                    class="lims-input"
                    placeholder="Enter first name"
                    disabled
                >
            </div>

            <div class="lims-field">
                <label class="lims-label">Middle Initial</label>
                <input
                    type="text"
                    name="MiddleInitial"
                    class="lims-input"
                    placeholder="M.I."
                    disabled
                >
            </div>
        </div>


        <div class="lims-form-grid lims-form-grid-2">
            <div class="lims-field">
                <label class="lims-label">Email Address</label>
                <input
                    type="email"
                    name="Email"
                    class="lims-input"
                    placeholder="example@email.com"
                    disabled
                >
            </div>

            <div class="lims-field">
                <label class="lims-label">Division / Section</label>
                <input
                    type="text"
                    name="DivisionSection"
                    class="lims-input"
                    placeholder="Enter division or section"
                    disabled
                >
            </div>
        </div>
    </section>


    <section class="lims-form-card">
        <div class="lims-form-card-header">
            <div>
                <p class="lims-form-eyebrow">Laboratory Service</p>
                <h2 class="lims-form-card-title">Laboratory Analysis</h2>
                <p class="lims-form-card-description">
                    Select the type of laboratory analysis required.
                </p>
            </div>
            <div class="lims-form-step">02</div>
        </div>

        <div class="lims-option-grid">
            <label class="lims-option-card">
                <input
                    type="radio"
                    name="LabAna"
                    value="Chemical"
                    class="lims-radio"
                    required
                >
                <div>
                    <span class="lims-option-title">Chemical</span>
                    <span class="lims-option-description">Chemical and physico-chemical testing</span>
                </div>
            </label>

            <label class="lims-option-card">
                <input
                    type="radio"
                    name="LabAna"
                    value="Biological"
                    class="lims-radio"
                    required
                >
                <div>
                    <span class="lims-option-title">Biological</span>
                    <span class="lims-option-description">Biological laboratory examination</span>
                </div>
            </label>

            <label class="lims-option-card">
                <input
                    type="radio"
                    name="LabAna"
                    value="Microbiological"
                    class="lims-radio"
                    required
                >
                <div>
                    <span class="lims-option-title">Microbiological</span>
                    <span class="lims-option-description">Microbiological testing and examination</span>
                </div>
            </label>
        </div>


        <div class="lims-field lims-field-medium">
            <label class="lims-label">Undersigned shall comply with the policies of this office with regard to the conduct of the laboratory testing. The official Report of Analysis for the sample shall be released on</label>
            <input
                type="date"
                name="DateTimeRelease"
                class="lims-input"
                required
            >
            <label class="lims-label">, in accordance with the work schedule.</label>
        </div>
    </section>


    <section class="lims-form-card">
        <div class="lims-form-card-header">
            <div>
                <p class="lims-form-eyebrow">Sample Details</p>
                <h2 class="lims-form-card-title">Sample Information</h2>
                <p class="lims-form-card-description">Identify the sample classification and preferred retrieval method.</p>
            </div>
            <div class="lims-form-step">03</div>
        </div>


        <div class="lims-field">
            <label class="lims-label">Sample Type</label>
            <div class="lims-choice-group">
                <label class="lims-choice">
                    <input
                        type="radio"
                        name="SamType"
                        value="Fishery Resources / Products"
                        class="lims-radio"
                        required
                    >
                    <span>Fishery Resources / Products</span>
                </label>

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="SamType"
                        value="Water"
                        class="lims-radio"
                        required
                    >
                    <span>Water</span>
                </label>

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="SamType"
                        value="Feeds"
                        class="lims-radio"
                        required
                    >
                    <span>Feeds</span>
                </label>

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="SamType"
                        value="Sediments"
                        class="lims-radio"
                        required
                    >
                    <span>Sediments</span>
                </label>

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="SamType"
                        value="Others"
                        class="lims-radio"
                        required
                    >
                    <span>Others</span>
                </label>
            </div>
        </div>


        <div class="lims-field lims-field-medium">
            <label class="lims-label">Specify Other Sample Type</label>
            <input
                type="text"
                name="SamTypeSpecify"
                class="lims-input"
                placeholder="Specify sample type"
            >
        </div>

        <div class="lims-form-divider"></div>

        <div class="lims-field">
            <label class="lims-label">
                Sample Retrieval
            </label>
            <div class="lims-option-grid lims-option-grid-2">
                <label class="lims-option-card">
                    <input
                        type="radio"
                        name="SampRet"
                        value="For Disposal"
                        class="lims-radio"
                        required
                    >
                    <div>
                        <span class="lims-option-title">For Disposal</span>
                        <span class="lims-option-description">Laboratory will dispose of the sample.</span>
                    </div>
                </label>

                <label class="lims-option-card">
                    <input
                        type="radio"
                        name="SampRet"
                        value="For Retrieval"
                        class="lims-radio"
                        required
                    >
                    <div>
                        <span class="lims-option-title">For Retrieval</span>
                        <span class="lims-option-description">Customer will retrieve the sample.</span>
                    </div>
                </label>
            </div>
        </div>
    </section>


    <section class="lims-form-card">
        <div class="lims-form-card-header">
            <div>
                <p class="lims-form-eyebrow">Additional Requirements</p>
                <h2 class="lims-form-card-title">Submission Information</h2>
                <p class="lims-form-card-description">Provide additional information required for sample handling and analysis.</p>
            </div>
            <div class="lims-form-step">04</div>
        </div>


        <div class="lims-question">

            <p class="lims-question-text">
                Do the submitted samples have an associated holding time requirement?
            </p>

            <div class="lims-yes-no">

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="Subinfo1"
                        value="1"
                        class="lims-radio"
                        required
                    >
                    <span>Yes</span>
                </label>

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="Subinfo1"
                        value="0"
                        class="lims-radio"
                        required
                    >
                    <span>No</span>
                </label>

            </div>

        </div>


        <div class="lims-question">

            <p class="lims-question-text">
                Do the submitted samples contain hazardous components?
            </p>

            <div class="lims-yes-no">

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="Subinfo2"
                        value="1"
                        class="lims-radio"
                        required
                    >
                    <span>Yes</span>
                </label>

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="Subinfo2"
                        value="0"
                        class="lims-radio"
                        required
                    >
                    <span>No</span>
                </label>

            </div>

        </div>


        <div class="lims-field lims-field-medium">

            <label class="lims-label">
                If yes, please specify
            </label>

            <input
                type="text"
                name="Subinfo2Specify"
                class="lims-input"
                placeholder="Specify hazardous component"
            >

        </div>


        <div class="lims-question">

            <p class="lims-question-text">
                Do the measurements need an associated uncertainty value?
            </p>

            <div class="lims-yes-no">

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="Subinfo3"
                        value="1"
                        class="lims-radio"
                        required
                    >
                    <span>Yes</span>
                </label>

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="Subinfo3"
                        value="0"
                        class="lims-radio"
                        required
                    >
                    <span>No</span>
                </label>

            </div>

        </div>


        <div class="lims-question">

            <p class="lims-question-text">
                Do the submitted samples require a statement of conformity?
            </p>

            <div class="lims-yes-no">

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="Subinfo4"
                        value="1"
                        class="lims-radio"
                        required
                    >
                    <span>Yes</span>
                </label>

                <label class="lims-choice">
                    <input
                        type="radio"
                        name="Subinfo4"
                        value="0"
                        class="lims-radio"
                        required
                    >
                    <span>No</span>
                </label>

            </div>

        </div>

        <div class="lims-field lims-field-medium">

            <label class="lims-label">
                If yes, please specify
            </label>

            <input
                type="text"
                name="Subinfo4Specify"
                class="lims-input"
                placeholder="Specify conformity requirement"
            >

        </div>


        <div class="lims-field">

            <label class="lims-label">
                Special Instructions
            </label>

            <textarea
                name="Instruction"
                class="lims-textarea"
                placeholder="Enter special handling or analysis instructions..."
            ></textarea>

        </div>

    </section>






    {{-- TABLE --}}
    <section class="lims-form-card">
        <div class="lims-form-card-header">
            <div>
                <p class="lims-form-eyebrow">Samples</p>
                <h2 class="lims-form-card-title">Samples for Analysis</h2>
                <p class="lims-form-card-description">Add each sample included in this laboratory request.</p>
            </div>

            <div class="lims-form-step">05</div>
        </div>

        <div class="lims-sample-table-wrapper">
            <table class="lims-sample-table">
                <thead>
                    <tr>
                        <th>Test Sample</th>
                        <th>Customer Sample Code</th>
                        <th>Date Collected</th>
                        <th>Place Collected</th>
                        <th>Analysis</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="sampleTableBody">
                    <tr>
                        <td>
                            <input
                                type="text"
                                name="samples[0][sample]"
                                class="lims-table-input"
                                placeholder="Test Sample"
                                required
                            >
                        </td>

                        <td>
                            <input
                                type="text"
                                name="samples[0][customer_sample_code]"
                                class="lims-table-input"
                                placeholder="Sample code"
                                required
                            >
                        </td>

                        <td>
                            <input
                                type="datetime-local"
                                name="samples[0][date_collected]"
                                class="lims-table-input"
                                required
                            >
                        </td>

                        <td>
                            <input
                                type="text"
                                name="samples[0][place_collected]"
                                class="lims-table-input"
                                placeholder="Location"
                                required
                            >
                        </td>

                        <td>
                            <button
                                type="button"
                                class="analysis-picker"
                                data-row="0"
                            >
                                <span id="analysisDisplay0">
                                    Select analysis...
                                </span>
                            </button>

                            <input
                                type="hidden"
                                name="samples[0][testarray]"
                                id="samplehidden0"
                                required
                            >
                        </td>

                        <td>
                            <button
                                type="button"
                                class="lims-remove-button remove-row"
                            >
                                Remove
                            </button>
                        </td>

                    </tr>

                </tbody>
            </table>
        </div>

        <button
            type="button"
            id="addRow"
            class="lims-add-sample-button"
        >
            <span>+</span>
            Add Sample
        </button>
    </section>


    <div class="lims-form-actions">
        <button type="button" class="lims-form-cancel">Cancel</button>
        <button type="submit" class="lims-form-submit">Submit Laboratory Request
            <span>→</span>
        </button>
    </div>
</form>


{{-- ANALYSIS MODAL --}}
<div id="analysisModal" class="lims-analysis-modal hidden">
    <div class="lims-analysis-modal-container">
        <div class="lims-analysis-modal-card">
            <div class="lims-modal-header">
                <div>
                    <p class="lims-form-eyebrow">Laboratory Tests</p>
                    <h2 class="lims-modal-title">Select Analysis</h2>
                </div>
            </div>


            <div class="lims-analysis-options">
                @php
                    $groupedAnalysis = collect($analysisList)
                        ->groupBy('type')
                        ->map(fn ($types) => $types->groupBy('category'));
                @endphp

                @foreach ($groupedAnalysis as $type => $categories)
                    <div class="lims-analysis-type">
                        <h3 class="lims-analysis-type-title">{{ $type }}</h3>
                        @foreach ($categories as $category => $analyses)
                            <div class="lims-analysis-category">
                                <h4 class="lims-analysis-category-title">{{ $category }}</h4>
                                <div class="lims-analysis-list">
                                    @foreach ($analyses as $analysis)
                                        <label class="lims-analysis-checkbox">
                                            <input
                                                type="checkbox"
                                                class="analysis-checkbox"
                                                value="{{ $analysis['analysisId'] }}"
                                                data-name="{{ $analysis['analyte'] }}"
                                            >
                                            <div>
                                                <p class="lims-analysis-name">{{ $analysis['analyte'] }}</p>
                                                @if (!empty($analysis['method']))
                                                    <p class="lims-analysis-method">{{ $analysis['method'] }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>


            <div class="lims-modal-footer">
                <span id="selectedCount" class="lims-selected-count">0 tests selected</span>

                <div class="lims-modal-actions">
                    <button type="button" id="cancelAnalysis" class="lims-modal-cancel">Cancel</button>
                    <button type="button" id="applyAnalysis" class="lims-modal-apply">Apply Selection</button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection