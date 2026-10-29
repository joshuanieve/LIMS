import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import {
    fetchAnalysisList,
    getAnalysisNames
} from '../services/fetchAnalysisTable.js';

let analysisList = [];
let routinglist;

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

// Same reference formatting as jobrouting.js - duplicated rather than
// shared since there's no common "cards" service module yet; keep both
// in sync if the format changes.
function formatReference(request) {
    return `Ref. #${String(request.requestId).padStart(4, '0')}`;
}

function getSampleProgress(sample) {
    const labWorks = sample.labWorks ?? [];
    const total = labWorks.length;

    // A row counts as encoded once it has both a unit AND a result -
    // analyst assignment (userId) doesn't gate progress here anymore.
    const completed = labWorks.filter(
        lw => lw.unit != null && lw.unit !== ''
            && lw.result != null && lw.result !== ''
    ).length;

    return { total, completed };
}

function getSampleStatus(completed, total, targetDate) {
    if (total > 0 && completed === total) {
        return 'completed';
    }

    if (targetDate) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const target = new Date(targetDate);
        target.setHours(0, 0, 0, 0);

        if (target < today) {
            return 'overdue';
        }
    }

    // A sample only shows up here once it's been routed to an analyst, so
    // there's no "not started" state to show - it's in progress from the
    // moment it appears, even at 0/N encoded.
    return 'in-progress';
}

const STATUS_LABEL = {
    'in-progress': 'In progress',
    'completed': 'Completed',
    'overdue': 'Overdue'
};

