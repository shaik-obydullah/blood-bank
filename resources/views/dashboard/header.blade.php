<header class="header">
    <div class="header-left">
        <h2>@yield('page-title', 'Dashboard Overview')</h2>
        <p>@yield('page-subtitle', 'Welcome to BloodBank Management System')</p>
    </div>
    <a href="{{ url('/admin-logout') }}" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</header>
