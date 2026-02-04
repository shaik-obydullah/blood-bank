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
                    <h3>Donor Details</h3>
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

            <!-- Donor Details Body -->
            <div class="detail-body">
                <div class="detail-row">
                    <!-- Left Column: Personal & Contact -->
                    <div class="detail-column">
                        <!-- Personal Information Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ $donor->name }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Birthdate</div>
                                    <div class="info-value">
                                        {{ \Carbon\Carbon::parse($donor->birthdate)->format('M d, Y') }}
                                        ({{ \Carbon\Carbon::parse($donor->birthdate)->age }} years old)
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Country</div>
                                    <div class="info-value">{{ $donor->country }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Contact Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fas fa-phone"></i> Mobile
                                    </div>
                                    <div class="info-value">{{ $donor->mobile }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fas fa-envelope"></i> Email
                                    </div>
                                    <div class="info-value">{{ $donor->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Medical & Status -->
                    <div class="detail-column">
                        <!-- Medical Information Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Medical Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="info-label">Blood Group</div>
                                    <div class="info-value">
                                        <span class="blood-group">
                                            {{ $donor->bloodGroup->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Hemoglobin Level</div>
                                    <div class="info-value">
                                        {{ $donor->hemoglobin_level ?? 'N/A' }} g/dL
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Blood Pressure</div>
                                    <div class="info-value">
                                        @if($donor->systolic && $donor->diastolic)
                                            {{ $donor->systolic }}/{{ $donor->diastolic }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Donor Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="status-display">
                                    @if($eligibility['eligible'])
                                        <div class="status-badge status-active">
                                            <i class="fas fa-check-circle"></i>
                                            Eligible for Donation
                                        </div>
                                    @else
                                        <div class="status-badge status-inactive">
                                            <i class="fas fa-times-circle"></i>
                                            Not Eligible
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Donation History -->
                                <div class="info-row mt-3">
                                    <div class="info-label">Last Donation</div>
                                    <div class="info-value">
                                        @if($donor->last_donation_date)
                                            {{ \Carbon\Carbon::parse($donor->last_donation_date)->format('M d, Y') }}
                                            ({{ \Carbon\Carbon::parse($donor->last_donation_date)->diffForHumans() }})
                                        @else
                                            Never donated
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Card (Full Width) -->
                <div class="card">
                    <div class="card-header">
                        <h5>Address Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Address Line 1</div>
                            <div class="info-value">{{ $donor->address_line_1 ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Address Line 2</div>
                            <div class="info-value">{{ $donor->address_line_2 ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Country</div>
                            <div class="info-value">{{ $donor->country }}</div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card danger-card">
                    <div class="card-header">
                        <h5>Danger Zone</h5>
                    </div>
                    <div class="card-body">
                        <p class="alert alert-warning mb-3">
                            Once you delete this donor, there is no going back. Please be certain.
                        </p>
                        <form action="{{ route('donors.destroy', $donor->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="btn btn-danger"
                                    onclick="if(confirm('Are you sure you want to delete donor {{ $donor->name }}? This action cannot be undone.')) { this.closest('form').submit(); }">
                                <i class="fas fa-trash mr-2"></i>
                                Delete Donor
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection