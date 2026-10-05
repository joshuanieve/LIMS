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
    const tableBody = document.getElementById("JobRoutingTableBody");

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

        requests.forEach(request => {
            (request.samples ?? []).forEach(sample => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${sample.laboratoryNumber ?? "-"}</td>
                    <td>${sample.sample ?? "-"}</td>
                    <td>${formatDate(sample.dateTimeCollected ?? "-")}</td>
                    <td>${sample.placeCollected ?? "-"}</td>
                    <td>${formatDate(request.dateTimeRelease)}</td>
                    <td>
                        <button
                        type="button"
                        data-request-id="${request.requestId}"
                        data-request-analysis="${sample.analysis}"
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
        });


        // Initialize DataTables AFTER rows are populated
        new DataTable('#JobRoutingTable', {
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            paging: true,
            info: true
        });


    } catch (error) {
        console.error("Failed to load pending requests:", error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="7">
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
    
    const ids = button.dataset.requestAnalysis.split(',').map(Number);   // "1,2,3" → [1, 2, 3]
    let rows = '';
    ids.forEach(id => {
        console.log(id);
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






const analysts = [
    { userId: 7, name: 'Ana Reyes' },
    { userId: 8, name: 'Mark Lopez' },
    { userId: 9, name: 'Joy Santos' }
];