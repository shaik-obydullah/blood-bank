@extends('dashboard.master')

@section('title', 'Appointment Details')
@section('page-title', 'Appointments Management')
@section('page-subtitle', 'View appointment details')

@section('content')
<div class="dashboard-content">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Main Container -->
    <div class="detail-container">
        <!-- Header with Back Button -->
        <div class="detail-header">
            <div class="detail-title">
                <h3>Appointment Details</h3>
                <p class="detail-subtitle">Appointment #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="detail-actions">
                <a href="{{ route('appointments.index') }}" class="btn btn-edit">
                    <span>Back to List</span>
                </a>
                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-edit">
                    <span>Edit Appointment</span>
                </a>
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="detail-body">
            <div class="detail-row">
                <!-- Left Column: Status & Time -->
                <div class="detail-column">
                    <!-- Status Card -->
                    <div class="detail-card status-card">
                        <div class="card-header">
                            <h5>Appointment Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="status-display status-{{ strtolower($appointment->status) }}">
                                <div class="status-icon">
                                    <!-- Icon removed -->
                                </div>
                                <div class="status-content">
                                    <h4>{{ $appointment->status }}</h4>
                                    <p>Current appointment status</p>
                                </div>
                            </div>
                            
                            <!-- Status Actions -->
                            @if($appointment->status !== 'Completed' && $appointment->status !== 'Cancelled')
                            <div class="status-actions">
                                <form action="{{ route('appointments.status', $appointment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($appointment->status === 'Pending')
                                        <button type="submit" name="status" value="Confirmed" class="status-btn confirm">
                                            Confirm Appointment
                                        </button>
                                    @endif
                                    @if($appointment->status === 'Confirmed')
                                        <button type="submit" name="status" value="Completed" class="status-btn complete">
                                            Mark as Completed
                                        </button>
                                    @endif
                                    @if(in_array($appointment->status, ['Pending', 'Confirmed']))
                                        <button type="submit" name="status" value="Cancelled" class="status-btn cancel">
                                            Cancel Appointment
                                        </button>
                                    @endif
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Time Information -->
                    <div class="detail-card">
                        <div class="card-header">
                            <h5>Time Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">
                                    Appointment Date
                                </div>
                                <div class="info-value">
                                    {{ $appointment->appointment_time->format('l, F d, Y') }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Appointment Time
                                </div>
                                <div class="info-value">
                                    {{ $appointment->appointment_time->format('h:i A') }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Time Until Appointment
                                </div>
                                <div class="info-value">
                                    @if($appointment->appointment_time > now() && !in_array($appointment->status, ['Cancelled', 'Completed']))
                                        <span class="time-until upcoming">
                                            {{ $appointment->appointment_time->diffForHumans() }}
                                        </span>
                                    @elseif($appointment->appointment_time < now() && !in_array($appointment->status, ['Cancelled', 'Completed']))
                                        <span class="time-until overdue">
                                            Overdue by {{ now()->diffForHumans($appointment->appointment_time, true) }}
                                        </span>
                                    @else
                                        <span style="color: #999;">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Doctor & Donor Info -->
                <div class="detail-column">
                    <!-- Doctor Information -->
                    <div class="detail-card">
                        <div class="card-header">
                            <h5>Doctor Information</h5>
                        </div>
                        <div class="card-body">
                            @if($appointment->doctor)
                            <div class="info-row">
                                <div class="info-label">
                                    Doctor Name
                                </div>
                                <div class="info-value">
                                    {{ $appointment->doctor->name }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Contact Number
                                </div>
                                <div class="info-value">
                                    {{ $appointment->doctor->mobile ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Address
                                </div>
                                <div class="info-value">
                                    {{ $appointment->doctor->address ?? 'N/A' }}
                                </div>
                            </div>
                            @else
                            <div class="no-data-warning">
                                <span>No doctor assigned to this appointment</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Donor Information -->
                    <div class="detail-card">
                        <div class="card-header">
                            <h5>Donor Information</h5>
                        </div>
                        <div class="card-body">
                            @if($appointment->donor)
                            <div class="info-row">
                                <div class="info-label">
                                    Donor Name
                                </div>
                                <div class="info-value">
                                    {{ $appointment->donor->name }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Contact Number
                                </div>
                                <div class="info-value">
                                    {{ $appointment->donor->mobile }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Email
                                </div>
                                <div class="info-value">
                                    {{ $appointment->donor->email }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    Blood Group
                                </div>
                                <div class="info-value">
                                    @if($appointment->donor->bloodGroup)
                                        <span class="blood-group">
                                            {{ $appointment->donor->bloodGroup->name }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="no-data-warning">
                                <span>No donor assigned to this appointment</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="detail-card system-card">
                <div class="card-header">
                    <h5>System Information</h5>
                </div>
                <div class="card-body">
                    <div class="system-info">
                        <div class="info-row">
                            <div class="info-label">
                                Appointment ID
                            </div>
                            <div class="info-value">
                                #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                Created At
                            </div>
                            <div class="info-value">
                                {{ $appointment->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                    <div class="system-info">
                        <div class="info-row">
                            <div class="info-label">
                                Last Updated
                            </div>
                            <div class="info-value">
                                {{ $appointment->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                Duration
                            </div>
                            <div class="info-value">
                                @if($appointment->status === 'Completed')
                                    <span class="time-until upcoming">
                                        Completed in {{ $appointment->created_at->diffForHumans($appointment->updated_at, true) }}
                                    </span>
                                @else
                                    <span class="time-until upcoming">
                                        Active for {{ $appointment->created_at->diffForHumans(now(), true) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($appointment->notes)
            <div class="detail-card notes-card">
                <div class="card-header">
                    <h5>Notes</h5>
                </div>
                <div class="card-body">
                    <div class="notes-content">
                        {{ $appointment->notes }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Danger Zone -->
            <div class="detail-card danger-card">
                <div class="card-header">
                    <h5>Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="danger-warning">
                        Once you delete an appointment, there is no going back. Please be certain.
                    </p>
                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-primary mt-2"
                                onclick="if(confirm('Are you sure you want to delete appointment #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}?\n\nThis action cannot be undone!')) { this.closest('form').submit(); }">
                            Delete Appointment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Status change confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const statusButtons = document.querySelectorAll('.status-btn');
        statusButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const status = this.value || this.classList.contains('confirm') ? 'Confirmed' :
                              this.classList.contains('complete') ? 'Completed' : 'Cancelled';
                let message = '';
                
                switch(status) {
                    case 'Confirmed':
                        message = 'Are you sure you want to confirm this appointment?';
                        break;
                    case 'Completed':
                        message = 'Are you sure you want to mark this appointment as completed?';
                        break;
                    case 'Cancelled':
                        message = 'Are you sure you want to cancel this appointment?';
                        break;
                }
                
                if (!confirm(message + '\n\nThis action will update the appointment status.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection