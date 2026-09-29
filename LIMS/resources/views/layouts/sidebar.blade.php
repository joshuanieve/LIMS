
{{-- SIDEBAR --}}
<div
    id="limsSidebar"
    class="sidebar relative w-72 transition-all duration-300">
    <div class="stickysidebar">
        <div> {{--  SIDEBAR CONTAINER --}}
            <div class="lims-sidebar-header" id="sidebar_content1">
                <div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center
                            rounded-xl
                            border border-[#79C7FF]/15
                            bg-[#0B5FA5]/20
                            text-[#79C7FF]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M9 3h6m-5 0v6l-5 9a2 2 0 001.74 3h10.52A2 2 0 0019 18l-5-9V3M8 15h8" />

                        </svg>

                    </div>
                </div>


                {{-- BRAND TEXT --}}
                <div
                    id="limsBrandText"
                    class="lims-brand-container transition-opacity duration-200">

                    <p class="lims-brand-title">
                        LIMS
                    </p>

                    <p class="lims-brand-subtitle">
                        Laboratory Information Management System
                    </p>

                </div>

            </div>
        </div>

        <button
            id="sidebarToggle"
            type="button"
            class="absolute -right-3 top-8 z-50
                flex h-7 w-7 items-center justify-center
                rounded-full
                border border-[#2589D8]/30
                bg-[#0A1A2F]
                text-[#79C7FF]
                shadow-lg
                transition-all duration-300
                hover:bg-[#0B5FA5]
                hover:text-white">

            <svg
                id="sidebarToggleIcon"
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform duration-300"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />

            </svg>
        </button>

        @include('layouts.sidebarroles')
    </div>
</div>
