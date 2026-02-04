@extends('website.master')
@section('title', 'My Profile')
@section('home')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="profile-header">
            <div class="profile-title">
                <h1>My Profile</h1>
                <p>Your personal information and donor details</p>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="profile-section">
            <div class="section-header">
                <h2>Basic Information</h2>
            </div>

            <div class="profile-table-container">
                <table class="profile-data-table">
                    <tbody>
                        <tr>
                            <td class="field-label">Full Name</td>
                            <td>{{ $donor->name ?? 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label">Email Address</td>
                            <td>{{ $donor->email ?? 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label">Mobile Number</td>
                            <td>{{ $donor->mobile ?? 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label">Date of Birth</td>
                            <td>
                                {{ $donor->birthdate ? $donor->birthdate->format('M d, Y') : 'Not Provided' }}
                                @if($donor->age)
                                    <span class="age-badge">({{ $donor->age }} years)</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="profile-section">
            <div class="section-header">
                <h2>Medical Information</h2>
            </div>

            <div class="profile-table-container">
                <table class="profile-data-table">
                    <tbody>
                        <tr>
                            <td class="field-label">Blood Type</td>
                            <td>
                                @if($donor->bloodGroup)
                                    <span class="blood-badge">{{ $donor->bloodGroup->code }}</span>
                                @else
                                    <span class="not-set">Not Set</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">Hemoglobin Level</td>
                            <td>
                                {{ $donor->hemoglobin_level ?? 'Not Recorded' }}
                                @if($donor->hemoglobin_level)
                                    <span class="unit-label">g/dL</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">Blood Pressure</td>
                            <td>{{ $donor->blood_pressure ?? 'Not Recorded' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label">Last Donation</td>
                            <td>{{ $donor->last_donation_date ? $donor->last_donation_date->format('M d, Y') : 'Never' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Address Information -->
        <div class="profile-section">
            <div class="section-header">
                <h2>Address Information</h2>
            </div>

            <div class="profile-table-container">
                <table class="profile-data-table">
                    <tbody>
                        <tr>
                            <td class="field-label">Address Line 1</td>
                            <td>{{ $donor->address_line_1 ?? 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label">Address Line 2</td>
                            <td>{{ $donor->address_line_2 ?? 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label">Country</td>
                            <td>{{ $donor->country ?? 'Not Provided' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Eligibility Status -->
        @if($donor && method_exists($donor, 'getEligibilityStatus'))
            @php
                $eligibility = $donor->getEligibilityStatus();
            @endphp
            <div class="eligibility-section">
                <div class="section-header eligibility-header">
                    <div class="eligibility-title">
                        <i class="fas fa-stethoscope eligibility-icon {{ $eligibility['eligible'] ? 'eligible' : 'not-eligible' }}"></i>
                        <h2>Donor Eligibility Status</h2>
                    </div>
                    <div class="eligibility-status">
                        @if($eligibility['eligible'])
                            <span class="eligibility-badge eligible-badge">
                                <i class="fas fa-check"></i> Eligible
                            </span>
                        @else
                            <span class="eligibility-badge not-eligible-badge">
                                <i class="fas fa-times"></i> Not Eligible
                            </span>
                        @endif
                    </div>
                </div>

                <div class="eligibility-content">
                    @if($eligibility['eligible'])
                        <div class="eligibility-card eligibility-success-card">
                            <div class="eligibility-card-content">
                                <div class="eligibility-icon-container">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="eligibility-message">
                                    <h3 class="eligibility-title-success">You are eligible to donate blood!</h3>
                                    <p class="eligibility-description">
                                        Your medical profile meets all requirements for blood donation. You can schedule your next appointment.
                                    </p>
                                    <div class="eligibility-actions">
                                        <div class="action-item">
                                            <i class="fas fa-heart"></i>
                                            <span>Ready to save lives</span>
                                        </div>
                                        <div class="action-divider"></div>
                                        <div class="action-item">
                                            <i class="far fa-calendar-check"></i>
                                            <span>Schedule your donation</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="eligibility-card eligibility-warning-card">
                            <div class="eligibility-card-header">
                                <div class="eligibility-icon-container">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="eligibility-message">
                                    <h3 class="eligibility-title-warning">You are currently not eligible to donate</h3>
                                    <p class="eligibility-description">
                                        Please review the following eligibility issues and take appropriate action:
                                    </p>
                                </div>
                            </div>

                            @if(!empty($eligibility['reasons']))
                                <div class="eligibility-issues">
                                    <div class="issues-header">
                                        <div class="issues-icon-container">
                                            <i class="fas fa-list-ul"></i>
                                        </div>
                                        <h4 class="issues-title">Eligibility Issues ({{ count($eligibility['reasons']) }})</h4>
                                    </div>

                                    <ul class="issues-list">
                                        @foreach($eligibility['reasons'] as $index => $reason)
                                            <li class="issue-item">
                                                <div class="issue-number">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="issue-reason">
                                                    {{ $reason }}
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="issues-footer">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Once these issues are resolved, you can check your eligibility again.</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection