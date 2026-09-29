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
        'resources/css/components/datatable.css',
        'resources/js/app.js'
    ])
</head>

<body class="font">
    {{-- Loader --}}
    <div id="loadingOverlay" class="loading-overlay"><div class="loader"></div></div>

    <div class="relative min-h-screen">
        

        {{-- BACKGROUND - UNDER --}}
        <div class="lims-bg-gradient absolute inset-0 z-0"></div>

        {{-- CONTENT - ABOVE --}}
        <div class="app-layout z-10 flex">

            @include('layouts.sidebar')


            <div class="main-content relative flex-1 transition-all duration-300" id="header-container">
               <main class="main-content relative transition-all duration-300" id="header-container">
                    <header class="lims-header">
                        <div class="lims-header-left">
                            <span class="lims-header-eyebrow">
                                Laboratory Information Management System
                            </span>

                            <h1 class="lims-header-title">
                                @yield('PageTitle', 'Dashboard')
                            </h1>
                        </div>

                        <div class="lims-header-right">
                            {{-- NOTIFICATION --}}
                            <div class="lims-header-dropdown-wrapper">
                                <button
                                    type="button"
                                    id="notificationButton"
                                    class="lims-header-icon"
                                >
                                    <i class="bx bx-bell"></i>
                                    {{-- Notification indicator --}}
                                    <span class="lims-notification-badge">3</span>
                                </button>


                                {{-- NOTIFICATION DROPDOWN --}}
                                <div
                                    id="notificationDropdown"
                                    class="lims-header-dropdown lims-notification-dropdown hidden"
                                >
                                    <div class="lims-dropdown-header">
                                        <div>
                                            <h3>Notifications</h3>
                                            <span>Recent activity</span>
                                        </div>

                                        <button type="button">
                                            Mark all read
                                        </button>
                                    </div>

                                    <div class="lims-notification-list">

                                        <a href="#" class="lims-notification-item unread">
                                            <div class="lims-notification-icon">
                                                <i class="bx bx-file"></i>
                                            </div>

                                            <div class="lims-notification-content">
                                                <span class="lims-notification-title">
                                                    Request Received
                                                </span>

                                                <p>
                                                    A new laboratory request has been submitted.
                                                </p>

                                                <small>5 minutes ago</small>
                                            </div>

                                            <span class="lims-notification-dot"></span>
                                        </a>


                                        <a href="#" class="lims-notification-item">
                                            <div class="lims-notification-icon">
                                                <i class="bx bx-test-tube"></i>
                                            </div>

                                            <div class="lims-notification-content">
                                                <span class="lims-notification-title">
                                                    Sample Assigned
                                                </span>

                                                <p>
                                                    A laboratory sample has been assigned.
                                                </p>

                                                <small>1 hour ago</small>
                                            </div>
                                        </a>

                                    </div>

                                    <a href="#" class="lims-dropdown-footer">
                                        View all notifications
                                    </a>
                                </div>

                            </div>


                            {{-- USER --}}
                            <div class="lims-header-dropdown-wrapper">
                                <button
                                    type="button"
                                    id="userDropdownButton"
                                    class="lims-header-user"
                                >
                                    <div class="lims-header-avatar">U</div>
                                    <div class="lims-header-user-info">
                                        <span class="lims-header-user-name">
                                            {{ session('user_name') ?? 'NULL' }}
                                        </span>

                                        <span class="lims-header-user-role">
                                            {{ session('lims_role') ?? 'NULL' }}
                                        </span>
                                    </div>
                                    <i class="bx bx-chevron-down lims-user-chevron"></i>
                                </button>


                                {{-- USER DROPDOWN --}}
                                <div
                                    id="userDropdown"
                                    class="lims-header-dropdown lims-user-dropdown hidden"
                                >
                                    {{-- <div class="lims-user-dropdown-profile">
                                        <div class="lims-header-avatar">
                                            U
                                        </div>

                                        <div>
                                            <strong>Local User</strong>
                                            <span>Laboratory User</span>
                                        </div>
                                    </div>

                                    <div class="lims-user-dropdown-menu">
                                        <a href="#"><i class="bx bx-user"></i>My Profile</a>
                                        <a href="#"><i class="bx bx-cog"></i>Account Settings</a>
                                    </div> --}}


                                    <div class="lims-user-dropdown-logout">
                                        <form
                                            method="POST"
                                            action="{{ route('logout') }}"
                                        >
                                            @csrf

                                            <button type="submit">
                                                <i class="bx bx-log-out"></i>
                                                Sign Out
                                            </button>
                                        </form>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </header>

                    
                    


















                    <section class="page-content" id="pagebody">
                        <div class="dashboard-content">
                            @yield('content')

                        </div>
                    </section>















                </main>
            </div>

        </div>
    </div>

    @yield('modals')


    
</body>
</html>
