    {{-- JOB ROUTING SLIP MODAL --}}
<div id="RouteModal" class="lims-modal hidden">
    <div class="lims-modal-container">
        <div class="lims-modal-card">
            <div class="lims-modal-header">
                <div>
                    <p class="lims-form-eyebrow">Job Routing</p>
                    <h2 class="lims-modal-title">Job Routing Slip</h2>
                </div>

                <button
                    type="button"
                    id="closeRouteModalHeader"
                    class="lims-modal-close"
                >
                    &times;
                </button>
            </div>

            <div class="lims-route-sample-info">
                <div class="lims-route-info-field">
                    <span class="lims-route-info-label">Laboratory No.</span>
                    <span id="routeLaboratoryNumber" class="lims-route-info-value">-</span>
                </div>

                <div class="lims-route-info-field">
                    <span class="lims-route-info-label">Sample</span>
                    <span id="routeSample" class="lims-route-info-value">-</span>
                </div>
            </div>

            <div class="lims-analysis-options">


            </div>


            <div class="lims-modal-footer">
                <div class="lims-modal-actions">
                    <button type="button" id="CloseRouteModal" class="lims-form-cancel">Cancel</button>
                    <button type="submit" id="ApplyRouteModal" class="lims-form-submit">Apply
                        <span>→</span>
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>
