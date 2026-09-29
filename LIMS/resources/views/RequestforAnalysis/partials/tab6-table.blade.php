{{-- TABLE --}}
    <section class="lims-form-card">
        <div class="lims-form-card-header">
            <div>
                <p class="lims-form-eyebrow">Samples</p>
                <h2 class="lims-form-card-title">Samples for Analysis *</h2>
                <p class="lims-form-card-description">Add each sample included in this laboratory request.<br>
                Note: Standard deviation is included in the results, while measurement uncertainty can be provided upon request. 
                Standard deviation indicates how much the results vary from the average, and measurement uncertainty estimates the
                range in which the true value may lie.<br>
                A statement of conformity is included when requested, indicating whether the results meet specified requirements or criteria.
                </p>
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
                            <button
                                type="button"
                                class="location-picker"
                                data-row="0"
                            >
                                <span id="locationDisplay0">
                                    Select Location...
                                </span>
                            </button>

                            <input
                                type="hidden"
                                name="samples[0][place_collected]"
                                class="lims-table-input"
                                placeholder="Location"
                                id="locationhidden0"
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
                                    Select Analysis...
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
                            <button type="button" class="noselect lims-button-remove remove-row"><span class="text">Remove</span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path></svg></span></button>
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