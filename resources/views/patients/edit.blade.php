@extends('dashboard.master')

@section('title', 'Edit Patient')
@section('page-title', 'Edit Patient')
@section('page-subtitle', 'Update patient information')

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

    <!-- Form Container -->
    <div class="form-container">
        <div class="form-header">
            <div class="form-title">
                <h3>Edit Patient</h3>
                <p class="form-subtitle">Update patient information</p>
            </div>
            <a href="{{ route('patients.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>

        <form action="{{ route('patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="id" class="form-label">
                            Patient ID <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-id-card"></i>
                            <input type="number" 
                                   name="id" 
                                   id="id" 
                                   class="form-control" 
                                   value="{{ old('id', $patient->id) }}"
                                   placeholder="Enter patient ID"
                                   required>
                        </div>
                        @error('id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-user"></i>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control" 
                                   value="{{ old('name', $patient->name) }}"
                                   placeholder="Enter patient's full name"
                                   required>
                        </div>
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-envelope"></i>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control" 
                                   value="{{ old('email', $patient->email) }}"
                                   placeholder="Enter email address"
                                   required>
                        </div>
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control" 
                                   placeholder="Enter new password (leave blank to keep current)">
                        </div>
                        <span class="form-text">Only enter if you want to change the password</span>
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <div class="input-group">
                        <i class="input-icon fas fa-home"></i>
                        <input type="text" 
                               name="address" 
                               id="address" 
                               class="form-control" 
                               value="{{ old('address', $patient->address) }}"
                               placeholder="Enter patient's address">
                    </div>
                    @error('address')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="last_blood_taking_date" class="form-label">Last Blood Taking Date</label>
                        <div class="input-group">
                            <i class="input-icon fas fa-calendar-alt"></i>
                            <input type="date" 
                                   name="last_blood_taking_date" 
                                   id="last_blood_taking_date" 
                                   class="form-control" 
                                   value="{{ old('last_blood_taking_date', $patient->last_blood_taking_date ? date('Y-m-d', strtotime($patient->last_blood_taking_date)) : '') }}">
                        </div>
                        @error('last_blood_taking_date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="medical_history" class="form-label">Medical History</label>
                    <div class="input-group">
                        <i class="input-icon fas fa-file-medical-alt"></i>
                        <textarea name="medical_history" 
                                  id="medical_history" 
                                  class="form-control" 
                                  rows="4"
                                  placeholder="Enter medical history or notes">{{ old('medical_history', $patient->medical_history) }}</textarea>
                    </div>
                    @error('medical_history')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-footer">
                <a href="{{ route('patients.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="reset" class="btn btn-reset">
                    <i class="fas fa-redo mr-2"></i>
                    Reset
                </button>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save mr-2"></i>
                    Update Patient
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set max date for last blood taking date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('last_blood_taking_date').max = today;
    });
</script>
@endsection