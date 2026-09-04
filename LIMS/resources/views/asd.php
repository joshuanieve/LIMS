<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LIMS Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/app.css', 'resources/js/requestform.js'])
</head>

<body>

    <div class="min-h-screen bg-green-500">
        <main class="p-6">
            <form method="POST" action="{{ route('requestform.storeRequestForm') }}">
                <input type="text" name="LastName" placeholder="LastName">
                <input type="text" name="FirstName" placeholder="FirstName">
                <input type="text" name="MiddleInitial" placeholder="MiddleInitial">
                <br>
                <input type="text" name="Email" placeholder="emailaddress"><br>
                <input type="text" name="DivisionSection" placeholder="divisionsection"><br><br>
                
                <p>Laboratory Analysis</p>
                <input type="radio" name="LabAna" value="Chemical"> Chemical<br>
                <input type="radio" name="LabAna" value="Biological"> Biological<br>
                <input type="radio" name="LabAna" value="Microbiological"> Microbiological<br>
                <input type="date"  name="DateTimeRelease" placeholder="Datetime"><br><br>

                <p>Sample Type</p>
                <input type="radio" name="SamType" value="Fishery Resources / Products"> Fishery Resources / Products<br>
                <input type="radio" name="SamType" value="Water"> Water<br>
                <input type="radio" name="SamType" value="Feeds"> Feeds<br>
                <input type="radio" name="SamType" value="Sediments"> Sediments<br>
                <input type="radio" name="SamType" value="Others"> Others<br>
                <input type="text"  name="SamTypeSpecify" placeholder="Specify"><br><br>

                <p>Sample Retrieval</p>
                <input type="radio" name="SampRet" value="For Disposal"> Laboratory will dispose the sample<br>
                <input type="radio" name="SampRet" value="For Retrieval"> Customer will retrieve the Sample<br><br>

                <p>Submission Information</p>
                <p>Do the submitted samples have an associated holding time requirement? &nbsp;
                <input type="radio" name="Subinfo1" value="1"> Yes &nbsp; 
                <input type="radio" name="Subinfo1" value="0"> No </p>
                <p>Do the submitted samples contain hazardous component &nbsp;
                <input type="radio" name="Subinfo2" value="1"> Yes &nbsp; 
                <input type="radio" name="Subinfo2" value="0"> No </p>
                <p>If YES, please speicfy: 
                <input type="text"  name="Subinfo2Specify" placeholder="Specify"></p>

                <p>Do the measurements need an associated uncertainty value? &nbsp;
                <input type="radio" name=Subinfo3" value="1"> Yes &nbsp; 
                <input type="radio" name=Subinfo3" value="0"> No </p>
                <p>Do the submitted samples require a statement of confirmity? &nbsp;
                <input type="radio" name=Subinfo4" value="1"> Yes &nbsp; 
                <input type="radio" name=Subinfo4" value="0"> No </p>
                <p>If YES, please speicfy: 
                <input type="text"  name="Subinfo4Specify" placeholder="Specify"></p>

                <p>Special Instruction: </p><textarea placeholder="Instruction" name="Instruction"></textarea>

                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Test Sample</th>
                            <th>Customer Sample Code</th>
                            <th>Date Collected</th>
                            <th>Place Collected</th>
                            <th>Analysis</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="sampleTableBody">
                        <tr>
                            <td><input type="text"              name="samples[0][sample]"               placeholder="asdasdasd"></td>
                            <td><input type="text"              name="samples[0][customer_sample_code]" placeholder="asdasdasd"></td>
                            <td><input type="datetime-local"    name="samples[0][date_collected]"       placeholder="asdasdasd"></td>
                            <td><input type="text"              name="samples[0][place_collected]"      placeholder="asdasdasd"></td>
                            <td><div>
                                <button
                                    type="button"
                                    class="analysis-picker"
                                    data-row="0"
                                >
                                    <span id="analysisDisplay0">Select analysis...</span>
                                </button>

                                <input type="text" name="samples[0][testarray]" id="samplehidden0">
                            </div></td>
                            <td><button type="button" class="btn btn-danger remove-row">
                                    Remove
                                </button></td>
                        </tr>

                    </tbody>
                </table>
                
                <button type="button"
                        id="addRow"
                        class="btn btn-primary mt-4">
                    + Add Sample
                </button>
                <br><br><br>

                <button type="Submit"
                        id="Submit"
                        class="btn btn-primary mt-4">
                    Submit
                </button>

            </form>

        </main>

    </div>


    <div id="analysisModal" class="fixed inset-0 z-50 hidden justify-center overflow-y-auto bg-black/60 backdrop-blur-sm">
        <div class="flex min-h-full items-start justify-center p-4 sm:p-6">
            <div class="my-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-xl bg-white p-6 shadow-xl">

                <div class="mb-5 flex items-center justify-between">
                    <h1 class="text-2xl font-semibold">
                        Select Analysis
                    </h1>
                </div>
{{-- 
                @php
                    $groupedAnalysis = collect($analysisList)
                        ->groupBy('type')
                        ->map(fn ($types) => $types->groupBy('category'));
                @endphp

                @foreach ($groupedAnalysis as $type => $categories)
                    <div class="mb-6">
                        <h2 class="text-lg font-bold">{{ $type }}</h2>

                        @foreach ($categories as $category => $analyses)
                            <div class="ml-6 mt-3">
                                <h3 class="font-semibold">{{ $category }}</h3>

                                <div class="ml-6 mt-2 space-y-2">

                                    @foreach ($analyses as $analysis)
                                        <label class="flex items-start gap-2">
                                            <input
                                                type="checkbox"
                                                class="analysis-checkbox mt-1"
                                                value="{{ $analysis['analysisId'] }}"
                                                data-name="{{ $analysis['analyte'] }}"
                                            >

                                            <div>
                                                {{ $analysis['analyte'] }}

                                                @if (!empty($analysis['method']))
                                                    <span class="text-sm text-gray-500">
                                                        — {{ $analysis['method'] }}
                                                    </span>
                                                @endif
                                            </div>

                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach --}}

                <div class="mt-6 flex items-center justify-between">
                    <span id="selectedCount" class="text-sm text-gray-500">
                        0 tests selected
                    </span>

                    <div class="flex gap-2">
                        <button
                            type="button"
                            id="cancelAnalysis"
                            class="rounded-lg border px-4 py-2"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            id="applyAnalysis"
                            class="rounded-lg bg-purple-700 px-4 py-2 text-white"
                        >
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>