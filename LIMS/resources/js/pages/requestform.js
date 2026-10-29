
//////////////////////// ANALYSIS ONCLICK POP UP //////////////////////////////
let rowIndex = 1;

// ADD ROW FROM TABLE
    document.getElementById('addRow').addEventListener('click', function () {
        const tbody = document.getElementById('sampleTableBody');
        const row = document.createElement('tr');

        row.innerHTML = `
                <td><input
                        type="text"
                        name="samples[${rowIndex}][sample]"
                        class="lims-table-input"
                        placeholder="Test Sample"
                        required
                    >
                </td>

                <td><input
                        type="text"
                        name="samples[${rowIndex}][customer_sample_code]"
                        class="lims-table-input"
                        placeholder="Sample code"
                        required
                    >
                </td>

                <td><input
                        type="datetime-local"
                        name="samples[${rowIndex}][date_collected]"
                        class="lims-table-input"
                    >
                </td>

                <td>
                    <button
                        type="button"
                        class="location-picker"
                        data-row="${rowIndex}"
                    >
                        <span id="locationDisplay${rowIndex}">
                            Select Location...
                        </span>
                    </button>

                    <input
                        type="hidden"
                        name="samples[${rowIndex}][place_collected]"
                        class="lims-table-input"
                        placeholder="Location"
                        id="locationhidden${rowIndex}"
                        required
                    >

                    <input
                        type="hidden"
                        name="samples[${rowIndex}][place_code]"
                        class="lims-table-input"
                        placeholder="Location"
                        id="locationcodehidden${rowIndex}"
                        required
                    >
                </td>

                <td>
                    <button
                        type="button"
                        class="analysis-picker"
                        data-row="${rowIndex}"
                    >
                        <span id="analysisDisplay${rowIndex}">
                            Select analysis...
                        </span>
                    </button>
                    
                    <input
                        type="hidden"
                        name="samples[${rowIndex}][testarray]"
                        id="samplehidden${rowIndex}"
                    >

                    <input
                        type="hidden"
                        name="samples[${rowIndex}][testothers]"
                        id="samplespecifyhidden${rowIndex}"
                    >
                </td>

                <td>
                    <button type="button" class="noselect lims-button-remove remove-row"><span class="text">Remove</span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path></svg></span></button>
                </td>
            `;

        tbody.appendChild(row);
        rowIndex++;

        // Wait until layout updates, then resize Vanta
        setTimeout(() => {
            if (window.vantaEffect) {
                window.vantaEffect.resize();
            }
        }, 250);
    });

// REMOVE ROW FROM TABLE
    document.getElementById('sampleTableBody')
    .addEventListener('click', function (event) {
        const removeButton = event.target.closest('.remove-row');
        if (!removeButton) return;
        removeButton.closest('tr').remove();
    });

