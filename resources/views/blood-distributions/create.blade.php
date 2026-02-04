@extends('dashboard.master')

@section('title', 'Create Blood Distribution')
@section('page-title', 'Blood Distributions Management')
@section('page-subtitle', 'Create new blood distribution request')

@section('content')
<div class="dashboard-content">
    <!-- Main Container -->
    <div class="form-container">
        <!-- Header with Back Button -->
        <div class="form-header">
            <div class="form-title">
                <h3>Create Blood Distribution Request</h3>
                <p class="form-subtitle">Request blood distribution for patient</p>
            </div>
            <a href="{{ route('blood-distributions.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('blood-distributions.store') }}" method="POST">
            @csrf

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
                                <option value="{{ $patient->id }}" {{ old('fk_patient_id') == $patient->id ? 'selected' : '' }}>
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
                                <option value="{{ $bg->id }}" {{ old('fk_blood_group_id') == $bg->id ? 'selected' : '' }}>
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
                            value="{{ old('request_unit') }}"
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
            </div>

            <!-- Form Footer -->
            <div class="form-footer">
                <a href="{{ route('blood-distributions.index') }}" class="btn btn-cancel">
                    Cancel
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save mr-2"></i>
                    Create Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection