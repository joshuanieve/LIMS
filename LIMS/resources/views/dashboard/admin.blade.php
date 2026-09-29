
{{-- //////////////////////////////// WELCOME ////////////////////////////////////////// --}}
<div class="dashboard-panelheader">
    <div class="lims-welcome-card">
        <div class="lims-welcome-content">
            <p class="lims-welcome-label">
                Welcome Back
            </p>
            <h2 class="lims-welcome-title">
                Good day, User.
            </h2>
            <p class="lims-welcome-description">
                View laboratory requests, track samples, monitor analyses,
                and manage laboratory records.
            </p>
        </div>

        <a href="#" class="lims-request-button">
            <span>
                New Laboratory Request
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                />
            </svg>
        </a>
    </div>
</div>



{{-- //////////////////////////////// PANEL ////////////////////////////////////////// --}}
<div class="lims-stats-grid">
    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M9 12h6m-6 4h6M9 8h2m-5 13h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v15a2 2 0 002 2z"
                />
            </svg>
        </div>

        <p class="lims-stat-label">Total Requests</p>
        <p class="lims-stat-value">24</p>
        <p class="lims-stat-description">Laboratory service requests</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M9 3h6m-5 0v6l-5 9a2 2 0 001.74 3h10.52A2 2 0 0019 18l-5-9V3M8 15h8"
                />
            </svg>
        </div>

        <p class="lims-stat-label">Samples</p>
        <p class="lims-stat-value">58</p>
        <p class="lims-stat-description">Registered samples</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M3 3v18h18M7 16v-4m5 4V8m5 8v-7"
                />
            </svg>
        </div>

        <p class="lims-stat-label">Ongoing Analysis</p>
        <p class="lims-stat-value">12</p>
        <p class="lims-stat-description">Currently being processed</p>
    </div>

    <div class="lims-stat-card">
        <div class="lims-stat-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M5 13l4 4L19 7"
                />
            </svg>
        </div>

        <p class="lims-stat-label">Completed</p>
        <p class="lims-stat-value">38</p>
        <p class="lims-stat-description">Completed laboratory requests</p>
    </div>

</div>


{{-- //////////////////////////////// CONTENT 2 ////////////////////////////////////////// --}}
<div class="lims-dashboard-bottom">

    {{-- RECENT REQUESTS --}}
    <section class="lims-panel lims-recent-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Laboratory Requests</p>
                <h2 class="lims-panel-title">Recent Requests</h2>
            </div>

            <a href="#" class="lims-panel-link">View all</a>
        </div>


        <div class="lims-table-wrapper">
            <table class="lims-dashboard-table">
                <thead>
                    <tr>
                        <th>Request No.</th>
                        <th>Client</th>
                        <th>Samples</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>LIMS-2026-001</td>
                        <td>Juan Dela Cruz</td>
                        <td>3</td>
                        <td>Sep 01, 2026</td>
                        <td>
                            <span class="lims-status lims-status-processing">
                                Processing
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>LIMS-2026-002</td>
                        <td>Maria Santos</td>
                        <td>2</td>
                        <td>Aug 31, 2026</td>
                        <td>
                            <span class="lims-status lims-status-pending">
                                Pending
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>LIMS-2026-003</td>
                        <td>Pedro Reyes</td>
                        <td>4</td>
                        <td>Aug 30, 2026</td>
                        <td>
                            <span class="lims-status lims-status-completed">
                                Completed
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>LIMS-2026-003</td>
                        <td>Pedro Reyes</td>
                        <td>4</td>
                        <td>Aug 30, 2026</td>
                        <td>
                            <span class="lims-status lims-status-completed">
                                Completed
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>LIMS-2026-003</td>
                        <td>Pedro Reyes</td>
                        <td>4</td>
                        <td>Aug 30, 2026</td>
                        <td>
                            <span class="lims-status lims-status-completed">
                                Completed
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>LIMS-2026-003</td>
                        <td>Pedro Reyes</td>
                        <td>4</td>
                        <td>Aug 30, 2026</td>
                        <td>
                            <span class="lims-status lims-status-completed">
                                Completed
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>
    </section>


    {{-- QUICK ACTIONS --}}
    <section class="lims-panel lims-quick-panel">
        <div class="lims-panel-header">
            <div>
                <p class="lims-panel-eyebrow">Logs</p>
                <h2 class="lims-panel-title">Recent Activity</h2>
            </div>
        </div>

        <div class="lims-table-wrapper">
            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

            <div class="lims-activity-item">
                <p class="lims-activity-message">Sample has resolved the SRV-001779</p>
                <p class="lims-activity-time">2026-09-02 09:26:15</p>
            </div>

        </div>
    </section>
</div>

<div class="lims-content-additional">
    <div class="lims-add-content">asdasd</div>
    <div class="lims-add-content">asdasd</div>
    <div class="lims-add-content">asdasd</div>
</div>