// JS for Analysis Form
let activeAnalysisRow = null;
    // OPEN MODAL FOR ANY ROW
    const analysisModal = document.getElementById('analysisModal');
    document.addEventListener('click', function (event) {
        const analysisButton = event.target.closest('.analysis-picker');
        if (!analysisButton) {
            return;
        }
        // Clear Checkboxes
        document.getElementById('selectedCount').textContent =
            `0 tests selected`;
        document.getElementById('AnalysisOthers').value = "";
        document.querySelectorAll('.analysis-checkbox').forEach(cb => cb.checked = false);
        const row_id = analysisButton.dataset.row;
        activeAnalysisRow = row_id;

        load_checkboxes();
        filterAnalysisByLabType();

        // Show the Tab
        analysisModal.classList.remove('hidden');
        analysisModal.classList.add('flex');

        analysisModal.classList.remove('lims-fade-in');
        void analysisModal.offsetWidth;
        analysisModal.classList.add('lims-fade-in');
    });

    // FILTER TESTS IN THE ANALYSIS MODAL BY THE SELECTED LABORATORY
    // (Chemical / Microbiological, chosen in tab2-laboratory-analysis).
    // /api/AnalysisList's `type` values ("Chemical Analysis", "Biological
    // Analysis") don't match the LabAna radio values exactly, so match by
    // keyword instead of an exact string compare.
    const LAB_TYPE_KEYWORDS = {
        chemical: 'chemical',
        microbiological: 'biological',
    };

    function getSelectedLabAnalysis() {
        const selected = document.querySelector('input[name="LabAna"]:checked');
        return selected ? selected.value : null;
    }

    function filterAnalysisByLabType() {
        const selectedType = getSelectedLabAnalysis();
        const keyword = selectedType
            ? LAB_TYPE_KEYWORDS[selectedType.trim().toLowerCase()]
                ?? selectedType.trim().toLowerCase()
            : null;

        document.querySelectorAll('.lims-analysis-type[data-analysis-type]').forEach(section => {
            const matches = keyword !== null &&
                section.dataset.analysisType.toLowerCase().includes(keyword);

            section.classList.toggle('hidden', !matches);

            if (!matches) {
                section.querySelectorAll('.analysis-checkbox:checked').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        });

        document.getElementById('selectedCount').textContent =
            `${document.querySelectorAll('.analysis-checkbox:checked').length} tests selected`;
    }

    document.querySelectorAll('input[name="LabAna"]').forEach(radio => {
        radio.addEventListener('change', filterAnalysisByLabType);
    });

    function load_checkboxes(){
        
        const hidden_inputvalue = document.getElementById(`samplehidden${activeAnalysisRow}`).value;
        if (hidden_inputvalue !== '') {
            const selectedIds = hidden_inputvalue
            .split(',')
            .map(id => id.trim())
            .filter(id => id !== '');

            // Check matching checkboxes
            document.querySelectorAll('.analysis-checkbox').forEach(function (checkbox) {
                checkbox.checked = selectedIds.includes(checkbox.value);
            });

            // Update selected count
            document.getElementById('selectedCount').textContent =
                `${selectedIds.length} tests selected`;
        }
        
    }

    // Change the indicator "0 tests Selected"
    document.addEventListener('change', function (event) {
        if (!event.target.classList.contains('analysis-checkbox')) {
            return;
        }

        const checkedCount = document.querySelectorAll(
            '.analysis-checkbox:checked'
        ).length;

        document.getElementById('selectedCount').textContent =
            `${checkedCount} tests selected`;
    });

    const ApplyButton = document.getElementById('applyAnalysis');
    ApplyButton.addEventListener('click', function () {

        const checkedCheckboxes = document.querySelectorAll('input.analysis-checkbox:checked');

        const ids = [];
        const analytes = [];

        checkedCheckboxes.forEach(function (checkbox) {
            ids.push(checkbox.value);
            analytes.push(checkbox.dataset.name);
        });

        // "Others" text from the modal
        const otherAnalysis = document.getElementById('AnalysisOthers').value.trim();

        // hidden inputs that get submitted
        document.getElementById(`samplehidden${activeAnalysisRow}`).value = ids.join(',');           // "1,2,3,4"
        document.getElementById(`samplespecifyhidden${activeAnalysisRow}`).value = otherAnalysis;    // "Lead, Mercury" or ""

        // what the user sees in the row
        const displayItems = [...analytes];
        if (otherAnalysis !== '') {
            displayItems.push(`Others: ${otherAnalysis}`);
        }
        document.getElementById(`analysisDisplay${activeAnalysisRow}`).textContent = displayItems.join(', ');

        closeAnalysisModal();
    });

    // Close Analysis Tab
    function closeAnalysisModal() {
        analysisModal.classList.remove('lims-fade-in');
        analysisModal.classList.add('lims-fade-out');

        setTimeout(() => {
            analysisModal.classList.remove('flex', 'lims-fade-out');
            analysisModal.classList.add('hidden');
        }, 200);
    }
    cancelAnalysis.addEventListener('click', closeAnalysisModal);








const submitButton = document.getElementById('SubmitButton');
const cancelButton = document.getElementById('CancelButton');

submitButton.addEventListener('click', function (event) {
    event.preventDefault();
    CreateRequest();
});
cancelButton.addEventListener('click', function (event) {
    window.location.reload();
});

async function CreateRequest() {
    showLoading('Submitting your request…');

    const requestForm = document.getElementById('requestForm');
    const formData = new FormData(requestForm);

    let response;
    try {
        response = await fetch('/requestform', {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        });
    } catch (error) {
        hideLoading();
        console.error('Request Form Error:', error);
        window.swal_error('Submission Failed', 'Unable to reach the server. Please try again.');
        return;
    }

    hideLoading();   // the request is done, success or not

    // read the body safely, even if it isn't JSON (e.g. a dd() page or a crash page)
    const text = await response.text();
    let result;
    try {
        result = JSON.parse(text);
    } catch {
        console.error('Non-JSON response:', text);
        window.swal_error('Submission Failed', 'The server returned an unexpected response.');
        return;
    }

    // LARAVEL VALIDATION ERROR
    if (response.status === 422) {
        const firstError = Object.values(result.errors)[0][0];
        window.swal_error('Incomplete Information', firstError);
        return;
    }

    // OTHER SERVER / API ERROR
    if (!response.ok) {
        window.swal_error('Submission Failed', result.message || 'Unable to submit laboratory request.');
        return;
    }

    // SUCCESS
    await window.swal_success('Request Submitted', result.message);
    document.getElementById('pagebody')?.scrollTo({ top: 0, behavior: 'smooth' });
    window.location.reload();
    
}


// if yes specify hiding

function setupConditionalField(radioName, containerId, inputId, showValue = '1') {
    const radios = document.querySelectorAll(
        `input[name="${radioName}"]`
    );

    const container = document.getElementById(containerId);
    const input = document.getElementById(inputId);

    if (!radios.length || !container || !input) return;

    radios.forEach(radio => {
        radio.addEventListener('change', function () {

            const showField =
                this.value === showValue && this.checked;

            container.classList.toggle('hidden', !showField);

            input.required = showField;

            if (!showField) {
                input.value = '';
            }
        });
    });
}

setupConditionalField(
    'SamType',
    'sampleTypeSpecifyContainer',
    'sampleTypeSpecify',
    'Others'
);

setupConditionalField(
    'Subinfo2',
    'hazardousSpecifyContainer',
    'hazardousSpecify'
);

setupConditionalField(
    'Subinfo4',
    'conformitySpecifyContainer',
    'conformitySpecify'
);



// LOCATION API
const locationModal = document.getElementById('locationModal');
const PSGC_API = 'https://psgc.gitlab.io/api';

const regionSelect = document.getElementById('region');
const provinceSelect = document.getElementById('province');
const municipalitySelect = document.getElementById('municipality');

document.addEventListener('DOMContentLoaded', () => {
    loadRegions();
});

// LOAD REGION
async function loadRegions() {
    try {
        const response = await fetch(`${PSGC_API}/regions/`);
        if (!response.ok) {
            throw new Error(`Failed to load regions: ${response.status}`);
        }
        const regions = await response.json();
        regionSelect.innerHTML = `
            <option value="" selected disabled>
                Select Region
            </option>
        `;

        regions.forEach(region => {
            const option = document.createElement('option');
            option.value = region.code;
            option.textContent =
                region.regionName && region.regionName !== region.name
                    ? `${region.regionName} - ${region.name}`
                    : region.name;

            regionSelect.appendChild(option);
        });

    } catch (error) {
        console.error('Error loading regions:', error);
    }
}

// LOAD PROVINCE
regionSelect.addEventListener('change', async function () {

    const regionCode = this.value;
    resetProvince();
    resetMunicipality();

    try {
        const response = await fetch(`${PSGC_API}/provinces/`);

        if (!response.ok) {
            throw new Error(`Failed to load provinces: ${response.status}`);
        }
        const provinces = await response.json();
        const filteredProvinces = provinces.filter(
            province => province.regionCode === regionCode
        );

        filteredProvinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.code;
            option.textContent = province.name;
            provinceSelect.appendChild(option);
        });

        if(regionCode != "130000000"){
            provinceSelect.disabled = false;
        }

    } catch (error) {
        console.error('Error loading provinces:', error);
    }
});

