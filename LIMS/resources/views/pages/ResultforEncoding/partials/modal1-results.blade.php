    {{-- RESULT FOR ENCODING MODAL --}}
<div id="ResultsModal" class="lims-modal hidden">
    <div class="lims-modal-container">
        <div class="lims-modal-card lims-results-card">
            <div class="lims-modal-header">
                <div>
                    <p class="lims-form-eyebrow">Result for Encoding</p>
                    <h2 class="lims-modal-title">Encode Test Results</h2>
                </div>

                <button
                    type="button"
                    id="closeResultsModalHeader"
                    class="lims-modal-close"
                >
                    &times;
                </button>
            </div>

            <div class="lims-modal-content">
                <div class="lims-results-table-wrapper">
                    <table class="lims-results-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Parameter</th>
                                <th>Method</th>
                                <th>Unit</th>
                                <th>Result</th>
                                <th>Analyst</th>
                                <th class="lims-admin-only">Action</th>
                            </tr>
                        </thead>

                        <tbody id="resultsTableBody">
                            {{-- JS (resultforencoding.js renderResultsTable()) POPULATES ONE ROW PER LAB WORK --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FOOTER LEFT AS-IS: reuses the existing global .lims-form-cancel / .lims-form-submit --}}
            <div class="lims-modal-footer">
                <div class="lims-modal-actions">
                    <button type="button" id="CloseResultsModal" class="lims-form-cancel">Cancel</button>
                    <button type="button" id="saveResultsButton" class="lims-form-submit">Save Results
                        <span>→</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
