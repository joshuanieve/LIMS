// Logs page - list + change-detail layout.
//
// No audit-log API exists yet: the entries are embedded server-side
// (LogsController -> #logsData script tag) rather than fetched. The
// click-to-select and search behavior below are real, not placeholders -
// swap the data source for a fetch() later without touching this logic.

const ICONS = {
    success: '<path d="M5 13l4 4L19 7"/>',
    edit: '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
    info: '<path d="M12 5v14M5 12h14"/>',
    warning: '<path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
    neutral: '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/>'
};

function iconSvg(type, size) {
    return `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="${size}" height="${size}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            ${ICONS[type] ?? ICONS.neutral}
        </svg>
    `;
}

const LOGS_PAGE_SIZE = 8;

function renderList(logs, activeId, page) {
    const list = document.getElementById('logsList');

    if (!logs.length) {
        list.innerHTML = '<div class="lims-logs-empty">No activity matches your search.</div>';
        return page;
    }

    const totalPages = Math.max(1, Math.ceil(logs.length / LOGS_PAGE_SIZE));
    page = Math.min(Math.max(page, 1), totalPages);

    const start = (page - 1) * LOGS_PAGE_SIZE;
    const pageLogs = logs.slice(start, start + LOGS_PAGE_SIZE);

    const itemsHtml = pageLogs.map(log => `
        <button
            type="button"
            class="lims-logs-item${log.id === activeId ? ' active' : ''}"
            data-log-id="${log.id}"
        >
            <span class="lims-logs-icon ${log.type}">${iconSvg(log.type, 15)}</span>

            <span class="lims-logs-item-body">
                <span class="lims-logs-item-title">${log.title}</span>
                <span class="lims-logs-item-meta">${log.actor} &middot; ${log.module}</span>
            </span>

            <span class="lims-logs-item-time">
                <span class="lims-logs-item-date">${log.date}</span>
                <span class="lims-logs-item-clock">${log.time}</span>
            </span>
        </button>
    `).join('');

    const paginationHtml = `
        <div class="lims-logs-pagination">
            <span>Page ${page} of ${totalPages} &middot; ${logs.length} ${logs.length === 1 ? 'entry' : 'entries'}</span>

            <div class="lims-logs-pagination-actions">
                <button type="button" id="logsPrevPage" class="lims-logs-page-button" ${page === 1 ? 'disabled' : ''}>
                    Previous
                </button>
                <button type="button" id="logsNextPage" class="lims-logs-page-button" ${page === totalPages ? 'disabled' : ''}>
                    Next
                </button>
            </div>
        </div>
    `;

    list.innerHTML = `
        <div class="lims-logs-items">${itemsHtml}</div>
        ${paginationHtml}
    `;

    return page;
}

function renderChangesTable(changes) {
    if (!changes || !changes.length) return '';

    const rows = changes.map(c => `
        <tr>
            <td class="field">${c.field}</td>
            <td>${c.before}</td>
            <td class="${c.highlight ? 'highlight' : ''}">${c.after}</td>
        </tr>
    `).join('');

    return `
        <div class="lims-logs-changes">
            <p class="lims-logs-changes-title">What changed</p>
            <table class="lims-logs-changes-table">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Before</th>
                        <th>After</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>
    `;
}

function renderDetail(log) {
    const panel = document.getElementById('logsDetail');

    // Restart the fade-in (remove, force reflow, re-add) every time the
    // selected entry changes - re-adding an already-present class alone
    // wouldn't restart a CSS animation.
    panel.classList.remove('lims-fade-in');
    void panel.offsetWidth;
    panel.classList.add('lims-fade-in');

    if (!log) {
        panel.innerHTML = '<div class="lims-logs-empty">Select an entry to see its detail.</div>';
        return;
    }

    const d = log.detail;

    // Sample-level events have both a reference number and a lab number
    // ("Ref. #0411 2026-5982"); request-level events only have the
    // reference number ("Ref. #0412") - combined into one "Record" line.
    const combinedRecord = d.record ? [d.request, d.record].filter(Boolean).join(' ') : d.request;

    const fields = [
        ['User', d.user, false],
        ['Module', d.module, false],
        ['Record', combinedRecord, true],
    ].filter(([, value]) => value);

    const fieldsHtml = fields.map(([label, value, isLink]) => `
        <div class="lims-logs-detail-field">
            <span class="lims-logs-detail-label">${label}</span>
            <span class="lims-logs-detail-value${isLink ? ' link' : ''}">${value}</span>
        </div>
    `).join('');

    panel.innerHTML = `
        <div class="lims-logs-detail-header">
            <span class="lims-logs-detail-icon ${log.type}">${iconSvg(log.type, 20)}</span>
            <div>
                <p class="lims-logs-detail-title">${log.title}</p>
                <p class="lims-logs-detail-time">${log.date} · ${log.time}</p>
            </div>
        </div>

        <div class="lims-logs-detail-grid">${fieldsHtml}</div>

        ${renderChangesTable(d.changes)}

        <div class="lims-logs-detail-actions">
            ${d.record
                ? `<button type="button" class="lims-button-view">
                       <span class="text">Open sample ${d.record}</span>
                       <span class="icon">${iconSvg('info', 16)}</span>
                   </button>`
                : ''
            }
            <button type="button" class="lims-grid-outline-button">All activity on this record</button>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', function () {
    const dataScript = document.getElementById('logsData');
    if (!dataScript) return;

    let logs = [];
    try {
        logs = JSON.parse(dataScript.textContent);
    } catch (error) {
        console.error('Logs: failed to parse embedded log data', error);
        return;
    }

    // Default to the first "edit" entry if there is one, else the first entry -
    // matches the reference design (an edited-result row selected by default).
    let activeId = (logs.find(l => l.type === 'edit') ?? logs[0])?.id ?? null;
    let currentPage = 1;

    function show(filteredLogs, page = currentPage) {
        currentPage = renderList(filteredLogs, activeId, page);
        renderDetail(filteredLogs.find(l => l.id === activeId) ?? filteredLogs[0] ?? null);
    }

    show(logs);

    document.getElementById('logsList').addEventListener('click', function (event) {
        const item = event.target.closest('.lims-logs-item');
        if (item) {
            activeId = Number(item.dataset.logId);
            show(currentFiltered());
            return;
        }

        if (event.target.closest('#logsPrevPage')) {
            show(currentFiltered(), currentPage - 1);
            return;
        }

        if (event.target.closest('#logsNextPage')) {
            show(currentFiltered(), currentPage + 1);
        }
    });

    const searchInput = document.getElementById('logsSearchInput');
    function currentFiltered() {
        const term = (searchInput.value ?? '').trim().toLowerCase();
        if (!term) return logs;

        return logs.filter(log => [
            log.title,
            log.actor,
            log.module,
            log.detail?.record,
            log.detail?.request
        ].filter(Boolean).join(' ').toLowerCase().includes(term));
    }

    searchInput.addEventListener('input', function () {
        const filtered = currentFiltered();
        if (!filtered.some(l => l.id === activeId)) {
            activeId = filtered[0]?.id ?? null;
        }
        show(filtered, 1); // reset to page 1 whenever the search term changes
    });
});