function buildDueBlock(targetDate, status) {
    if (!targetDate) {
        return `
            <p class="lims-grid-card-target-date">&ndash;</p>
            <p class="lims-grid-card-days-left not-set">Not set</p>
        `;
    }

    if (status === 'completed') {
        return `
            <p class="lims-grid-card-target-date">${formatDate(targetDate)}</p>
            <p class="lims-grid-card-days-left ready">Ready for review</p>
        `;
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const target = new Date(targetDate);
    target.setHours(0, 0, 0, 0);
    const diffDays = Math.round((target - today) / 86400000);

    const note = diffDays > 0
        ? `${diffDays} day${diffDays === 1 ? '' : 's'} left`
        : diffDays === 0
            ? 'Due today'
            : `Overdue by ${Math.abs(diffDays)} day${Math.abs(diffDays) === 1 ? '' : 's'}`;

    const overdueClass = status === 'overdue' ? ' overdue' : '';

    return `
        <p class="lims-grid-card-target-date${overdueClass}">${formatDate(targetDate)}</p>
        <p class="lims-grid-card-days-left${overdueClass}">${note}</p>
    `;
}

function buildActionButton(status, requestId, testId) {
    if (status === 'completed') {
        return `
            <button type="button" data-request-id="${requestId}" data-test-id="${testId}" class="noselect lims-grid-outline-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                View
            </button>
        `;
    }

    return `
        <button type="button" data-request-id="${requestId}" data-test-id="${testId}" class="noselect lims-button-view">
            <span class="text">Encode</span>
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                </svg>
            </span>
        </button>
    `;
}

// POPULATE CARDS
document.addEventListener("DOMContentLoaded", async function () {
    const tableBody = document.getElementById("ResultforEncodingBody");
    const sampleSubtitleLabel = document.getElementById("resultsSampleSubtitle");

    try {
        showLoading('Loading results for encoding…');
        const response = await fetch('/resultforencoding/data', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error: ${response.status}`);
        }

        const requests = await response.json();
        routinglist = requests; // Store the fetched requests in the global variable
        tableBody.innerHTML = "";

        let totalSamples = 0;
        let completedCount = 0;
        let overdueCount = 0;

        requests.forEach(request => {
            (request.samples ?? []).forEach(sample => {
                totalSamples++;

                const { total, completed } = getSampleProgress(sample);
                const status = getSampleStatus(completed, total, request.targetDate);

                if (status === 'completed') completedCount++;
                if (status === 'overdue') overdueCount++;

                const progressPct = total > 0 ? Math.round((completed / total) * 100) : 0;
                const fillClass = status === 'completed' ? 'complete' : status === 'overdue' ? 'overdue' : '';

                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>
                        <div class="lims-grid-card">
                            <div class="lims-grid-card-top">
                                <span class="lims-grid-card-labno">${sample.laboratoryNumber ?? "**LabNumber**"}</span>
                                <span class="lims-grid-status-pill ${status}">${STATUS_LABEL[status]}</span>
                            </div>

                            <p class="lims-grid-card-sample-name">${sample.sample ?? "-"}</p>

                            <div>
                                <div class="lims-grid-progress-head">
                                    <span class="lims-grid-progress-label">Analyses encoded</span>
                                    <span class="lims-grid-progress-count">${completed}/${total}</span>
                                </div>
                                <div class="lims-grid-progress-track">
                                    <div class="lims-grid-progress-fill ${fillClass}" style="width:${progressPct}%"></div>
                                </div>
                            </div>

                            <div class="lims-grid-card-target">
                                <p class="lims-grid-card-target-label">Due</p>
                                ${buildDueBlock(request.targetDate, status)}
                            </div>

                            <div class="lims-grid-card-actions">
                                ${buildActionButton(status, request.requestId, sample.testId)}
                            </div>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        });

        if (sampleSubtitleLabel) {
            const parts = [];
            if (completedCount > 0) parts.push(`${completedCount} completed`);
            if (overdueCount > 0) parts.push(`${overdueCount} overdue`);
            sampleSubtitleLabel.textContent = parts.length
                ? parts.join(' · ')
                : `${totalSamples} sample${totalSamples === 1 ? '' : 's'} assigned`;
        }

        // Initialize DataTables AFTER rows are populated
        new DataTable('#ResultforEncodingTable', {
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
                infoEmpty: 'No samples assigned',
                infoFiltered: '(filtered from _MAX_ total samples)',
                emptyTable: 'No samples assigned yet.'
            },
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
                    Failed to load data.
                </td>
            </tr>
        `;
    } finally {
        hideLoading();
    }

});



const closeResultsModalHeader = document.getElementById('closeResultsModalHeader');
const closeResultsModalFooter = document.getElementById('CloseResultsModal');
closeResultsModalHeader?.addEventListener('click', () => CloseModal('ResultsModal'));
closeResultsModalFooter?.addEventListener('click', () => CloseModal('ResultsModal'));

// TEMP PLACEHOLDER until a real employee/analyst list API is wired in
// (same stand-in list used in jobrouting.js - keep userId/name in sync).
const analysts = [
    { userId: 7, name: 'Ana Reyes' },
    { userId: 8, name: 'Mark Lopez' },
    { userId: 9, name: 'Joy Santos' }
];

const Table = document.getElementById('ResultforEncodingTable');
Table.addEventListener('click', viewRequest);
function viewRequest(event) {
    // Matches either the solid "Encode" button (.lims-button-view) or the
    // outline "View" button shown once a sample is completed.
    const button = event.target.closest('.lims-button-view, .lims-grid-outline-button');
    if (!button) return;

    const requestId = Number(button.dataset.requestId);
    const testId = Number(button.dataset.testId);

    const request = (routinglist ?? []).find(
        r => r.requestId === requestId
    );
    const sample = (request?.samples ?? []).find(
        s => s.testId === testId
    );

    renderResultsTable(sample?.labWorks ?? []);
    applyReassignVisibility();
    OpenModal("ResultsModal");
}

// "Reassign" (Action column) is admin-only. `data-role` comes from
// <body data-role="{{ session('lims_role') }}"> in layouts/app.blade.php.
function applyReassignVisibility() {
    const isAdmin = document.body.dataset.role === 'admin';

    document.querySelectorAll('#ResultsModal .lims-admin-only').forEach(el => {
        el.classList.toggle('hidden', !isAdmin);
    });
}

function renderResultsTable(labWorks) {
    const resultsTableBody = document.getElementById('resultsTableBody');
    resultsTableBody.innerHTML = '';

    if (!labWorks.length) {
        resultsTableBody.innerHTML = `
            <tr>
                <td colspan="7" class="lims-result-method">
                    No laboratory parameters assigned to this sample yet.
                </td>
            </tr>
        `;
        return;
    }

    labWorks.forEach((labWork, index) => {
        const analysis = analysisList.find(
            a => a.analysisId === labWork.analysis
        );

        const analyst = analysts.find(
            a => a.userId === labWork.userId
        );

        const hasResult = labWork.result !== null &&
            labWork.result !== undefined &&
            labWork.result !== '';

        const row = document.createElement('tr');
        row.className = hasResult
            ? 'lims-result-row has-result'
            : 'lims-result-row';
        row.dataset.labWorkId = labWork.labWorkId;
        row.dataset.testId = labWork.testId;
        row.dataset.analysisId = labWork.analysis;

        row.innerHTML = `
            <td class="lims-result-no">${index + 1}</td>
            <td class="lims-result-param">${analysis?.analyte ?? `Unknown (${labWork.analysis})`}</td>
            <td class="lims-result-method">${analysis?.method ?? '-'}</td>
            <td>
                <input
                    type="text"
                    class="lims-table-input lims-result-unit"
                    placeholder="e.g. mg/L"
                    value="${labWork.unit ?? ''}"
                >
            </td>
            <td class="lims-result-cell">
                <input
                    type="text"
                    class="lims-table-input lims-result-input"
                    placeholder="Result"
                    value="${labWork.result ?? ''}"
                >
            </td>
            <td>
                ${analyst
                    ? `<span class="lims-result-analyst">${analyst.name}</span>`
                    : `<span class="lims-result-unassigned">Not assigned</span>`
                }
            </td>
            <td class="lims-admin-only">
                <button type="button" class="lims-reassign-button" title="Reassign analyst">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/>
                    </svg>
                    Reassign
                </button>
            </td>
        `;

        resultsTableBody.appendChild(row);
    });
}
