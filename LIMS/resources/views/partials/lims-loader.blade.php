{{-- =========================================================
     LIMS DNA LOADER
     File: resources/views/partials/lims-loader.blade.php
     Included once in layouts/app.blade.php, right before </body>.
     ========================================================= --}}
<div id="limsLoader" class="lims-loader" role="status" aria-live="polite" aria-hidden="true">
    <div class="lims-loader-card">
        <svg class="lims-loader-art" viewBox="0 0 120 120" aria-hidden="true">
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="12" y1="36" x2="12" y2="84"/>
                <circle class="lims-dna-bottom" cx="12" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="12" cy="60" r="5"/>
            </g>
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="24" y1="36" x2="24" y2="84"/>
                <circle class="lims-dna-bottom" cx="24" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="24" cy="60" r="5"/>
            </g>
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="36" y1="36" x2="36" y2="84"/>
                <circle class="lims-dna-bottom" cx="36" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="36" cy="60" r="5"/>
            </g>
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="48" y1="36" x2="48" y2="84"/>
                <circle class="lims-dna-bottom" cx="48" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="48" cy="60" r="5"/>
            </g>
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="60" y1="36" x2="60" y2="84"/>
                <circle class="lims-dna-bottom" cx="60" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="60" cy="60" r="5"/>
            </g>
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="72" y1="36" x2="72" y2="84"/>
                <circle class="lims-dna-bottom" cx="72" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="72" cy="60" r="5"/>
            </g>
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="84" y1="36" x2="84" y2="84"/>
                <circle class="lims-dna-bottom" cx="84" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="84" cy="60" r="5"/>
            </g>
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="96" y1="36" x2="96" y2="84"/>
                <circle class="lims-dna-bottom" cx="96" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="96" cy="60" r="5"/>
            </g>
            <g class="lims-dna-pair">
                <line class="lims-dna-rung" x1="108" y1="36" x2="108" y2="84"/>
                <circle class="lims-dna-bottom" cx="108" cy="60" r="5"/>
                <circle class="lims-dna-top" cx="108" cy="60" r="5"/>
            </g>
        </svg>

        <p class="lims-loader-text" id="limsLoaderText">Processing request&hellip;</p>
        <p class="lims-loader-sub">Please wait a moment</p>
    </div>
</div>
