@extends('dashboard.master')

@section('title', 'Appointment Details')
@section('page-title', 'Appointments Management')
@section('page-subtitle', 'View appointment details')

@section('content')
<div class="dashboard-content">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
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
                <a href="{{ route('appointments.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-edit">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="detail-body">
            <div class="detail-row">
                <!-- Left Column: Basic Info -->
                <div class="detail-column">
                    <!-- Status Card -->
                    <div class="detail-card status-card">
                        <div class="card-header">
                            <h5>Appointment Status</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $statusInfo = [
                                    'Pending' => ['color' => '#ff9800', 'bg_color' => '#fff3e0', 'icon' => 'clock', 'label' => 'Pending'],
                                    'Confirmed' => ['color' => '#2196f3', 'bg_color' => '#e3f2fd', 'icon' => 'check-circle', 'label' => 'Confirmed'],
                                    'Cancelled' => ['color' => '#f44336', 'bg_color' => '#ffebee', 'icon' => 'times-circle', 'label' => 'Cancelled'],
                                    'Completed' => ['color' => '#4caf50', 'bg_color' => '#f1f8e9', 'icon' => 'check-double', 'label' => 'Completed']
                                ];
                                $info = $statusInfo[$appointment->status] ?? $statusInfo['Pending'];
                            @endphp
                            <div class="status-display" style="border-left: 4px solid {{ $info['color'] }}; background-color: {{ $info['bg_color'] }}; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                                <div style="display: flex; align-items: center;">
                                    <div style="margin-right: 15px; font-size: 24px; color: {{ $info['color'] }};">
                                        <i class="fas fa-{{ $info['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h4 style="color: {{ $info['color'] }}; margin: 0; font-size: 18px;">{{ $appointment->status }}</h4>
                                        <p style="color: #666; margin: 5px 0 0; font-size: 14px;">Current appointment status</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Actions -->
                            @if($appointment->status !== 'Completed' && $appointment->status !== 'Cancelled')
                            <div class="status-actions" style="margin-top: 20px;">
                                <form action="{{ route('appointments.status', $appointment->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @if($appointment->status === 'Pending')
                                        <button type="submit" name="status" value="Confirmed" 
                                                style="background: #2196f3; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; margin-right: 10px;">
                                            <i class="fas fa-check mr-2"></i>
                                            Confirm Appointment
                                        </button>
                                    @endif
                                    @if($appointment->status === 'Confirmed')
                                        <button type="submit" name="status" value="Completed" 
                                                style="background: #4caf50; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; margin-right: 10px;">
                                            <i class="fas fa-check-double mr-2"></i>
                                            Mark as Completed
                                        </button>
                                    @endif
                                    @if(in_array($appointment->status, ['Pending', 'Confirmed']))
                                        <button type="submit" name="status" value="Cancelled" 
                                                style="background: #f44336; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center;">
                                            <i class="fas fa-times mr-2"></i>
                                            Cancel Appointment
                                        </button>
                                    @endif
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Time Information -->
                    <div class="detail-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px; overflow: hidden;">
                        <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <h5 style="margin: 0; color: #333;">Time Information</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-calendar-alt" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Appointment Date
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $appointment->appointment_time->format('l, F d, Y') }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-clock" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Appointment Time
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $appointment->appointment_time->format('h:i A') }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-hourglass-half" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Time Until Appointment
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    @if($appointment->appointment_time > now() && !in_array($appointment->status, ['Cancelled', 'Completed']))
                                        <span style="color: #4caf50;">
                                            {{ $appointment->appointment_time->diffForHumans() }}
                                        </span>
                                    @elseif($appointment->appointment_time < now() && !in_array($appointment->status, ['Cancelled', 'Completed']))
                                        <span style="color: #ff9800;">
                                            Overdue by {{ now()->diffForHumans($appointment->appointment_time, true) }}
                                        </span>
                                    @else
                                        <span style="color: #999;">
                                            N/A
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Doctor & Donor Info -->
                <div class="detail-column">
                    <!-- Doctor Information -->
                    <div class="detail-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px; overflow: hidden;">
                        <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <h5 style="margin: 0; color: #333;">Doctor Information</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">
                            @if($appointment->doctor)
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-user-md" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Doctor Name
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $appointment->doctor->name }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-phone" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Contact Number
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $appointment->doctor->mobile ?? 'N/A' }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-map-marker-alt" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Address
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $appointment->doctor->address ?? 'N/A' }}
                                </div>
                            </div>
                            @else
                            <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 12px; border-radius: 4px; font-size: 14px;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No doctor assigned to this appointment
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Donor Information -->
                    <div class="detail-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px; overflow: hidden;">
                        <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <h5 style="margin: 0; color: #333;">Donor Information</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">
                            @if($appointment->donor)
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-user-injured" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Donor Name
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $appointment->donor->name }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-phone" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Contact Number
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $appointment->donor->mobile }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-envelope" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Email
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $appointment->donor->email }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-tint" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Blood Group
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    @if($appointment->donor->bloodGroup)
                                        <span style="background: #c62828; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                                            {{ $appointment->donor->bloodGroup->name }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            @else
                            <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 12px; border-radius: 4px; font-size: 14px;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No donor assigned to this appointment
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="detail-card system-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-top: 20px; overflow: hidden;">
                <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                    <h5 style="margin: 0; color: #333;">System Information</h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                <i class="fas fa-hashtag" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                Appointment ID
                            </div>
                            <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                <i class="fas fa-calendar-plus" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                Created At
                            </div>
                            <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                {{ $appointment->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                <i class="fas fa-calendar-check" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                Last Updated
                            </div>
                            <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                {{ $appointment->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                <i class="fas fa-clock" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                Duration
                            </div>
                            <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                @if($appointment->status === 'Completed')
                                    Completed in {{ $appointment->created_at->diffForHumans($appointment->updated_at, true) }}
                                @else
                                    Active for {{ $appointment->created_at->diffForHumans(now(), true) }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($appointment->notes)
            <div class="detail-card notes-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-top: 20px; overflow: hidden;">
                <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                    <h5 style="margin: 0; color: #333;">Notes</h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #3498db; white-space: pre-line;">
                        {{ $appointment->notes }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Danger Zone -->
            <div class="detail-card danger-card" style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; margin-top: 20px; overflow: hidden;">
                <div class="card-header" style="background: #f8d7da; padding: 15px 20px; border-bottom: 1px solid #f5c6cb;">
                    <h5 style="margin: 0; color: #721c24;">Danger Zone</h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <p style="color: #856404; margin-bottom: 15px;">
                        Once you delete an appointment, there is no going back. Please be certain.
                    </p>
                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center;"
                                onclick="if(confirm('Are you sure you want to delete appointment #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}? This action cannot be undone.')) { this.closest('form').submit(); }">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Appointment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-content {
        padding: 20px;
    }

    .detail-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .detail-title h3 {
        color: #333;
        margin-bottom: 5px;
        font-size: 24px;
    }

    .detail-subtitle {
        color: #666;
        margin: 0;
        font-size: 14px;
    }

    .detail-actions {
        display: flex;
        gap: 10px;
    }

    .btn-back {
        background: #f8f9fa;
        color: #333;
        border: 1px solid #dee2e6;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 14px;
    }

    .btn-back:hover {
        background: #e9ecef;
        text-decoration: none;
    }

    .btn-edit {
        background: #007bff;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 14px;
    }

    .btn-edit:hover {
        background: #0056b3;
        color: white;
        text-decoration: none;
    }

    .detail-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-column {
        flex: 1;
    }

    @media (max-width: 992px) {
        .detail-row {
            flex-direction: column;
        }
        
        .detail-container {
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        .detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .detail-actions {
            width: 100%;
        }
        
        .btn-back, .btn-edit {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<script>
    // Status change confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const statusButtons = document.querySelectorAll('button[name="status"]');
        statusButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const status = this.value;
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
                
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection