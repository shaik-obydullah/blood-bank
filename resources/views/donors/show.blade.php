@extends('dashboard.master')

@section('title', $donor->name . ' - Donor Details')
@section('page-title', 'Donor Details')
@section('page-subtitle', 'View donor information and medical history')

@section('content')
    <div class="dashboard-content">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Donor Details Container -->
        <div class="detail-container">
            <!-- Header -->
            <div class="detail-header">
                <div class="detail-title">
                    <h3><i class="fas fa-user mr-2"></i> Donor Details</h3>
                    <p class="detail-subtitle">Donor ID: #{{ $donor->id }}</p>
                </div>
                <div class="detail-actions">
                    <a href="{{ route('donors.edit', $donor->id) }}" class="btn btn-edit">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Donor
                    </a>
                    <a href="{{ route('donors.index') }}" class="btn btn-back">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Donor Profile Card -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <div class="avatar-circle">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name">{{ $donor->name }}</h2>
                        <div class="profile-badges">
                            <span class="badge badge-blood">
                                {{ $donor->bloodGroup->code ?? 'N/A' }}
                            </span>
                            <span class="badge {{ $eligibility['eligible'] ? 'badge-success' : 'badge-danger' }}">
                                <i class="fas {{ $eligibility['eligible'] ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ $eligibility['eligible'] ? 'Eligible for Donation' : 'Not Eligible' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Information Grid -->
                <div class="info-grid">
                    <!-- Personal Information -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="fas fa-user-circle mr-2"></i> Personal Information</h4>
                        </div>
                        <div class="section-body">
                            <div class="info-row">
                                <span class="info-label">Full Name:</span>
                                <span class="info-value">{{ $donor->name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Birthdate:</span>
                                <span class="info-value">
                                    {{ \Carbon\Carbon::parse($donor->birthdate)->format('F d, Y') }}
                                    ({{ \Carbon\Carbon::parse($donor->birthdate)->age }} years old)
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Country:</span>
                                <span class="info-value">{{ $donor->country }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="fas fa-heartbeat mr-2"></i> Medical Information</h4>
                        </div>
                        <div class="section-body">
                            <div class="medical-cards">
                                <div class="medical-card">
                                    <div class="medical-value">{{ $donor->bloodGroup->name ?? 'N/A' }}</div>
                                    <div class="medical-label">Blood Group</div>
                                </div>
                                <div class="medical-card">
                                    <div class="medical-value">{{ $donor->hemoglobin_level ?? 'N/A' }} g/dL</div>
                                    <div class="medical-label">Hemoglobin</div>
                                </div>
                                <div class="medical-card">
                                    <div class="medical-value">
                                        @if($donor->systolic && $donor->diastolic)
                                            {{ $donor->systolic }}/{{ $donor->diastolic }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="medical-label">Blood Pressure</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="fas fa-address-card mr-2"></i> Contact Information</h4>
                        </div>
                        <div class="section-body">
                            <div class="contact-grid">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="contact-details">
                                        <div class="contact-label">Mobile</div>
                                        <div class="contact-value">{{ $donor->mobile }}</div>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="contact-details">
                                        <div class="contact-label">Email</div>
                                        <div class="contact-value">{{ $donor->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="fas fa-map-marker-alt mr-2"></i> Address Information</h4>
                        </div>
                        <div class="section-body">
                            <div class="address-box">
                                @if($donor->address_line_1)
                                    <p>{{ $donor->address_line_1 }}</p>
                                @endif
                                @if($donor->address_line_2)
                                    <p>{{ $donor->address_line_2 }}</p>
                                @endif
                                <p><strong>{{ $donor->country }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Donation History -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="fas fa-history mr-2"></i> Donation History</h4>
                        </div>
                        <div class="section-body">
                            <div class="info-row">
                                <span class="info-label">Last Donation:</span>
                                <span class="info-value">
                                    @if($donor->last_donation_date)
                                        {{ \Carbon\Carbon::parse($donor->last_donation_date)->format('F d, Y') }}
                                        ({{ \Carbon\Carbon::parse($donor->last_donation_date)->diffForHumans() }})
                                    @else
                                        Never donated
                                    @endif
                                </span>
                            </div>
                            
                            <!-- Eligibility Reasons -->
                            @if(!$eligibility['eligible'] && !empty($eligibility['reasons']))
                                <div class="info-row">
                                    <span class="info-label">Not Eligible Because:</span>
                                    <span class="info-value text-danger">
                                        {{ implode(', ', $eligibility['reasons']) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection