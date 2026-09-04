
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

                <td><input
                        type="text"
                        name="samples[${rowIndex}][place_collected]"
                        class="lims-table-input"
                        placeholder="Location"
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
                </td>

                <td>
                    <button
                        type="button"
                        class="lims-remove-button remove-row"
                    >
                        Remove
                    </button>
                </td>
            `;

        tbody.appendChild(row);
        rowIndex++;
    });

// REMOVE ROW FROM TABLE
    document.getElementById('sampleTableBody')
        .addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-row')) {
                event.target.closest('tr').remove();
            }
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
        document.querySelectorAll('.analysis-checkbox').forEach(cb => cb.checked = false);
        const row_id = analysisButton.dataset.row;
        activeAnalysisRow = row_id;
        
        load_checkboxes();

        // Show the Tab
        analysisModal.classList.remove('hidden');
        analysisModal.classList.add('flex');
    });

    function load_checkboxes(){
        
        const hidden_inputvalue = document.getElementById(`samplehidden${activeAnalysisRow}`).value;
        console.log(hidden_inputvalue);
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

        const checkedCheckboxes = document.querySelectorAll(
            'input.analysis-checkbox:checked'
        );
        let Analyte = [];
        let ID = [];

        checkedCheckboxes.forEach(function (checkbox) {
            ID.push(checkbox.value);
            Analyte.push(checkbox.dataset.name);
        });
        console.log(activeAnalysisRow);

        document.getElementById(
        `analysisDisplay${activeAnalysisRow}`
        ).textContent = Analyte.join(', ');

        document.getElementById(
        `samplehidden${activeAnalysisRow}`
        ).value = ID.join(', ');

        analysisModal.classList.remove('flex');
        analysisModal.classList.add('hidden');
    });

    // Close Analysis Tab
    function closeAnalysisModal() {
        analysisModal.classList.add('hidden');
        analysisModal.classList.remove('flex');
    }
    cancelAnalysis.addEventListener('click', closeAnalysisModal);

// VALIDATE FIELDS
const form = document.querySelector('.lims-form');
form.addEventListener('submit', function (event) {
    validate_fields();
});

function validate_fields(){
    
    let hasError = false;
    const analysisButtons = document.querySelectorAll('.analysis-picker');

    analysisButtons.forEach(function (button) {
        const rowIndex = button.dataset.row;
        const hiddenInput = document.getElementById(
            `samplehidden${rowIndex}`
        );

        if (!hiddenInput || hiddenInput.value.trim() === '') {
            hasError = true;
            button.classList.add('analysis-picker-error');
        } else {
            button.classList.remove('analysis-picker-error');
        }
    });

    if (hasError) {
        event.preventDefault();
        swal_error(
            'Incomplete Information',
            'Please select an analysis for every sample.'
        );
    }
}



