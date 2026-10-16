{{-- Laboratory Analysis --}}
<section class="lims-form-card">
    <div class="lims-form-card-header">
        <div>
            <p class="lims-form-eyebrow">Laboratory Service</p>

            <h2 class="lims-form-card-title">
                Laboratory Analysis
            </h2>

            <p class="lims-form-card-description">
                Select the type of laboratory analysis required.*
            </p>
        </div>

        <div class="lims-form-step">02</div>
    </div>


    {{-- ANALYSIS OPTIONS --}}
    <div class="lims-option-grid lims-option-grid-2">

        {{-- CHEMICAL --}}
        <label class="lims-option-card">
            <input
                type="radio"
                name="LabAna"
                value="Chemical"
                class="lims-radio"
                required
            >

            <div class="lims-option-icon">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M9 3h6M10 3v6l-5 9a2 2 0 001.8 3h10.4a2 2 0 001.8-3l-5-9V3M8 15h8"
                    />
                </svg>
            </div>

            <div class="lims-option-content">
                <span class="lims-option-title">
                    Chemical
                </span>

                <span class="lims-option-description">
                    Chemical and physico-chemical testing
                </span>
            </div>
        </label>


        {{-- MICROBIOLOGICAL --}}
        <label class="lims-option-card">
            <input
                type="radio"
                name="LabAna"
                value="Microbiological"
                class="lims-radio"
                required
            >

            <div class="lims-option-icon">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                >
                    <ellipse
                        cx="12"
                        cy="8"
                        rx="7"
                        ry="4"
                        stroke-width="1.8"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M5 8v7c0 2 3 4 7 4s7-2 7-4V8"
                    />

                    <circle cx="9" cy="8" r="1" fill="currentColor" />
                    <circle cx="14" cy="7" r="1" fill="currentColor" />
                    <circle cx="13" cy="10" r="1" fill="currentColor" />
                </svg>
            </div>

            <div class="lims-option-content">
                <span class="lims-option-title">
                    Microbiological
                </span>

                <span class="lims-option-description">
                    Microbiological testing and examination
                </span>
            </div>
        </label>

    </div>


    {{-- RELEASE DATE --}}
    <div class="lims-field lims-field-medium">

        <label class="lims-label">
            Undersigned shall comply with the policies of this office
            with regard to the conduct of the laboratory testing.
            The official Report of Analysis for the sample shall be
            released on
        </label>

        <input
            type="date"
            name="DateTimeRelease"
            class="lims-input"
            required
        >

        <label class="lims-label">
            , in accordance with the work schedule.*
        </label>

    </div>
</section>