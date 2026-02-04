@extends('website.master')
@section('title', 'BloodBank')
@section('home')
    <section class="dashboard">
        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <i class="fas fa-user-friends"></i>
                <h3>{{ $totalDonors }}</h3>
                <p>Registered Donors</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-check"></i>
                <h3>{{ $activeAppointments }}</h3>
                <p>Total Appointments</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-tint"></i>
                <h3>{{ $bloodQuantity }}</h3>
                <p>ML Collected</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>{{ $totalGroup }}</h3>
                <p>Blood Types</p>
            </div>
        </div>

        <!-- Action Cards - Two Large Cards -->
        <div class="dashboard-actions-large">
            <!-- Register Donor Card -->
            <div class="action-card-large primary">
                <div class="action-content-large">
                    <div class="action-icon-large primary">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-text">
                        <h2>Register New Donor</h2>
                        <p class="description">Add new donors to the system with complete personal, medical, and contact
                            information. All data is securely stored and easily accessible.</p>
                        <a href="/donor-registration" class="btn btn-card-primary">
                            <i class="fas fa-user-plus"></i> Register Now
                        </a>
                    </div>
                </div>
            </div>

            <!-- Schedule Appointment Card -->
            <div class="action-card-large accent">
                <div class="action-content-large">
                    <div class="action-icon-large accent">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="action-text">
                        <h2>Schedule Donation</h2>
                        <p class="description">Book blood donation appointments quickly. Select from available time slots
                            and locations. Automated reminders ensure no-shows are minimized.</p>
                        <div class="action-buttons">
                            <a href="{{ url('/donor-appointment') }}" class="btn btn-card-accent">
                                <i class="fas fa-calendar-check"></i> Schedule Now
                            </a>
                            <a href="{{ url('/donor-appointment') }}" class="btn btn-outline-white">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Add hover effect to stat cards
                const statCards = document.querySelectorAll(".stat-card");
                statCards.forEach((card) => {
                    card.addEventListener("mouseenter", function () {
                        this.style.transform = "translateY(-5px)";
                    });
                    card.addEventListener("mouseleave", function () {
                        this.style.transform = "translateY(0)";
                    });
                });

                // Add animation to large cards
                const largeCards = document.querySelectorAll(".action-card-large");
                largeCards.forEach((card, index) => {
                    // Add entrance animation
                    card.style.opacity = "0";
                    card.style.transform = "translateY(20px)";

                    setTimeout(() => {
                        card.style.transition = "all 0.5s ease";
                        card.style.opacity = "1";
                        card.style.transform = "translateY(0)";
                    }, index * 200);
                });
            });
        </script>
    @endpush
@endsection