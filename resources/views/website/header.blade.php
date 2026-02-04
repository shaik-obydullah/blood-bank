<header>
    <div class="container">
        <div class="header-content">
            <!-- Logo -->
            <div class="logo">
                <i class="fas fa-tint"></i>
                <a href="{{ url('/') }}">
                    <h1>BloodBank</h1>
                </a>
            </div>

            <!-- Search Blood Box -->
            <div class="search-container">
                <form action="{{ url('/search-blood') }}" method="GET" id="bloodSearchForm">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="blood_type" id="bloodSearch" placeholder="Search blood type..."
                            autocomplete="off" />
                    </div>
                </form>
            </div>

            <!-- CTA Button -->
            <button class="btn-become-donor" onclick="becomeDonor()">
                <i class="fas fa-heart-pulse"></i> Become a Donor
            </button>
            @auth('donor')
                <!-- Donor Panel -->
                <div class="donor-panel" onclick="toggleDonorPanel()">
                    <i class="fas fa-user-injured"></i>
                    <div class="panel-text">
                        <span class="panel-title">Donor Panel</span>
                        <span class="panel-subtitle">Manage donations</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
            @else


                <a href="{{ url('/donor-login') }}" class="donor-login-btn elegant-btn">
                    <span class="btn-text">Donor Login</span>
                    <i class="fas fa-arrow-right"></i>
                </a>

            @endauth


        </div>


        @auth('donor')
            <!-- Donor Panel Dropdown -->
            <div class="donor-panel-dropdown" id="donorPanelDropdown">
                <a href="/donor-dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Donor Dashboard</span>
                </a>
                <a href="/donor-appointment">
                    <i class="fas fa-calendar-check"></i>
                    <span>My Appointments</span>
                </a>
                <a href="/donor-history">
                    <i class="fas fa-history"></i>
                    <span>Donation History</span>
                </a>
                <a href="/donor-profile">
                    <i class="fas fa-user-circle"></i>
                    <span>My Profile</span>
                </a>
                <a href="/donor-logout" class="logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        @endauth
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bloodSearch = document.getElementById('bloodSearch');
        const bloodSearchForm = document.getElementById('bloodSearchForm');

        // Search on Enter key
        bloodSearch.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performBloodSearch();
            }
        });

        // Simple placeholder change on focus
        bloodSearch.addEventListener('focus', function () {
            this.setAttribute('placeholder', 'Type A+, B-, O+, etc. and press Enter');
        });

        bloodSearch.addEventListener('blur', function () {
            this.setAttribute('placeholder', 'Search blood type...');
        });
    });

    // Toggle Donor Panel
    function toggleDonorPanel() {
        const donorPanel = document.querySelector('.donor-panel');
        const dropdown = document.getElementById('donorPanelDropdown');

        donorPanel.classList.toggle('active');
        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        const donorPanel = document.querySelector('.donor-panel');
        const dropdown = document.getElementById('donorPanelDropdown');

        if (!e.target.closest('.donor-panel') && !e.target.closest('.donor-panel-dropdown')) {
            donorPanel.classList.remove('active');
            dropdown.classList.remove('show');
        }
    });

    function performBloodSearch() {
        const bloodType = document.getElementById('bloodSearch').value.trim().toUpperCase();
        const validBloodTypes = @json($all_blood_groups);

        if (bloodType && validBloodTypes.includes(bloodType)) {
            // Show loading state
            const searchBox = document.querySelector('.search-box');
            const searchIcon = document.querySelector('.search-icon');

            searchBox.style.backgroundColor = '#e8f5e9';
            searchIcon.classList.remove('fa-search');
            searchIcon.classList.add('fa-spinner', 'fa-spin');

            // Submit the form
            document.getElementById('bloodSearchForm').submit();
        } else if (bloodType) {
            // Invalid blood type
            const searchBox = document.querySelector('.search-box');
            searchBox.style.backgroundColor = '#ffebee';

            setTimeout(() => {
                searchBox.style.backgroundColor = '';
            }, 1000);

            alert('Please enter a valid blood type (A+, A-, B+, B-, AB+, AB-, O+, O-)');
            document.getElementById('bloodSearch').focus();
        }
    }

    function becomeDonor() {
        window.location.href = '/donor-registration';
    }

    // Keyboard shortcut for search
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('bloodSearch').focus();
        }

        // Escape to close dropdown
        if (e.key === 'Escape') {
            document.querySelector('.donor-panel').classList.remove('active');
            document.getElementById('donorPanelDropdown').classList.remove('show');
        }
    });
</script>