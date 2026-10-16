{{-- Sample Information --}}
    <section class="lims-form-card">
        <div class="lims-form-card-header">
            <div>
                <p class="lims-form-eyebrow">Sample Details</p>
                <h2 class="lims-form-card-title">Sample Information</h2>
                <p class="lims-form-card-description">
                    Identify the sample classification and preferred retrieval method.
                </p>
            </div>

            <div class="lims-form-step">03</div>
        </div>


        {{-- SAMPLE TYPE --}}
        <div class="lims-field">
            <label class="lims-label">
                Sample Type *
            </label>

            <div class="lims-sample-type-grid">

                <label class="lims-sample-type-card">
                    <input
                        type="radio"
                        name="SamType"
                        value="Fishery Resources / Products"
                        class="lims-radio"
                        required
                    >

                    <div class="lims-sample-type-icon">
                        🐟
                    </div>

                    <div>
                        <span class="lims-option-title">
                            Fishery Resources / Products
                        </span>
                    </div>
                </label>


                <label class="lims-sample-type-card">
                    <input
                        type="radio"
                        name="SamType"
                        value="Water"
                        class="lims-radio"
                        required
                    >

                    <div class="lims-sample-type-icon">
                        💧
                    </div>

                    <div>
                        <span class="lims-option-title">
                            Water
                        </span>
                    </div>
                </label>


                <label class="lims-sample-type-card">
                    <input
                        type="radio"
                        name="SamType"
                        value="Feeds"
                        class="lims-radio"
                        required
                    >

                    <div class="lims-sample-type-icon">
                        ◉
                    </div>

                    <div>
                        <span class="lims-option-title">
                            Feeds
                        </span>
                    </div>
                </label>


                <label class="lims-sample-type-card">
                    <input
                        type="radio"
                        name="SamType"
                        value="Sediments"
                        class="lims-radio"
                        required
                    >

                    <div class="lims-sample-type-icon">
                        ▱
                    </div>

                    <div>
                        <span class="lims-option-title">
                            Sediments
                        </span>
                    </div>
                </label>


                <label class="lims-sample-type-card">
                    <input
                        type="radio"
                        name="SamType"
                        value="Others"
                        class="lims-radio"
                        id="sampleTypeOthers"
                        required
                    >

                    <div class="lims-sample-type-icon">
                        •••
                    </div>

                    <div>
                        <span class="lims-option-title">
                            Others
                        </span>
                    </div>
                </label>

            </div>
        </div>


        {{-- SPECIFY OTHER --}}
        <div
            class="lims-field lims-field-medium hidden"
            id="sampleTypeSpecifyContainer"
        >
            <label class="lims-label">
                Specify Other Sample Type
            </label>

            <input
                type="text"
                name="SamTypeSpecify"
                id="sampleTypeSpecify"
                class="lims-input"
                placeholder="Specify sample type"
            >
        </div>


        <div class="lims-form-divider"></div>


        {{-- SAMPLE RETRIEVAL --}}
        <div class="lims-field">

            <label class="lims-label">
                Sample Retrieval *
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
                        <span class="lims-option-title">
                            For Disposal
                        </span>

                        <span class="lims-option-description">
                            Laboratory will dispose of the sample.
                        </span>
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
                        <span class="lims-option-title">
                            For Retrieval
                        </span>

                        <span class="lims-option-description">
                            Customer will retrieve the sample.
                        </span>
                    </div>
                </label>

            </div>
        </div>
    </section>