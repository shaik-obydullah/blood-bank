@extends('dashboard.master')

@section('title', 'Add Patient')
@section('page-title', 'Add New Patient')
@section('page-subtitle', 'Register a new patient in the system')

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
                <h3>Add New Patient</h3>
                <p class="form-subtitle">Register a new patient in the system</p>
            </div>
            <a href="{{ route('patients.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>

        <form action="{{ route('patients.store') }}" method="POST">
            @csrf
            
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
                                   value="{{ old('id') }}"
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
                                   value="{{ old('name') }}"
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
                                   value="{{ old('email') }}"
                                   placeholder="Enter email address"
                                   required>
                        </div>
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control" 
                                   placeholder="Enter password (optional)">
                        </div>
                        <span class="form-text">Leave blank to generate random password</span>
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
                               value="{{ old('address') }}"
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
                                   value="{{ old('last_blood_taking_date') }}">
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
                                  placeholder="Enter medical history or notes">{{ old('medical_history') }}</textarea>
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
                    Create Patient
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
        
        // Generate random password suggestion
        const passwordField = document.getElementById('password');
        const generatePasswordBtn = document.createElement('button');
        generatePasswordBtn.type = 'button';
        generatePasswordBtn.innerHTML = '<i class="fas fa-key"></i>';
        generatePasswordBtn.style.cssText = `
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            z-index: 3;
        `;
        generatePasswordBtn.addEventListener('click', function() {
            const randomPassword = Math.random().toString(36).slice(-8);
            passwordField.value = randomPassword;
        });
        
        const passwordGroup = passwordField.closest('.input-group');
        passwordGroup.style.position = 'relative';
        passwordGroup.appendChild(generatePasswordBtn);
    });
</script>
@endsection