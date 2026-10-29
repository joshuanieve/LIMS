import Swal from 'sweetalert2';
window.Swal = Swal;

// THIS IS SIDEBAR SHOW AND COLLAPSE
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('limsSidebar');
    const toggleButton = document.getElementById('sidebarToggle');
    const toggleIcon = document.getElementById('sidebarToggleIcon');
    const sidebarContent1 = document.getElementById('sidebar_content1');
    const sidebarContent2 = document.getElementById('sidebar_content2');
    const sidebarContent3 = document.getElementById('sidebar_content3');

    let sidebarOpen = true;

    toggleButton.addEventListener('click', function () {
        sidebarOpen = !sidebarOpen;

        if (sidebarOpen) {
            sidebar.classList.remove('w-2');
            sidebar.classList.add('w-72');

            sidebar.style.paddingInline = 'calc(var(--spacing) * 5)';

            sidebarContent1.style.display = '';
            sidebarContent2.style.display = '';
            if (sidebarContent3) sidebarContent3.style.display = '';

            toggleIcon.classList.remove('rotate-180');

        } else {
            sidebar.classList.remove('w-72');
            sidebar.classList.add('w-2');

            sidebar.style.paddingInline = '0';

            sidebarContent1.style.display = 'none';
            sidebarContent2.style.display = 'none';
            if (sidebarContent3) sidebarContent3.style.display = 'none';

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

// Resolves to true/false depending on whether the user confirmed.
window.swal_confirm = function (title, message, confirmButtonText = 'Yes', cancelButtonText = 'No', icon = 'question') {
    return Swal.fire({
        icon: icon,
        title: title,
        text: message,
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        reverseButtons: true
    }).then((result) => result.isConfirmed);
};

// Small, non-blocking, auto-dismissing notification - top-right corner.
window.swal_toast = function (title, icon = 'success') {
    return Swal.fire({
        icon: icon,
        title: title,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
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








// Matches .lims-loader-fade-out's animation-duration in lims-loader.css.
const LOADER_FADE_OUT_MS = 200;

window.showLoading = function (message = 'Processing request…') {
    const loader = document.getElementById('limsLoader');
    if (!loader) return;

    document.getElementById('limsLoaderText').textContent = message;
    loader.classList.add('is-visible');
    loader.setAttribute('aria-hidden', 'false');

    loader.classList.remove('lims-loader-fade-out', 'lims-loader-fade-in');
    void loader.offsetWidth;
    loader.classList.add('lims-loader-fade-in');
};

window.hideLoading = function () {
    const loader = document.getElementById('limsLoader');
    if (!loader) return;

    loader.classList.remove('lims-loader-fade-in');
    loader.classList.add('lims-loader-fade-out');
    loader.setAttribute('aria-hidden', 'true');

    setTimeout(() => {
        loader.classList.remove('is-visible', 'lims-loader-fade-out');
    }, LOADER_FADE_OUT_MS);
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



// Matches .lims-fade-out's animation-duration in app.css.
const MODAL_FADE_OUT_MS = 200;

window.CloseModal = function CloseModal(modalID) {
    const modal = document.getElementById(modalID);
    if (!modal) return;

    modal.classList.remove('lims-fade-in');
    modal.classList.add('lims-fade-out');

    setTimeout(() => {
        modal.classList.remove('flex', 'lims-fade-out');
        modal.classList.add('hidden');
    }, MODAL_FADE_OUT_MS);
}

window.OpenModal =function OpenModal(modalID) {
    const modal = document.getElementById(modalID);
    if (!modal) {
        console.error('Modal not found in the DOM.');
        return;
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Re-trigger the fade every open (remove, force reflow, re-add) - same
    // pattern used for DataTable redraws/Logs panel selection elsewhere.
    modal.classList.remove('lims-fade-in');
    void modal.offsetWidth;
    modal.classList.add('lims-fade-in');
}