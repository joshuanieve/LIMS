import './bootstrap';

import DataTable from 'datatables.net-dt';

document.addEventListener('DOMContentLoaded', function () {

    const requestTable = document.querySelector('#requestTable');
    if (requestTable) {
        new DataTable(requestTable, {
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[4, 'desc']]
        });
    }

    
});

document.addEventListener('DOMContentLoaded', function () {

    const JobRoutingTable = document.querySelector('#JobRoutingTable');
    if (JobRoutingTable) {
        new DataTable(JobRoutingTable, {
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[4, 'desc']]
        });
    }

    
});



