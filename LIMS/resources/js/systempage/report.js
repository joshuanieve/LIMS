// Reports page
//
// No reporting/aggregation API exists yet (ReportsController holds
// temporary hardcoded numbers) - the period toggle below only switches
// which button looks active, it doesn't recalculate anything yet. Wire it
// to a real fetch (passing the selected period) once an endpoint exists.

document.addEventListener('DOMContentLoaded', function () {
    const periodGroup = document.getElementById('reportsPeriod');

    periodGroup?.addEventListener('click', function (event) {
        const button = event.target.closest('.lims-reports-period-button');
        if (!button) return;

        periodGroup.querySelectorAll('.lims-reports-period-button').forEach(btn => {
            btn.classList.toggle('active', btn === button);
        });

        // TODO: refetch KPIs/breakdowns for button.dataset.period once a
        // reporting endpoint exists.
    });

    document.getElementById('reportsExportButton')?.addEventListener('click', function () {
        window.Swal?.fire({
            icon: 'info',
            title: 'Export Report',
            text: "This isn't wired to a backend yet - no CSV/PDF generation exists."
        });
    });
});
