<div id="RequestFormModal" class="lims-modal hidden">
    <div class="lims-modal-container">
        <div class="lims-modal-card lims-request-view-card">
            <div class="lims-modal-header">
                <div>
                    <p class="lims-form-eyebrow">Request for Analysis</p>
                    <h2 class="lims-modal-title">Request Details</h2>
                </div>

                <button
                    type="button"
                    id="closeRequestModal"
                    class="lims-modal-close"
                >
                    &times;
                </button>
            </div>
            {{-- =================================================
                     Request Details
                ================================================== --}}

            <div class="lims-modal-content">
                <section class="lims-modal-section">
                    <div class="lims-view-section-header">
                        <div>
                            <p class="lims-form-eyebrow">Request Information</p>
                            <h3 class="lims-modal-section-title">Requestor Information</h3>
                        </div>
                        <span class="lims-view-step">01</span>
                    </div>

                    <div class="lims-view-grid lims-view-grid-2">
                        <div class="lims-view-field">
                            <span class="lims-view-label">Customer</span>
                            <span id="viewCustomerName" class="lims-view-value">-</span>
                        </div>

                        <div class="lims-view-field">
                            <span class="lims-view-label">Email Address</span>
                            <span id="viewEmailAddress" class="lims-view-value">-</span>
                        </div>

                        <div class="lims-view-field">
                            <span class="lims-view-label">Division / Section</span>
                            <span id="viewDivisionSection" class="lims-view-value">-</span>
                        </div>

                        <div class="lims-view-field">
                            <span class="lims-view-label">Date and Time Submitted</span>
                            <span id="viewDateSubmitted" class="lims-view-value">-</span>
                        </div>
                    </div>
                </section>

                {{-- =================================================
                     Laboratory Service
                ================================================== --}}
                <section class="lims-modal-section">
                    <div class="lims-view-section-header">
                        <div>
                            <p class="lims-form-eyebrow">Laboratory Service</p>
                            <h3 class="lims-modal-section-title">Laboratory Information</h3>
                        </div>
                        <span class="lims-view-step">02</span>
                    </div>

                    <div class="lims-view-grid lims-view-grid-3">
                        <div class="lims-view-field">
                            <span class="lims-view-label">Laboratory Analysis</span>
                            <span id="viewLabAnalysis" class="lims-view-value lims-view-value-highlight">-</span>
                        </div>

                        <div class="lims-view-field">
                            <span class="lims-view-label">Sample Type</span>
                            <span id="viewSampleType" class="lims-view-value">-</span>
                        </div>

                        <div class="lims-view-field">
                            <span class="lims-view-label">Sample Retrieval</span>
                            <span id="viewSampleRetrieval" class="lims-view-value">-</span>
                        </div>
                    </div>

                    <div id="viewSampleTypeSpecifyContainer" class="lims-view-extra hidden">
                        <span class="lims-view-label">Other Sample Type</span>
                        <span id="viewSampleTypeSpecify" class="lims-view-value">-</span>
                    </div>

                    <div class="lims-view-release">
                        <div>
                            <span class="lims-view-label">Expected Report of Analysis Release</span>
                            <span id="viewReleaseDate" class="lims-view-release-date">-</span>
                        </div>
                    </div>
                </section>



                {{-- =================================================
                     SUBMISSION INFORMATION
                ================================================== --}}
                <section class="lims-modal-section">
                    <div class="lims-view-section-header">
                        <div>
                            <p class="lims-form-eyebrow">Additional Requirements</p>
                            <h3 class="lims-modal-section-title">Submission Information</h3>
                        </div>
                        <span class="lims-view-step">03</span>
                    </div>


                    <div class="lims-view-question-list">
                        {{-- 01 --}}
                        <div class="lims-view-question">
                            <div class="lims-view-question-copy">
                                <span class="lims-view-question-number">01</span>
                                <span>
                                    Do the submitted samples have an associated
                                    holding time requirement?
                                </span>
                            </div>
                            <span id="viewSubinfo1" class="lims-view-answer">-</span>
                        </div>


                        {{-- 02 --}}
                        <div class="lims-view-question">
                            <div class="lims-view-question-copy">
                                <span class="lims-view-question-number">02</span>
                                <span>
                                    Do the submitted samples contain hazardous
                                    components?
                                </span>
                            </div>
                            <span id="viewSubinfo2" class="lims-view-answer">-</span>
                        </div>


                        <div id="viewSubinfo2SpecifyContainer" class="lims-view-extra hidden">
                            <span class="lims-view-label">Hazardous Component</span>
                            <span id="viewSubinfo2Specify" class="lims-view-value">-</span>
                        </div>


                        {{-- 03 --}}
                        <div class="lims-view-question">
                            <div class="lims-view-question-copy">
                                <span class="lims-view-question-number">03</span>
                                <span>
                                    Do the measurements need an associated
                                    uncertainty value?
                                </span>
                            </div>
                            <span id="viewSubinfo3" class="lims-view-answer">-</span>
                        </div>


                        {{-- 04 --}}
                        <div class="lims-view-question">
                            <div class="lims-view-question-copy">
                                <span class="lims-view-question-number">04</span>
                                <span>
                                    Do the submitted samples require a
                                    statement of conformity?
                                </span>
                            </div>
                            <span id="viewSubinfo4" class="lims-view-answer">-</span>
                        </div>

                        <div id="viewSubinfo4SpecifyContainer" class="lims-view-extra hidden">
                            <span class="lims-view-label">Conformity Requirement</span>
                            <span id="viewSubinfo4Specify" class="lims-view-value">-</span>
                        </div>
                    </div>


                    {{-- SPECIAL INSTRUCTIONS --}}
                    <div class="lims-view-instruction">
                        <span class="lims-view-label">Special Instructions</span>
                        <p id="viewInstruction">-</p>
                    </div>
                </section>


                 {{-- =================================================
                     TERMS / REMINDER
                ================================================== --}}
                <section class="lims-modal-section">
                    <div class="lims-view-section-header">
                        <div>
                            <p class="lims-form-eyebrow">Terms of Reference</p>
                            <h3 class="lims-modal-section-title">General Information</h3>
                        </div>
                    </div>


                    <div class="lims-view-terms">
                        <div><strong>01</strong>
                            <p>
                                <b>Minimum Sample Quantity</b><br>
                                100 g for fishery resources or 100 mL for
                                environmental water samples intended for
                                microbiological analysis.
                            </p>
                        </div>

                        <div><strong>02</strong>
                            <p>
                                <b>Sample Rejection</b><br>
                                Samples may be rejected if test methods are not
                                applicable or laboratory resources are unavailable.
                            </p>
                        </div>

                        <div><strong>03</strong>
                            <p>
                                <b>Liability Disclaimer</b><br>
                                NFRDI-IRL shall not be held liable for risks
                                associated with the use or interpretation of
                                test results.
                            </p>
                        </div>

                        <div><strong>04</strong>
                            <p>
                                <b>Confidentiality</b><br>
                                Customer information and test results are treated
                                as confidential.
                            </p>
                        </div>

                        <div><strong>05</strong>
                            <p>
                                <b>Turnaround Time</b><br>
                                Reporting time may vary depending on sample
                                condition, method availability, or laboratory
                                workload.
                            </p>
                        </div>
                    </div>
                </section>



                {{-- =================================================
                     SAMPLE LIST
                ================================================== --}}
                <section class="lims-modal-section">
                    <div class="lims-view-section-header">
                        <div>
                            <p class="lims-form-eyebrow">Submitted Samples</p>
                            <h3 class="lims-modal-section-title">Samples for Analysis</h3>
                        </div>

                        <span class="lims-view-step">04</span>
                    </div>

                    <div class="lims-sample-table-wrapper">
                        <table class="lims-sample-table">
                            <thead>
                                <tr>
                                    <th>Laboratory No.</th>
                                    <th>Sample</th>
                                    <th>Customer Sample Code</th>
                                    <th>Date Collected</th>
                                    <th>Place Collected</th>
                                    <th>Analysis</th>
                                </tr>
                            </thead>

                            <tbody id="viewSamplesTableBody">
                                {{-- JS POPULATES ROWS --}}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            {{-- =====================================================
                 FOOTER
            ====================================================== --}}
            <div class="lims-modal-footer">
                <button type="button" id="closeRequestModalFooter" class="lims-modal-cancel">
                    Close
                </button>
            </div>

        </div>

    </div>

</div>

