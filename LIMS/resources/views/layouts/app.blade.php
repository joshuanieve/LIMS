<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'LIMS')</title>

    {{-- Boxicons --}}
    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="font">
    <div class="relative min-h-screen">

        {{-- BACKGROUND - UNDER --}}
        <div class="lims-bg-gradient absolute inset-0 z-0"></div>

        {{-- CONTENT - ABOVE --}}
        <div class="app-layout z-10 flex">

            {{-- SIDEBAR --}}
            <div
                id="limsSidebar"
                class="sidebar relative w-72 transition-all duration-300">
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
                            {{ request()->routeIs('requestform') ? 'lims-nav-link-active' : '' }}">

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
                            Request Form
                        </span>

                    </a>

                    <a
                        href="#"
                        class="lims-nav-link
                            {{ request()->routeIs('samples.*') ? 'lims-nav-link-active' : '' }}">

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
                                d="M9 3h6m-5 0v6l-5 9a2 2 0 001.74 3h10.52A2 2 0 0019 18l-5-9V3M8 15h8" />

                        </svg>

                        <span class="lims-nav-text sidebar-label">
                            Samples
                        </span>

                    </a>

                    <a
                        href="#"
                        class="lims-nav-link
                            {{ request()->routeIs('analysis.*') ? 'lims-nav-link-active' : '' }}">

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
                            Analysis
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
                </nav>
            </div>


            <div class="main-content relative flex-1 transition-all duration-300" id="header-container">
               <main class="main-content relative transition-all duration-300" id="header-container">
                    <header class="lims-header">
                        <div class="lims-header-left">
                            <span class="lims-header-eyebrow">
                                Laboratory Information Management System
                            </span>
                            <h1 class="lims-header-title">Dashboard</h1>
                        </div>

                        <div class="lims-header-right">
                            <button type="button" class="lims-header-icon">
                                <i class="bx bx-bell"></i>
                            </button>

                            <div class="lims-header-user">
                                <div class="lims-header-avatar">U</div>
                                <div class="lims-header-user-info">
                                    <span class="lims-header-user-name">
                                        Local User
                                    </span>
                                    <span class="lims-header-user-role">
                                        Laboratory User
                                    </span>
                                </div>
                            </div>
                        </div>
                    </header>
















                    <section class="page-content">
                        <div class="dashboard-content">
                            @yield('content')

                        </div>
                    </section>















                </main>
            </div>

        </div>
    </div>


    
</body>
</html>
