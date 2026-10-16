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

// Counts how many of a sample's analyses are Chemical vs Microbiological,
// using the same `type` values AnalysisList actually returns
// ("Chemical Analysis" / "Biological Analysis" - see requestform.js's
// LAB_TYPE_KEYWORDS for the same naming mismatch).
function countCategories(analysisValue) {
    const counts = { chemical: 0, microbiological: 0 };

    (analysisValue ?? '')
        .split(',')
        .map(id => Number(id.trim()))
        .filter(id => !Number.isNaN(id))
        .forEach(id => {
            const analysis = analysisList.find(a => a.analysisId === id);
            const type = (analysis?.type ?? '').toLowerCase();

            if (type.includes('chemical')) {
                counts.chemical++;
            } else if (type.includes('biological')) {
                counts.microbiological++;
            }
        });

    return counts;
}

function buildCategoryPills(analysisValue) {
    const { chemical, microbiological } = countCategories(analysisValue);
    let html = '';

    if (chemical > 0) {
        html += `<span class="lims-grid-category-pill chemical">${chemical} chemical</span>`;
    }

    if (microbiological > 0) {
        html += `<span class="lims-grid-category-pill microbiological">${microbiological} micro</span>`;
    }

    return html || '<span class="lims-grid-category-pill">No analyses</span>';
}

