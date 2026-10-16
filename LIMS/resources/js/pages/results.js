import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

// Laboratory Records
//
// The table is server-rendered from temporary placeholder data in
// LaboratoryRecordsController (no fetch/data API wired up yet) - DataTables
// is initialized directly against those existing rows, same library/search/
// pagination pattern as Request Sample, Job Routing and Result for Encoding.

document.addEventListener('DOMContentLoaded', async function () {
    // LOAD TABLE
    try {
        showLoading();
        const response = await fetch('/laboratory-records/data', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error: ${response.status}`);
        }

        const requests = await response.json();


        console.log(requests);

        // RENDER IN BROWSER
        const tableBody = document.getElementById("LaboratoryRecordsBody");
        tableBody.innerHTML = "";
        function formatLabNumberRange(samples) {
            const labNumbers = (samples ?? [])
                .map(s => s.laboratoryNumber)
                .filter(Boolean)
                .sort();

            if (!labNumbers.length) return '-';

            const first = labNumbers[0];
            const last = labNumbers[labNumbers.length - 1];

            if (first === last) return first;

            const [firstYear] = first.split('-');
            const [lastYear, lastNumber] = last.split('-');

            if (firstYear !== lastYear) return `${first} - ${last}`;

            return `${first} - ${lastNumber}`;
        }

        function formatSampleMeta(samples) {
            const list = samples ?? [];
            const sampleCount = list.length;

            const analysisCount = list.reduce((total, s) => {
                const ids = String(s.analysis ?? '').split(',').filter(id => id.trim());
                return total + ids.length;
            }, 0);

            return `${sampleCount} ${sampleCount === 1 ? 'sample' : 'samples'} · ${analysisCount} ${analysisCount === 1 ? 'analysis' : 'analyses'}`;
        }


        // ===== ROWS =====
        requests.forEach(request => {
            const labNumberRange = formatLabNumberRange(request.samples);
            const sampleMeta = formatSampleMeta(request.samples);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><span class="lims-flat-id">${request.requestId}</span></td>
                <td>
                    <p class="lims-flat-strong">${request.customerName ?? '-'}</p>
                    <p class="lims-flat-secondary">${request.divisionSection ?? '-'}</p>
                </td>
                <td><span class="lims-flat-value">${request.sampleType ?? '-'}</span></td>
                <td>
                    <p class="lims-flat-strong">${labNumberRange}</p>
                    <p class="lims-flat-secondary">${sampleMeta}</p>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <button
                        type="button"
                        data-request-id="${request.requestId}"
                        class="noselect lims-button-view"
                    >
                        <span class="text">View</span>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </span>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });




             

    }catch (error) {
        console.error("Failed to load Data:", error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="7">
                    Failed to load data.
                </td>
            </tr>
        `;
    }finally {
        hideLoading();
    }















    new DataTable('#LaboratoryRecordsTable', {
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        paging: true,
        info: true,
        // Fade the rows in on every redraw (page change, search). Looked up
        // fresh here rather than reusing the `tableBody` above, since that
        // one is declared inside the try block and out of scope down here.
        drawCallback: function () {
            const body = document.getElementById('LaboratoryRecordsBody');
            if (!body) return;
            body.classList.remove('lims-fade-in');
            void body.offsetWidth;
            body.classList.add('lims-fade-in');
        }
    });

    // TODO: open a request detail view/modal once one exists for this page.
    document.getElementById('LaboratoryRecordsTable')
        ?.addEventListener('click', function (event) {
            const button = event.target.closest('.lims-button-view');
            if (!button) return;

            console.log('Laboratory Records: view', button.dataset.rfaNo);
        });
});
