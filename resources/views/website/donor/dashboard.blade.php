@extends('website.master')
@section('title', 'Donor Dashboard')
@section('home')
    <div class="dashboard-content">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Donor Dashboard</h1>
                <p class="dashboard-subtitle">Welcome to your donor panel</p>
            </div>
        </div>

        <!-- Dashboard Navigation -->
        <div class="dashboard-navigation">
            <a href="/donor-appointment" class="dashboard-nav-card nav-card-appointments">
                <div class="nav-card-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="nav-card-content">
                    <h2>My Appointments</h2>
                    <p>View and manage your donation appointments</p>
                </div>
            </a>

            <a href="/donor-history" class="dashboard-nav-card nav-card-history">
                <div class="nav-card-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="nav-card-content">
                    <h2>Donation History</h2>
                    <p>Track your previous donations and impact</p>
                </div>
            </a>

            <a href="/donor-profile" class="dashboard-nav-card nav-card-profile">
                <div class="nav-card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="nav-card-content">
                    <h2>My Profile</h2>
                    <p>Your personal information</p>
                </div>
            </a>
        </div>
    </div>
@endsection