// LOAD CITY 
provinceSelect.addEventListener('change', async function () {
    const provinceCode = this.value;
    resetMunicipality();

    try {
        const response = await fetch(`${PSGC_API}/provinces/${provinceCode}/cities-municipalities/`);
        if (!response.ok) {
            throw new Error(`Failed to load cities/municipalities: ${response.status}`);
        }
        const locations = await response.json();
        locations.forEach(location => {
            const option = document.createElement('option');
            option.value = location.code;
            option.textContent = location.name;
            municipalitySelect.appendChild(option);
        });
        municipalitySelect.disabled = false;
    } catch (error) {
        console.error(
            'Error loading cities / municipalities:',
            error
        );
    }
});

function resetProvince() {
    provinceSelect.innerHTML = `
        <option value="" selected disabled>
            Select Province
        </option>
    `;

    provinceSelect.disabled = true;
}


function resetMunicipality() {
    municipalitySelect.innerHTML = `
        <option value="" selected disabled>
            Select City / Municipality
        </option>
    `;

    municipalitySelect.disabled = true;
}









let activeLocationRow = null;
const CancelLocationButton = document.getElementById('cancelLocation');
const ApplyLocationBUtton = document.getElementById('applyLocation');

    // OPEN MODAL FOR ANY ROW
    document.addEventListener('click', function (event) {
        const locationButton = event.target.closest('.location-picker');
        if (!locationButton) return;

        const row_id = locationButton.dataset.row;
        activeLocationRow = row_id;

        regionSelect.value = "";
        provinceSelect.value = "";
        municipalitySelect.value = "";

        locationModal.classList.remove('hidden');
        locationModal.classList.add('flex');

        locationModal.classList.remove('lims-fade-in');
        void locationModal.offsetWidth;
        locationModal.classList.add('lims-fade-in');
    });

    // Submit Location Tab
    ApplyLocationBUtton.addEventListener('click', function () {
        const regionText = regionSelect.options[regionSelect.selectedIndex].text;
        const provinceText = provinceSelect.options[provinceSelect.selectedIndex].text;
        const municipalityText = municipalitySelect.options[municipalitySelect.selectedIndex].text;
        
        const regionCode       = regionSelect.value;
        const provinceCode     = provinceSelect.value;
        const municipalityCode = municipalitySelect.value;

        let placeCode = '';

        if (municipalityCode)  { placeCode = municipalityCode;  } 
        else if (provinceCode) { placeCode = provinceCode;      } 
        else if (regionCode)   { placeCode = regionCode;        }

        let location_value;
        if(provinceSelect.value != "" && municipalitySelect.value != ""){
            location_value = municipalityText +" "+ provinceText;
        }
        else{
            location_value = regionText;
        }
        // console.log(activeLocationRow);

        document.getElementById(
        `locationDisplay${activeLocationRow}`
        ).textContent = location_value;

        document.getElementById(
        `locationhidden${activeLocationRow}`
        ).value = location_value;

        document.getElementById(
        `locationcodehidden${activeLocationRow}`
        ).value = placeCode;

        closeLocationModal();
    });

    // Close Location Tab
    function closeLocationModal() {
        locationModal.classList.remove('lims-fade-in');
        locationModal.classList.add('lims-fade-out');

        setTimeout(() => {
            locationModal.classList.remove('flex', 'lims-fade-out');
            locationModal.classList.add('hidden');
        }, 200);
    }
    CancelLocationButton.addEventListener('click', closeLocationModal);


// Analysis Modal Condition
