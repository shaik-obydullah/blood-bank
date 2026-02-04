<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BloodBank Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">
</head>

<body>
    <!-- Sidebar Navigation -->
    <nav class="sidebar">
        <div class="logo">
            <i class="fas fa-tint"></i>
            <h1>BloodBank</h1>
        </div>

        <div class="nav-menu">
            <ul>
                <!-- Dashboard -->
                <li>
                    <a href="{{ url('/admin-dashboard') }}"
                        class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Blood Groups -->
                <li>
                    <a href="{{ url('/blood-groups') }}"
                        class="{{ request()->routeIs('blood-groups.*') ? 'active' : '' }}">
                        <i class="fas fa-heartbeat"></i>
                        <span>Blood Groups</span>
                    </a>
                </li>

                <!-- Donors -->
                <li>
                    <a href="{{ url('/donors') }}" class="{{ request()->routeIs('donors.*') ? 'active' : '' }}">
                        <i class="fas fa-user-injured"></i>
                        <span>Donors</span>
                    </a>
                </li>

                <!-- Doctors -->
                <li>
                    <a href="{{ url('/doctors') }}" class="{{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                        <i class="fas fa-user-md"></i>
                        <span>Doctors</span>
                    </a>
                </li>

                <!-- Appointments -->
                <li>
                    <a href="{{ url('/appointments') }}"
                        class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Appointments</span>
                    </a>
                </li>

                <!-- Patients -->
                <li>
                    <a href="{{ url('/patients') }}" class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="fas fa-procedures"></i>
                        <span>Patients</span>
                    </a>
                </li>

                <!-- Blood Inventory -->
                <li>
                    <a href="{{ url('/blood-inventory') }}" class="{{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                        <i class="fas fa-vial"></i>
                        <span>Blood Bank</span>
                    </a>
                </li>

                <!-- Blood Distributions -->
                <li>
                    <a href="{{ url('/blood-distributions') }}"
                        class="{{ request()->routeIs('blood-distributions.*') ? 'active' : '' }}">
                        <i class="fas fa-syringe"></i>
                        <span>Blood Distributions</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        @include('dashboard.header')

        <!-- Content Area -->
        @yield('content')

        <!-- Footer -->
        @include('dashboard.footer')
    </main>

    <script>
        // Sidebar navigation active state
        document.addEventListener('DOMContentLoaded', function () {
            // Add click handlers for mobile menu
            const navLinks = document.querySelectorAll('.nav-menu a');
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    // Remove active class from all links
                    navLinks.forEach(item => {
                        item.classList.remove('active');
                    });
                    // Add active class to clicked link
                    this.classList.add('active');
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>