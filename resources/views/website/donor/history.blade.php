@extends('website.master')
@section('title', 'Donation History')
@section('home')
<div class="dashboard-content">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="dashboard-title">
            <h1>Donation History</h1>
            <p>Track your blood donation records and impact</p>
        </div>
    </div>

    @php
        $totalDonations = $donations->count();
        $livesImpacted = $totalDonations * 3;
        $now = date('Y-m-d'); // Current date for comparison
    @endphp

    @if($donations->count() > 0)
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <i class="fas fa-tint"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalDonations }}</h3>
                <p>Total Donations</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <i class="fas fa-heart"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $livesImpacted }}</h3>
                <p>Lives Impacted</p>
                <span class="stat-trend up">
                    <i class="fas fa-users"></i>
                    {{ $totalDonations > 0 ? '3 lives per donation' : 'No donations yet' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Donation History Cards -->
    <div class="donation-history-container">
        <div class="history-header">
            <h2>My Donations ({{ $totalDonations }})</h2>
        </div>
        
        <div class="donations-grid">
            @foreach($donations as $donation)
            @php
                // Format dates manually
                $collectionDate = $donation->collection_date ? date('M d, Y', strtotime($donation->collection_date)) : 'Date not recorded';
                $expiryDate = $donation->expiry_date ? date('M d, Y', strtotime($donation->expiry_date)) : null;
                
                // Check if expired
                $isExpired = $donation->expiry_date ? (strtotime($donation->expiry_date) < strtotime($now)) : false;
                $isFuture = $donation->expiry_date ? (strtotime($donation->expiry_date) > strtotime($now)) : false;
            @endphp
            
            <div class="donation-card">
                <div class="donation-header">
                    <div>
                        <h3 class="donation-title">
                            <i class="fas fa-heartbeat"></i>
                            Donation #{{ $donation->id }}
                        </h3>
                        <div class="donation-date">
                            <i class="far fa-calendar"></i>
                            {{ $collectionDate }}
                        </div>
                    </div>
                    <div class="donation-status">
                        @if($donation->expiry_date && $isFuture)
                            <span class="status-badge status-active">Active</span>
                        @elseif($donation->expiry_date)
                            <span class="status-badge status-used">Used</span>
                        @else
                            <span class="status-badge status-processed">Processed</span>
                        @endif
                    </div>
                </div>
                
                <div class="donation-details">
                    <!-- Blood Type -->
                    <div class="blood-type-info">
                        <div class="blood-icon-container">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div class="blood-info">
                            <div class="blood-label">Blood Type</div>
                            <div class="blood-value">
                                @if($donation->bloodGroup)
                                    <span class="blood-badge">{{ $donation->bloodGroup->code }}</span>
                                @else
                                    <span class="not-recorded">Not recorded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quantity & Expiry -->
                    <div class="donation-metrics">
                        <div class="metric">
                            <div class="metric-label">Quantity</div>
                            <div class="metric-value">
                                {{ $donation->quantity }} unit{{ $donation->quantity > 1 ? 's' : '' }}
                            </div>
                        </div>
                        <div class="metric">
                            <div class="metric-label">Expiry Date</div>
                            <div class="metric-value">
                                @if($expiryDate)
                                    {{ $expiryDate }}
                                    @if($isExpired)
                                        <div class="expiry-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Expired
                                        </div>
                                    @endif
                                @else
                                    <span class="not-available">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Impact Message -->
                <div class="impact-message">
                    <i class="fas fa-users"></i>
                    <span>Your donation helped save up to 3 lives</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-donation-state">
        <div class="empty-icon-container">
            <i class="fas fa-history"></i>
        </div>
        <h4>No Donation History</h4>
        <p>You haven't made any donations yet. Your first donation can help save lives!</p>
        <a href="{{ url('/donor-appointment') }}" class="btn-schedule">
            <i class="fas fa-plus"></i> Schedule Your First Donation
        </a>
    </div>
    @endif
</div>
@endsection