import Swal from 'sweetalert2';

window.Swal = Swal;

// THIS IS SIDEBAR SHOW AND COLLAPSE
document.addEventListener('DOMContentLoaded', function () {

const sidebar = document.getElementById('limsSidebar');
const toggleButton = document.getElementById('sidebarToggle');
const toggleIcon = document.getElementById('sidebarToggleIcon');

const sidebarContent1 = document.getElementById('sidebar_content1');
const sidebarContent2 = document.getElementById('sidebar_content2');

const content_panel = document.getElementById('header-container');

let sidebarOpen = true;

    toggleButton.addEventListener('click', function () {

        sidebarOpen = !sidebarOpen;

        if (sidebarOpen) {

            // OPEN
            sidebar.classList.remove('w-2');
            sidebar.classList.add('w-72');

            sidebar.style.paddingInline = 'calc(var(--spacing) * 5)';

            sidebarContent1.style.display = '';
            sidebarContent2.style.display = '';

            toggleIcon.classList.remove('rotate-180');

        } else {

            // CLOSE
            sidebar.classList.remove('w-72');
            sidebar.classList.add('w-2');

            sidebar.style.paddingInline = '0';

            sidebarContent1.style.display = 'none';
            sidebarContent2.style.display = 'none';

            toggleIcon.classList.add('rotate-180');
        }

    });

});

window.swal_error = swal_error;
function swal_error(title, message) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonText: 'OK'
    });
}