{{-- LOCATION MODAL --}}
<div id="locationModal" class="lims-analysis-modal hidden">
    <div class="lims-analysis-modal-container">
        <div class="lims-analysis-modal-card">
            <div class="lims-modal-header">
                <div class="flex items-center gap-2">

                    <p class="lims-form-eyebrow m-0">
                        Location
                    </p>

                    <div class="group relative inline-flex items-center">

                        <!-- INFO ICON -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 cursor-help text-[var(--secondary)]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 11v5" />
                            <path d="M12 8h.01" />
                        </svg>

                        <!-- TOOLTIP -->
                        <div class="tooltip-description">
                            Location data is provided by the free Philippine Standard Geographic Code (PSGC) API at psgc.gitlab.io/api

                            <div
                                class="
                                    absolute
                                    left-1/2
                                    top-full
                                    -translate-x-1/2
                                    border-4
                                    border-transparent
                                    border-t-[var(--primary)]
                                "
                            ></div>
                        </div>

                    </div>

                </div>
            </div>


            <div class="lims-analysis-options">
                <div class="lims-location-grid">
                    {{-- REGION --}}
                    <div class="lims-field">
                        <label for="region" class="lims-label">Region *</label>

                        <select id="region" name="Region" class="lims-select" required>
                            <option value="" selected disabled>Select Region</option>
                        </select>
                    </div>

                    {{-- PROVINCE --}}
                    <div class="lims-field">
                        <label for="province" class="lims-label">Province *</label>

                        <select id="province" name="Province" class="lims-select" required>
                            <option value="" selected disabled>
                                Select Province
                            </option>
                        </select>
                    </div>


                    {{-- CITY / MUNICIPALITY --}}
                    <div class="lims-field">
                        <label for="municipality" class="lims-label">City / Municipality *</label>

                        <select id="municipality" name="Municipality" class="lims-select" required>
                            <option value="" selected disabled>
                                Select City / Municipality
                            </option>
                        </select>
                    </div>

                </div>
            </div>


            <div class="lims-modal-footer w-full flex">
                <div class="lims-modal-actions ml-auto flex items-center gap-3">
                    <button type="button" id="cancelLocation" class="lims-modal-cancel">Cancel</button>
                    <button type="button" id="applyLocation" class="lims-modal-apply">Apply Selection</button>
                </div>
            </div>

        </div>
    </div>
</div>