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
                Back to List
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
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">Current Status</h5>
                        @php
                            $statusInfo = [
                                'pending' => ['class' => 'status-pending', 'icon' => 'clock', 'label' => 'Pending'],
                                'approved' => ['class' => 'status-completed', 'icon' => 'check-circle', 'label' => 'Approved'],
                                'rejected' => ['class' => 'status-cancelled', 'icon' => 'times-circle', 'label' => 'Rejected'],
                                'partially_approved' => ['class' => 'status-confirmed', 'icon' => 'check-double', 'label' => 'Partially Approved'],
                                'fully_approved' => ['class' => 'status-completed', 'icon' => 'check-circle', 'label' => 'Fully Approved'],
                            ];
                            $status = $bloodDistribution->status;
                            $info = $statusInfo[$status] ?? $statusInfo['pending'];
                        @endphp
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <span class="status-badge {{ $info['class'] }}">
                                    <i class="fas fa-{{ $info['icon'] }}"></i>
                                    {{ $info['label'] }}
                                </span>
                            </div>
                            <div>
                                <p class="mb-0">
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
                </div>

                <!-- Quick Actions -->
                @if($bloodDistribution->isPending())
                <div class="alert alert-info mb-3">
                    <h5 class="alert-heading">Quick Actions</h5>
                    <p class="mb-2">You can quickly approve or reject this request:</p>
                    <div class="d-flex gap-2">
                        <button type="button" 
                                class="btn btn-success"
                                onclick="document.getElementById('approved_unit').value = {{ $bloodDistribution->request_unit }};">
                            <i class="fas fa-check mr-2"></i>
                            Approve Full Amount
                        </button>
                        <button type="button" 
                                class="btn btn-danger"
                                onclick="document.getElementById('approved_unit').value = 0;">
                            <i class="fas fa-times mr-2"></i>
                            Reject Request
                        </button>
                        <button type="button" 
                                class="btn btn-warning"
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
                    View Details
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save mr-2"></i>
                    Update Distribution
                </button>
            </div>
        </form>
    </div>
</div>

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