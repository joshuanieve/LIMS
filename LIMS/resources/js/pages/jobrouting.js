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

        console.log('Analysis List:', analysisList);

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
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${request.requestId ?? "-"}</td>
                <td>${request.customerName ?? "-"}</td>
                <td>${request.sampleType ?? "-"}</td>
                <td>${request.labAnalysis ?? "-"}</td>
                <td>${formatDate(request.dateTime)}</td>

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