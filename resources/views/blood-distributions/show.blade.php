@extends('dashboard.master')

@section('title', 'Blood Distribution Details')
@section('page-title', 'Blood Distributions Management')
@section('page-subtitle', 'View blood distribution details')

@section('content')
<div class="dashboard-content">
    <!-- Main Container -->
    <div class="detail-container">
        <!-- Header with Back Button -->
        <div class="detail-header">
            <div class="detail-title">
                <h3>Blood Distribution Details</h3>
                <p class="detail-subtitle">Request #{{ str_pad($bloodDistribution->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="detail-actions">
                <a href="{{ route('blood-distributions.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
                <a href="{{ route('blood-distributions.edit', $bloodDistribution->id) }}" class="btn btn-edit">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            </div>
        </div>

        <!-- Blood Distribution Details -->
        <div class="detail-body">
            <div class="detail-row">
                <!-- Left Column: Status & Patient -->
                <div class="detail-column">
                    <!-- Status Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Request Status</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $statusInfo = [
                                    'pending' => ['class' => 'status-pending', 'icon' => 'clock', 'label' => 'Pending'],
                                    'approved' => ['class' => 'status-completed', 'icon' => 'check-circle', 'label' => 'Approved'],
                                    'rejected' => ['class' => 'status-cancelled', 'icon' => 'times-circle', 'label' => 'Rejected'],
                                    'partially_approved' => ['class' => 'status-confirmed', 'icon' => 'check-double', 'label' => 'Partially Approved'],
                                    'fully_approved' => ['class' => 'status-completed', 'icon' => 'check-circle', 'label' => 'Fully Approved'],
                                    'unknown' => ['class' => 'status-inactive', 'icon' => 'question-circle', 'label' => 'Unknown']
                                ];
                                $status = $bloodDistribution->status;
                                $info = $statusInfo[$status] ?? $statusInfo['unknown'];
                            @endphp
                            <div class="alert-info">
                                <div class="status-icon">
                                    <i class="fas fa-{{ $info['icon'] }}"></i>
                                </div>
                                <div class="status-info">
                                    <h4>{{ $info['label'] }}</h4>
                                    <p>Current request status</p>
                                </div>
                            </div>
                            
                            <!-- Status Actions -->
                            @if($bloodDistribution->isPending())
                            <div class="status-actions">
                                <form action="{{ route('blood-distributions.reject', $bloodDistribution->id) }}" method="POST">
                                    @csrf
                                    <button type="button" 
                                            class="btn btn-danger mt-2"
                                            onclick="if(confirm('Are you sure you want to reject this blood distribution request?')) { this.closest('form').submit(); }">
                                        <i class="fas fa-times mr-2"></i>
                                        Reject Request
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Patient Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Patient Information</h5>
                        </div>
                        <div class="card-body">
                            @if($bloodDistribution->patient)
                            <div class="info-row">
                                <div class="info-label">
                                    Patient Name
                                </div>
                                <div class="info-value">
                                    {{ $bloodDistribution->patient->name }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Patient ID
                                </div>
                                <div class="info-value">
                                    #{{ str_pad($bloodDistribution->patient->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                            @else
                            <div class="no-data-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Patient information not available
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Blood & Amount -->
                <div class="detail-column">
                    <!-- Blood Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Blood Information</h5>
                        </div>
                        <div class="card-body">
                            @if($bloodDistribution->bloodGroup)
                            <div class="info-row">
                                <div class="info-label">
                                    Blood Group
                                </div>
                                <div class="info-value">
                                    <span class="blood-group">
                                        {{ $bloodDistribution->bloodGroup->name }} ({{ $bloodDistribution->bloodGroup->code }})
                                    </span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Description
                                </div>
                                <div class="info-value">
                                    {{ $bloodDistribution->bloodGroup->description ?? 'No description' }}
                                </div>
                            </div>
                            @else
                            <div class="no-data-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Blood group information not available
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Amount Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Amount Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">
                                    Requested Amount
                                </div>
                                <div class="info-value">
                                    {{ $bloodDistribution->request_unit }} ML
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Approved Amount
                                </div>
                                <div class="info-value">
                                    @if($bloodDistribution->approved_unit === null)
                                        <span class="text-muted">Pending</span>
                                    @elseif($bloodDistribution->approved_unit == 0)
                                        <span class="text-danger">Rejected (0 ML)</span>
                                    @else
                                        <span class="text-success">{{ $bloodDistribution->approved_unit }} ML</span>
                                    @endif
                                </div>
                            </div>
                            @if($bloodDistribution->approved_unit !== null && $bloodDistribution->approved_unit > 0)
                            <div class="info-row">
                                <div class="info-label">
                                    Approval Rate
                                </div>
                                <div class="info-value">
                                    <span class="text-primary">{{ number_format($bloodDistribution->approval_percentage, 1) }}%</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card system-card">
                <div class="card-header">
                    <h5>System Information</h5>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">
                                Request ID
                            </div>
                            <div class="info-value">
                                #{{ str_pad($bloodDistribution->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                Created At
                            </div>
                            <div class="info-value">
                                {{ $bloodDistribution->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                Last Updated
                            </div>
                            <div class="info-value">
                                {{ $bloodDistribution->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                Duration
                            </div>
                            <div class="info-value">
                                Active for {{ $bloodDistribution->created_at->diffForHumans(now(), true) }}
                            </div>
                        </div>
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
                        Once you delete this blood distribution, there is no going back. Please be certain.
                    </p>
                    <form action="{{ route('blood-distributions.destroy', $bloodDistribution->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                class="btn btn-danger"
                                onclick="if(confirm('Are you sure you want to delete blood distribution request #{{ str_pad($bloodDistribution->id, 6, '0', STR_PAD_LEFT) }}? This action cannot be undone.')) { this.closest('form').submit(); }">
                            <i class="fas fa-trash mr-2"></i>
                            Delete this Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection