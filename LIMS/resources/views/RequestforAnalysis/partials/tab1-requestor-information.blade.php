{{-- Requestor Information --}}
<section class="lims-form-card">
    <div class="lims-form-card-header">
        <div>
            <p class="lims-form-eyebrow">Request Information</p>
            <h2 class="lims-form-card-title">Requestor Information</h2>
            <p class="lims-form-card-description">Provide the contact information of the requesting client.</p>
        </div>
        <div class="lims-form-step">01</div>
    </div>


    <div class="lims-form-grid lims-form-grid-3">
        <div class="lims-field">
            <label class="lims-label">Last Name</label>
            <input
                type="text"
                name="LastName"
                class="lims-input"
                placeholder="Enter last name"
                value="{{ session('lname') ?? 'NULL' }}"
                disabled
            >
        </div>

        <div class="lims-field">
            <label class="lims-label">First Name</label>
            <input
                type="text"
                name="FirstName"
                class="lims-input"
                placeholder="Enter first name"
                value="{{ session('fname') ?? 'NULL' }}"
                disabled
            >
        </div>

        <div class="lims-field">
            <label class="lims-label">Middle Initial</label>
            <input
                type="text"
                name="MiddleInitial"
                class="lims-input"
                placeholder="M.I."
                value="{{ session('mname') ? strtoupper(substr(session('mname'), 0, 1)) . '.' : '' }}"
                disabled
            >
        </div>
    </div>


    <div class="lims-form-grid lims-form-grid-2">
        <div class="lims-field">
            <label class="lims-label">Email Address</label>
            <input
                type="email"
                name="Email"
                class="lims-input"
                placeholder="example@email.com"
                value="{{ session('user_email') ?? 'NULL' }}"
                disabled
            >
        </div>

        <div class="lims-field">
            <label class="lims-label">Division / Section</label>
            <input
                type="text"
                class="lims-input"
                placeholder="Enter division or section"
                value="{{ session('division') ?? 'NULL' }}"
                disabled
            >

            <input
                type="hidden"
                name="DivisionSection"
                class="lims-input"
                placeholder="Enter division or section"
                value="{{ session('division_id') ?? 'NULL' }}"
                disabled
            >
        </div>
    </div>
</section>