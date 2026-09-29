import Swal from 'sweetalert2';
window.Swal = Swal;

// THIS IS SIDEBAR SHOW AND COLLAPSE
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('limsSidebar');
    const toggleButton = document.getElementById('sidebarToggle');
    const toggleIcon = document.getElementById('sidebarToggleIcon');
    const sidebarContent1 = document.getElementById('sidebar_content1');
    const sidebarContent2 = document.getElementById('sidebar_content2');

    let sidebarOpen = true;

    toggleButton.addEventListener('click', function () {
        sidebarOpen = !sidebarOpen;

        if (sidebarOpen) {
            sidebar.classList.remove('w-2');
            sidebar.classList.add('w-72');

            sidebar.style.paddingInline = 'calc(var(--spacing) * 5)';

            sidebarContent1.style.display = '';
            sidebarContent2.style.display = '';

            toggleIcon.classList.remove('rotate-180');

        } else {
            sidebar.classList.remove('w-72');
            sidebar.classList.add('w-2');

            sidebar.style.paddingInline = '0';

            sidebarContent1.style.display = 'none';
            sidebarContent2.style.display = 'none';

            toggleIcon.classList.add('rotate-180');
        }

        // Wait until layout updates, then resize Vanta
        setTimeout(() => {
            if (vantaEffect) {
                vantaEffect.resize();
            }
        }, 250);
    });
});

window.swal_success = function (title, message) {
    return Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        confirmButtonText: 'OK'
    });
};

window.swal_error = function (title, message) {
    return Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonText: 'OK'
    });
};



const requestsDropdownButton = document.getElementById('requestsDropdownButton');
const requestsDropdown = document.getElementById('requestsDropdown');
const requestsDropdownArrow = document.getElementById('requestsDropdownArrow');

if (requestsDropdownButton) {
    requestsDropdownButton.addEventListener('click', function () {
        // DROPDOWN
        requestsDropdown.classList.toggle('max-h-0');
        requestsDropdown.classList.toggle('max-h-40');
        requestsDropdown.classList.toggle('opacity-0');
        requestsDropdown.classList.toggle('opacity-100');

        // ARROW
        requestsDropdownArrow.classList.toggle('rotate-180');
    });
}








window.showLoading = function () {
    const overlay = document.getElementById('loadingOverlay');

    if (!overlay) {
        console.log('loadingOverlay not found');
        return;
    }

    overlay.classList.add('show');
};

window.hideLoading = function () {
    const overlay = document.getElementById('loadingOverlay');

    if (!overlay) {
        console.log('loadingOverlay not found');
        return;
    }

    overlay.classList.remove('show');
};


window.formatDate = function (dateString) {
    if (!dateString) {
        return "-";
    }
    const date = new Date(dateString);
    return date.toLocaleDateString("en-PH", {
        year: "numeric",
        month: "short",
        day: "numeric"
    });
}

window.setText = function setText(id, value) {
    const element = document.getElementById(id);

    if (!element) return;

    element.textContent = value ?? '-';
}




// NOTIFICATIONS AND USER
const notificationButton =
    document.getElementById('notificationButton');

const notificationDropdown =
    document.getElementById('notificationDropdown');

const userDropdownButton =
    document.getElementById('userDropdownButton');

const userDropdown =
    document.getElementById('userDropdown');


notificationButton?.addEventListener('click', (event) => {
    event.stopPropagation();

    userDropdown?.classList.add('hidden');

    notificationDropdown?.classList.toggle('hidden');
});


userDropdownButton?.addEventListener('click', (event) => {
    event.stopPropagation();

    notificationDropdown?.classList.add('hidden');

    userDropdown?.classList.toggle('hidden');
});


notificationDropdown?.addEventListener('click', (event) => {
    event.stopPropagation();
});


userDropdown?.addEventListener('click', (event) => {
    event.stopPropagation();
});


document.addEventListener('click', () => {
    notificationDropdown?.classList.add('hidden');
    userDropdown?.classList.add('hidden');
});