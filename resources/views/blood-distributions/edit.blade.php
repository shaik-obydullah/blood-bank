@extends('dashboard.master')

@section('title', 'Edit Blood Distribution')
@section('page-title', 'Blood Distributions Management')
@section('page-subtitle', 'Edit blood distribution details')

@section('content')
<div class="dashboard-content">
    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="mb-0 pl-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Main Container -->
    <div class="form-container">
        <!-- Header with Back Button -->
        <div class="form-header">
            <div class="form-title">
                <h3>Edit Blood Distribution</h3>
                <p class="form-subtitle">Update request #{{ str_pad($bloodDistribution->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <a href="{{ route('blood-distributions.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="ml-2">Back to List</span>
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('blood-distributions.update', $bloodDistribution->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-body">
                <!-- Patient Selection -->
                <div class="form-group">
                    <label for="fk_patient_id" class="form-label">
                        Patient <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-user-injured"></i>
                        </div>
                        <select id="fk_patient_id" name="fk_patient_id" 
                                class="form-control @error('fk_patient_id') is-invalid @enderror"
                                required>
                            <option value="">Select a patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" 
                                        {{ old('fk_patient_id', $bloodDistribution->fk_patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('fk_patient_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Select the patient who needs blood
                    </small>
                </div>

                <!-- Blood Group Selection -->
                <div class="form-group">
                    <label for="fk_blood_group_id" class="form-label">
                        Blood Group <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-tint"></i>
                        </div>
                        <select id="fk_blood_group_id" name="fk_blood_group_id" 
                                class="form-control @error('fk_blood_group_id') is-invalid @enderror"
                                required>
                            <option value="">Select a blood group</option>
                            @foreach($bloodGroups as $bg)
                                <option value="{{ $bg->id }}" 
                                        {{ old('fk_blood_group_id', $bloodDistribution->fk_blood_group_id) == $bg->id ? 'selected' : '' }}>
                                    {{ $bg->name }} ({{ $bg->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('fk_blood_group_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Select the required blood group
                    </small>
                </div>

                <div class="form-row">
                    <!-- Request Units -->
                    <div class="form-group">
                        <label for="request_unit" class="form-label">
                            Requested Amount (ML) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-prescription-bottle"></i>
                            </div>
                            <input type="number" id="request_unit" name="request_unit"
                                class="form-control @error('request_unit') is-invalid @enderror"
                                placeholder="Enter amount in milliliters"
                                value="{{ old('request_unit', $bloodDistribution->request_unit) }}"
                                min="1"
                                required>
                            <div class="input-append">
                                <span class="input-text">ML</span>
                            </div>
                        </div>
                        @error('request_unit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Enter the amount of blood needed in milliliters
                        </small>
                    </div>

                    <!-- Approved Units -->
                    <div class="form-group">
                        <label for="approved_unit" class="form-label">
                            Approved Amount (ML)
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <input type="number" id="approved_unit" name="approved_unit"
                                class="form-control @error('approved_unit') is-invalid @enderror"
                                placeholder="Enter approved amount"
                                value="{{ old('approved_unit', $bloodDistribution->approved_unit) }}"
                                min="0"
                                max="{{ $bloodDistribution->request_unit }}">
                            <div class="input-append">
                                <span class="input-text">ML</span>
                            </div>
                        </div>
                        @error('approved_unit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Enter approved amount (0 for reject, leave empty for pending)
                        </small>
                    </div>
                </div>

                <!-- Current Status Display -->
                <div class="status-display-card" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                    <h5 style="margin-top: 0; color: #333;">Current Status</h5>
                    @php
                        $statusInfo = [
                            'pending' => ['color' => '#f39c12', 'icon' => 'clock', 'label' => 'Pending'],
                            'approved' => ['color' => '#27ae60', 'icon' => 'check-circle', 'label' => 'Approved'],
                            'rejected' => ['color' => '#e74c3c', 'icon' => 'times-circle', 'label' => 'Rejected'],
                            'partially_approved' => ['color' => '#3498db', 'icon' => 'check-double', 'label' => 'Partially Approved'],
                            'fully_approved' => ['color' => '#27ae60', 'icon' => 'check-circle', 'label' => 'Fully Approved'],
                        ];
                        $status = $bloodDistribution->status;
                        $info = $statusInfo[$status] ?? $statusInfo['pending'];
                    @endphp
                    <div style="display: flex; align-items: center;">
                        <div style="color: {{ $info['color'] }}; margin-right: 10px; font-size: 20px;">
                            <i class="fas fa-{{ $info['icon'] }}"></i>
                        </div>
                        <div>
                            <h4 style="color: {{ $info['color'] }}; margin: 0; font-size: 16px;">{{ $info['label'] }}</h4>
                            <p style="color: #666; margin: 5px 0 0; font-size: 14px;">
                                @if($bloodDistribution->approved_unit === null)
                                    Request is pending approval
                                @elseif($bloodDistribution->approved_unit == 0)
                                    Request has been rejected
                                @else
                                    {{ $bloodDistribution->approved_unit }} ML approved out of {{ $bloodDistribution->request_unit }} ML requested
                                    ({{ number_format($bloodDistribution->approval_percentage, 1) }}%)
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if($bloodDistribution->isPending())
                <div class="quick-actions" style="background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                    <h5 style="margin-top: 0; color: #1565c0;">Quick Actions</h5>
                    <p style="color: #0d47a1; margin-bottom: 10px;">You can quickly approve or reject this request:</p>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" 
                                style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center;"
                                onclick="document.getElementById('approved_unit').value = {{ $bloodDistribution->request_unit }};">
                            <i class="fas fa-check mr-2"></i>
                            Approve Full Amount
                        </button>
                        <button type="button" 
                                style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center;"
                                onclick="document.getElementById('approved_unit').value = 0;">
                            <i class="fas fa-times mr-2"></i>
                            Reject Request
                        </button>
                        <button type="button" 
                                style="background: #f39c12; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center;"
                                onclick="document.getElementById('approved_unit').value = '';">
                            <i class="fas fa-clock mr-2"></i>
                            Set as Pending
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Form Footer -->
            <div class="form-footer">
                <a href="{{ route('blood-distributions.index') }}" class="btn btn-cancel">
                    Cancel
                </a>
                <a href="{{ route('blood-distributions.show', $bloodDistribution->id) }}" class="btn btn-reset">
                    <i class="fas fa-eye mr-2"></i>
                    <span class="ml-2">View Details</span>
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save mr-2"></i>
                    <span class="ml-2">Update Distribution</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-row .form-group {
        flex: 1;
    }
    
    .input-append {
        background: #f8f9fa;
        border: 1px solid #ced4da;
        border-left: none;
        padding: 0 12px;
        display: flex;
        align-items: center;
        border-radius: 0 4px 4px 0;
        color: #666;
    }
    
    .input-group {
        display: flex;
    }
    
    .input-group .form-control {
        border-right: none;
        border-radius: 4px 0 0 4px;
    }
    
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
        
        .quick-actions div {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requestUnit = document.getElementById('request_unit');
        const approvedUnit = document.getElementById('approved_unit');
        
        // Update max value of approved unit when request unit changes
        requestUnit.addEventListener('input', function() {
            const maxValue = parseInt(this.value) || 0;
            approvedUnit.max = maxValue;
            
            if (parseInt(approvedUnit.value) > maxValue) {
                approvedUnit.value = maxValue;
            }
        });
        
        // Validate approved unit doesn't exceed request unit
        approvedUnit.addEventListener('input', function() {
            const maxValue = parseInt(requestUnit.value) || 0;
            const currentValue = parseInt(this.value) || 0;
            
            if (currentValue > maxValue) {
                this.value = maxValue;
                alert('Approved amount cannot exceed requested amount');
            }
        });
    });
</script>
@endsection