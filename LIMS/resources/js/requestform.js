
//////////////////////// ANALYSIS ONCLICK POP UP //////////////////////////////
let rowIndex = 1;

// ADD ROW FROM TABLE
    document.getElementById('addRow').addEventListener('click', function () {
        const tbody = document.getElementById('sampleTableBody');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td><input  type="text"             name="samples[${rowIndex}][sample]"                 class="input" placeholder="Enter Sample"></td>
            <td><input  type="text"             name="samples[${rowIndex}][customer_sample_code]"   class="input" placeholder="Enter Customer Sample Code"></td>
            <td><input  type="datetime-local"   name="samples[${rowIndex}][date_collected]"         class="input"></td>
            <td><input  type="text"             name="samples[${rowIndex}][place_collected]"        class="input" placeholder="Enter Place Collected"></td>
            <td>
                <div><button
                    type="button"
                    class="analysis-picker"
                    data-row="${rowIndex}"
                    >
                        <span id="analysisDisplay${rowIndex}">Select analysis...</span>
                    </button>

                    <input type="text" name="samples[${rowIndex}][testarray]" id="samplehidden${rowIndex}">
                </div>
            </td>

            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
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



    });

    // Close Analysis Tab
    function closeAnalysisModal() {
        analysisModal.classList.add('hidden');
        analysisModal.classList.remove('flex');
    }
    cancelAnalysis.addEventListener('click', closeAnalysisModal);

