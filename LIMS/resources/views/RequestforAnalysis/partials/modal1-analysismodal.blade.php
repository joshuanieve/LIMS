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

                    <div class="lims-analysis-type">
                        <h3 class="lims-analysis-type-title">Others</h3>
                        
                        <div class="lims-analysis-category">
                            <div class="lims-analysis-list">
                                <input
                                    type="text"
                                    name="AnalysisOthers"
                                    id="AnalysisOthers"
                                    class="lims-input"
                                    placeholder="Specify"
                                >
                            </div>
                        </div>
                        
                    </div>
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