function buildTargetBlock(dateTimeRelease) {
    if (!dateTimeRelease) {
        return `
            <p class="lims-grid-card-target-date">&ndash;</p>
            <p class="lims-grid-card-days-left not-set">Not set</p>
        `;
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const target = new Date(dateTimeRelease);
    target.setHours(0, 0, 0, 0);

    const diffDays = Math.round((target - today) / 86400000);
    const daysLeftText = diffDays > 0
        ? `${diffDays} day${diffDays === 1 ? '' : 's'} left`
        : diffDays === 0
            ? 'Due today'
            : `${Math.abs(diffDays)} day${Math.abs(diffDays) === 1 ? '' : 's'} overdue`;

    return `
        <p class="lims-grid-card-target-date">${formatDate(dateTimeRelease)}</p>
        <p class="lims-grid-card-days-left">${daysLeftText}</p>
    `;
}

function formatReference(request) {
    return `Ref. #${String(request.requestId).padStart(4, '0')}`;
}

// POPULATE CARDS
document.addEventListener("DOMContentLoaded", async function () {
    const tableBody = document.getElementById("JobRoutingTableBody");
    const sampleCountLabel = document.getElementById("jobRoutingSampleCount");

    try {
        const response = await fetch('/jobrouting/data', {
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

        let totalSamples = 0;

        requests.forEach(request => {
            (request.samples ?? []).forEach(sample => {
                totalSamples++;

                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>
                        <div class="lims-grid-card">
                            <div class="lims-grid-card-top">
                                <span class="lims-grid-card-labno">${sample.laboratoryNumber ?? "**LabNumber**"}</span>

                                <div class="lims-grid-card-target">
                                    <p class="lims-grid-card-target-label">Target</p>
                                    ${buildTargetBlock(request.dateTimeRelease)}
                                </div>
                            </div>

                            <p class="lims-grid-card-sample-name">${sample.sample ?? "**SampleName**"}</p>

                            <p class="lims-grid-card-meta">
                                ${request.customerName ?? "**CustomerName**"}
                            </p>

                            <p class="lims-grid-card-meta">
                                Collected ${formatDate(sample.dateTimeCollected)} &middot; ${sample.placeCollected ?? "-"}
                            </p>

                            <div class="lims-grid-card-categories">
                                ${buildCategoryPills(sample.analysis)}
                            </div>

                            <div class="lims-grid-card-actions">
                                <button
                                    type="button"
                                    data-request-id="${request.requestId}"
                                    data-test-id="${sample.testId}"
                                    data-lab-no="${sample.laboratoryNumber ?? 'Pending'}"
                                    class="noselect lims-grid-cancel-button"
                                >
                                    Cancel Test
                                </button>

                                <button
                                    type="button"
                                    data-request-id="${request.requestId}"
                                    data-request-analysis="${sample.analysis}"
                                    data-lab-no="${sample.laboratoryNumber ?? '-'}"
                                    data-sample-name="${sample.sample ?? '-'}"
                                    class="noselect lims-button-view remove-row"
                                >
                                    <span class="text">Route</span>

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
                                            <path d="M5 12h14M13 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        });

        if (sampleCountLabel) {
            sampleCountLabel.textContent = `${totalSamples} sample${totalSamples === 1 ? '' : 's'} to route`;
        }

        // Initialize DataTables AFTER rows are populated
        new DataTable('#JobRoutingTable', {
            pageLength: 9,
            lengthChange: false,
            ordering: false,
            searching: true,
            paging: true,
            info: true,
            language: {
                search: '',
                searchPlaceholder: 'Search lab no., sample, customer',
                info: 'Showing _START_ to _END_ of _TOTAL_ samples',
                infoEmpty: 'No samples to route',
                infoFiltered: '(filtered from _MAX_ total samples)',
                emptyTable: 'No samples to route.'
            },
            // Fade the cards in on every redraw (page change, search) -
            // restart the CSS animation by removing the class, forcing a
            // reflow, then re-adding it (just re-adding an already-present
            // class doesn't restart a CSS animation).
            drawCallback: function () {
                tableBody.classList.remove('lims-grid-fade-in');
                void tableBody.offsetWidth;
                tableBody.classList.add('lims-grid-fade-in');
            }
        });


    } catch (error) {
        console.error("Failed to load pending requests:", error);
        tableBody.innerHTML = `
            <tr>
                <td>
                    Failed to load requests.
                </td>
            </tr>
        `;
    }

});


const CloseRouteButton = document.getElementById('CloseRouteModal');
CloseRouteButton.addEventListener('click', () => CloseModal('RouteModal'));

const closeRouteModalHeader = document.getElementById('closeRouteModalHeader');
closeRouteModalHeader?.addEventListener('click', () => CloseModal('RouteModal'));




const JobRoutingTable = document.getElementById('JobRoutingTable');
JobRoutingTable.addEventListener('click', viewRequest);
function viewRequest(event) {
    const button = event.target.closest('.lims-button-view');
    const container = document.querySelector('#RouteModal .lims-analysis-options');
    if (!button) return;

    setText('routeLaboratoryNumber', button.dataset.labNo);
    setText('routeSample', button.dataset.sampleName);

    const ids = button.dataset.requestAnalysis.split(',').map(Number);   // "1,2,3" → [1, 2, 3]
    let rows = '';
    ids.forEach(id => {
        const analysis = analysisList.find(a => a.analysisId === id);
        if (!analysis) return;

        rows += `
            <div class="lims-route-row">
                <div>
                    <p class="lims-analysis-name">${analysis.analyte}</p>
                    <p class="lims-analysis-method">${analysis.method}</p>
                </div>
                <select class="lims-input lims-route-select" data-analysis-id="${analysis.analysisId}">
                    <option value="">Select analyst</option>

                </select>
            </div>
        `;
    });

    container.innerHTML = rows || '<p class="lims-analysis-method">No analysis requested.</p>';
    OpenModal('RouteModal');
}


// "Cancel Test" - stub pending a real endpoint.
JobRoutingTable.addEventListener('click', function (event) {
    const button = event.target.closest('.lims-grid-cancel-button');
    if (!button) return;

    window.Swal.fire({
        icon: 'warning',
        title: 'Cancel Test',
        text: `Cancel testing for ${button.dataset.labNo}? This isn't wired to a backend yet.`,
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'Never mind'
    }).then(result => {
        if (result.isConfirmed) {
            console.log('Cancel Test (not implemented):', button.dataset.testId, button.dataset.requestId);
        }
    });
});




const analysts = [
    { userId: 7, name: 'Ana Reyes' },
    { userId: 8, name: 'Mark Lopez' },
    { userId: 9, name: 'Joy Santos' }
];
