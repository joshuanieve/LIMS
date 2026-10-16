import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import {
    fetchAnalysisList,
    getAnalysisNames
} from '../services/fetchAnalysisTable.js';

let analysisList = [];

async function loadAnalysisData() {
    try {
        analysisList = await fetchAnalysisList();

    } catch (error) {
        console.error(
            'Failed to load analysis list:',
            error
        );
    }
}loadAnalysisData();

// POPULATE TABLES
document.addEventListener("DOMContentLoaded", async function () {
    const tableBody = document.getElementById("requestTableBody");

    try {
        const response = await fetch('/requestsample/data', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error: ${response.status}`);
        }

        const requests = await response.json();
        tableBody.innerHTML = "";

        requests.forEach(request => {
            const isChemical = (request.labAnalysis ?? '').trim().toLowerCase() === 'chemical';

            const categoryIcon = isChemical
                ? '<path d="M9 3h6M10 3v6l-5 9a2 2 0 001.74 3h10.52A2 2 0 0019 18l-5-9V3M8 15h8"/>'
                : '<circle cx="12" cy="12" r="8"/><circle cx="9" cy="10" r="1" fill="currentColor"/><circle cx="14" cy="9" r="1" fill="currentColor"/><circle cx="13" cy="14" r="1" fill="currentColor"/>';

            const categoryClass = isChemical ? 'chemical' : 'microbiological';

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <div class="lims-list-identity-row">
                        <span class="lims-list-name">${request.customerName ?? "-"}</span>
                        <span class="lims-list-ref">#${request.requestId}</span>
                    </div>
                    <p class="lims-list-meta">${request.sampleType ?? "-"}</p>
                </td>

                <td>
                    <span class="lims-list-category ${categoryClass}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            ${categoryIcon}
                        </svg>
                        ${request.labAnalysis ?? "-"}
                    </span>
                </td>

                <td>
                    ${request.targetDate
                        ? `<p class="lims-list-release">${formatDate(request.targetDate)}</p>`
                        : `<p class="lims-list-release pending">Not yet</p>`
                    }
                    <p class="lims-list-submitted">Submitted ${formatDate(request.dateTime)}</p>
                </td>

                <td>
                    <button
                        type="button"
                        data-request-id="${request.requestId}"
                        class="noselect lims-button-view remove-row"
                    >
                        <span class="text">View</span>

                        <span class="icon">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                width="24"
                                height="24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </span>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });


        // Initialize DataTables AFTER rows are populated
        new DataTable('#requestTable', {
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            paging: true,
            info: true,
            // Fade the rows in on every redraw (page change, search) -
            // restart the CSS animation by removing the class, forcing a
            // reflow, then re-adding it.
            drawCallback: function () {
                tableBody.classList.remove('lims-fade-in');
                void tableBody.offsetWidth;
                tableBody.classList.add('lims-fade-in');
            }
        });


    } catch (error) {
        console.error("Failed to load pending requests:", error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="4">
                    Failed to load requests.
                </td>
            </tr>
        `;
    }

});


// MODAL VIEW
const closeRequestModal = document.getElementById('closeRequestModal');
const closeRequestModalFooter = document.getElementById('closeRequestModalFooter');

closeRequestModal.addEventListener('click', closeRequestFormModal);
closeRequestModalFooter.addEventListener('click', closeRequestFormModal);

function openRequestFormModal() {
    const modal = document.getElementById('RequestFormModal');

    if (!modal) {
        console.error('RequestFormModal not found in the DOM.');
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRequestFormModal() {
    const modal = document.getElementById('RequestFormModal');

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}









const requestTableBody = document.getElementById('requestTableBody');

requestTableBody.addEventListener('click', viewRequest);
function viewRequest(event) {
    const button = event.target.closest('.lims-button-view');
    if (!button) return;
    const requestId = button.dataset.requestId;

    openRequestFormModal();
    fetchRequestFormData(requestId);
}

async function fetchRequestFormData(id) {
    try {
        const response = await fetch(`/requestsample/view/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {throw new Error(`HTTP error: ${response.status}`);}
        const result = await response.json();

        // console.log(result);

        setText('viewCustomerName', result.customerName);
        setText('viewEmailAddress', result.emailAddress);
        setText('viewDivisionSection', result.divisionSection);
        setText('viewDateSubmitted', result.dateTime);

        setText('viewLabAnalysis', result.labAnalysis);

        const standardSampleTypes = [
            'Fishery Resources / Products',
            'Water',
            'Feeds',
            'Sediments'
        ];

        if (standardSampleTypes.includes(result.sampleType)) {
            setText('viewSampleType', result.sampleType);
            setText('viewSampleTypeSpecify', '');
        } else {
            setText('viewSampleType', 'Others');
            setText('viewSampleTypeSpecify', result.sampleType);
        }

        setText('viewSampleRetrieval', result.sampleRetrieval);
        setText('viewReleaseDate', result.dateTimeRelease);
        
        setYesNo('viewSubinfo1', result.subInfo1);
        setYesNo('viewSubinfo2', result.subInfo2);
        setYesNo('viewSubinfo3', result.subInfo3);
        setYesNo('viewSubinfo4', result.subInfo4);

        setText( 'viewSubinfo2Specify', result.subInfo2 ?? '');
        setText( 'viewSubinfo4Specify', result.subInfo4 ?? '');
        setText('viewInstruction', result.instruction);


        // HIDE CONTAINER
        const sampleTypeSpecifyContainer = document.getElementById('viewSampleTypeSpecifyContainer');
        const subInfo2Container = document.getElementById('viewSubinfo2SpecifyContainer');
        const subInfo4Container = document.getElementById('viewSubinfo4SpecifyContainer');
        const isOtherSampleType = !standardSampleTypes.includes(result.sampleType);
        sampleTypeSpecifyContainer.classList.toggle(
            'hidden', !isOtherSampleType
        );

        subInfo2Container.classList.toggle('hidden', !result.subInfo2);
        subInfo4Container.classList.toggle('hidden', !result.subInfo4);

        
        const tbody = document.getElementById('viewSamplesTableBody');
        tbody.innerHTML = '';
        result.samples.forEach(sample => {
            const analysisNames =
                getAnalysisNames(
                    sample.analysis,
                    analysisList
                );

            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${sample.laboratoryNumber ?? '-'}</td>
                <td>${sample.sample ?? '-'}</td>
                <td>${sample.customerSampleCode ?? '-'}</td>
                <td>${formatDate(sample.dateTimeCollected) ?? '-'}</td>
                <td>${sample.placeCollected ?? '-'}</td>
                <td>${analysisNames}</td>
            `;

            tbody.appendChild(row);
        });

        return result;
    } catch (error) {
        console.error('Request Error:', error);
        window.swal_error(
            'Request Error',
            error.message
        );
    }   finally {
        hideLoading();
    }
}

function setYesNo(id, value) {
    const element = document.getElementById(id);
    if (!element) return;

    const isYes = Boolean(value);
    element.textContent = isYes ? '✓ Yes' : '✕ No';
    element.classList.remove('yes', 'no');
    element.classList.add(isYes ? 'yes' : 'no');
}
