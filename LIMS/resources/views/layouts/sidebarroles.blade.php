<nav class="lims-sidebar-nav" id="sidebar_content2">
    <a
        href="{{ route('dashboard') }}"
        class="lims-nav-link
            {{ request()->routeIs('dashboard') ? 'lims-nav-link-active' : '' }}">

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="lims-nav-icon"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10" />

        </svg>

        <span class="lims-nav-text sidebar-label">
            Dashboard
        </span>

    </a>

    <a
        href="{{ route('requestform.index') }}"
        class="lims-nav-link
            {{ request()->routeIs('requestform.*') ? 'lims-nav-link-active' : '' }}">

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="lims-nav-icon"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M9 12h6m-6 4h6M9 8h2m-5 13h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v15a2 2 0 002 2z" />

        </svg>

        <span class="lims-nav-text sidebar-label">
            Request for Analysis
        </span>
    </a>

    <a
        href="{{ route('requestsample.index') }}"
        class="lims-nav-link
            {{ request()->routeIs('requestsample.*') ? 'lims-nav-link-active' : '' }}">

        <svg
                xmlns="http://www.w3.org/2000/svg"
                class="lims-nav-icon"
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

        <span class="lims-nav-text sidebar-label">
            Requests Sample
        </span>
    </a>



    <a
        href="{{ route('jobrouting.index') }}"
        class="lims-nav-link
            {{ request()->routeIs('jobrouting.index') ? 'lims-nav-link-active' : '' }}">

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="lims-nav-icon"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M3 3v18h18M7 16v-4m5 4V8m5 8v-7" />

        </svg>

        <span class="lims-nav-text sidebar-label">
            Job Routing
        </span>
    </a>

    <a
        href="#"
        class="lims-nav-link
            {{ request()->routeIs('resultencoding.*') ? 'lims-nav-link-active' : '' }}">

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="lims-nav-icon"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M4 6h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z
                M6 10h1
                M10 10h1
                M14 10h1
                M18 10h1
                M6 14h1
                M10 14h1
                M14 14h1
                M18 14h1
                M8 17h8" />

        </svg>

        <span class="lims-nav-text sidebar-label">
            Result for Encoding
        </span>
    </a>

    <a
        href="#"
        class="lims-nav-link
            {{ request()->routeIs('laboratory-records.*') ? 'lims-nav-link-active' : '' }}">

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="lims-nav-icon"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M4 6h16M4 12h16M4 18h10" />

        </svg>

        <span class="lims-nav-text sidebar-label">
            Laboratory Records
        </span>
    </a>

    <a
        href="#"
        class="lims-nav-link
            {{ request()->routeIs('logs') ? 'lims-nav-link-active' : '' }}">

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="lims-nav-icon"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M9 12h6m-6 4h6M9 8h6M5 4h14a2 2 0 012 2v14H3V6a2 2 0 012-2z" />

        </svg>

        <span class="lims-nav-text sidebar-label">
            Logs
        </span>
    </a>
</nav>


















        {{-- <div class="lims-nav-dropdown cursor-pointer">
                <button
                    type="button"
                    id="requestsDropdownButton"
                    class="lims-nav-link w-full cursor-pointer
                        {{ request()->routeIs('requests.*') ? 'lims-nav-link-active' : '' }}"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="lims-nav-icon"
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

                    <span class="lims-nav-text sidebar-label flex-1 text-left">Request Samples</span>


                    <svg
                        id="requestsDropdownArrow"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transition-transform duration-200 sidebar-label"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>

                </button>


                <div
                    id="requestsDropdown"
                    class="max-h-0 overflow-hidden opacity-0
                        transition-all duration-300 ease-in-out cursor-pointer"
                >

                    <a
                        href="{{ route('requestpending.index') }}"
                        class="lims-nav-sub-link
                            {{ request()->routeIs('requests.pending') ? 'lims-nav-sub-link-active' : '' }}"
                    >
                        Pending / Ongoing
                    </a>

                    <a
                        class="lims-nav-sub-link
                            {{ request()->routeIs('requests.completed') ? 'lims-nav-sub-link-active' : '' }}"
                    >
                        Completed / Released
                    </a>
                </div>
            </div> --}}