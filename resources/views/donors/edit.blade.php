@extends('dashboard.master')

@section('title', 'Edit Donor')
@section('page-title', 'Donors Management')
@section('page-subtitle', 'Update donor information')

@section('content')
    <div class="dashboard-content">
        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Please fix the following errors:</strong>
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
                    <h3><i class="fas fa-user-edit mr-2 text-danger"></i> Edit Donor</h3>
                    <p class="form-subtitle">Update donor information for {{ $donor->name }}</p>
                </div>
                <a href="{{ route('donors.show', $donor->id) }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Donor Details
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('donors.update', $donor->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-body">
                    <!-- Personal Information -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-user-circle mr-2"></i>
                            Personal Information
                        </h5>
                        
                        <div class="form-row">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="Enter donor's full name" 
                                           value="{{ old('name', $donor->name) }}"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="donor@example.com" 
                                           value="{{ old('email', $donor->email) }}"
                                           required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <!-- Mobile -->
                            <div class="form-group">
                                <label for="mobile" class="form-label">
                                    Mobile Number <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <input type="tel" 
                                           id="mobile" 
                                           name="mobile" 
                                           class="form-control @error('mobile') is-invalid @enderror" 
                                           placeholder="+8801712345678" 
                                           value="{{ old('mobile', $donor->mobile) }}"
                                           required>
                                </div>
                                @error('mobile')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Birthdate -->
                            <div class="form-group">
                                <label for="birthdate" class="form-label">
                                    Date of Birth <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <input type="date" 
                                           id="birthdate" 
                                           name="birthdate" 
                                           class="form-control @error('birthdate') is-invalid @enderror" 
                                           value="{{ old('birthdate', $donor->birthdate) }}"
                                           required
                                           max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}">
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Donor must be at least 18 years old
                                </small>
                                @error('birthdate')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Blood Information -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-heartbeat mr-2 text-danger"></i>
                            Blood Information
                        </h5>
                        
                        <div class="form-row">
                            <!-- Blood Group -->
                            <div class="form-group">
                                <label for="fk_blood_group_id" class="form-label">
                                    Blood Group
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-tint"></i>
                                    </div>
                                    <select id="fk_blood_group_id" 
                                            name="fk_blood_group_id" 
                                            class="form-control @error('fk_blood_group_id') is-invalid @enderror">
                                        <option value="">Select Blood Group</option>
                                        @foreach($bloodGroups as $bloodGroup)
                                            <option value="{{ $bloodGroup->id }}" 
                                                {{ old('fk_blood_group_id', $donor->fk_blood_group_id) == $bloodGroup->id ? 'selected' : '' }}>
                                                {{ $bloodGroup->name }} ({{ $bloodGroup->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('fk_blood_group_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Hemoglobin -->
                            <div class="form-group">
                                <label for="hemoglobin_level" class="form-label">
                                    Hemoglobin Level (g/dL)
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <input type="number" 
                                           id="hemoglobin_level" 
                                           name="hemoglobin_level" 
                                           class="form-control @error('hemoglobin_level') is-invalid @enderror" 
                                           value="{{ old('hemoglobin_level', $donor->hemoglobin_level) }}" 
                                           step="0.01"
                                           min="0"
                                           max="20"
                                           placeholder="e.g., 14.5">
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Normal range: 12.0-16.0 g/dL (women), 13.5-17.5 g/dL (men)
                                </small>
                                @error('hemoglobin_level')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <!-- Blood Pressure -->
                            <div class="form-group">
                                <label class="form-label">Blood Pressure (mmHg)</label>
                                <div class="bp-input-group">
                                    <div class="input-group">
                                        <div class="input-icon">
                                            <i class="fas fa-heartbeat"></i>
                                        </div>
                                        <input type="number" 
                                               id="systolic" 
                                               name="systolic" 
                                               class="form-control @error('systolic') is-invalid @enderror" 
                                               value="{{ old('systolic', $donor->systolic) }}" 
                                               min="70"
                                               max="200"
                                               placeholder="Systolic">
                                    </div>
                                    <span class="bp-separator">/</span>
                                    <div class="input-group">
                                        <input type="number" 
                                               id="diastolic" 
                                               name="diastolic" 
                                               class="form-control @error('diastolic') is-invalid @enderror" 
                                               value="{{ old('diastolic', $donor->diastolic) }}" 
                                               min="40"
                                               max="130"
                                               placeholder="Diastolic">
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: Systolic/Diastolic (e.g., 120/80)
                                </small>
                                @error('systolic')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('diastolic')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Last Donation Date -->
                            <div class="form-group">
                                <label for="last_donation_date" class="form-label">
                                    Last Donation Date
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <input type="date" 
                                           id="last_donation_date" 
                                           name="last_donation_date" 
                                           class="form-control @error('last_donation_date') is-invalid @enderror" 
                                           value="{{ old('last_donation_date', $donor->last_donation_date) }}"
                                           max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Leave empty if first-time donor
                                </small>
                                @error('last_donation_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address Information -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Address Information
                        </h5>
                        
                        <!-- Country -->
                        <div class="form-group">
                            <label for="country" class="form-label">
                                Country <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <select id="country" 
                                        name="country" 
                                        class="form-control @error('country') is-invalid @enderror" 
                                        required>
                                    <option value="">Select Country</option>
                                    <option value="Bangladesh" {{ old('country', $donor->country) == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                    <option value="USA" {{ old('country', $donor->country) == 'USA' ? 'selected' : '' }}>United States</option>
                                    <option value="UK" {{ old('country', $donor->country) == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="India" {{ old('country', $donor->country) == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="Canada" {{ old('country', $donor->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Australia" {{ old('country', $donor->country) == 'Australia' ? 'selected' : '' }}>Australia</option>
                                </select>
                            </div>
                            @error('country')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <!-- Address Line 1 -->
                            <div class="form-group">
                                <label for="address_line_1" class="form-label">
                                    Address Line 1
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <input type="text" 
                                           id="address_line_1" 
                                           name="address_line_1" 
                                           class="form-control @error('address_line_1') is-invalid @enderror" 
                                           value="{{ old('address_line_1', $donor->address_line_1) }}" 
                                           placeholder="House #, Street, Area">
                                </div>
                                @error('address_line_1')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Address Line 2 -->
                            <div class="form-group">
                                <label for="address_line_2" class="form-label">
                                    Address Line 2
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <input type="text" 
                                           id="address_line_2" 
                                           name="address_line_2" 
                                           class="form-control @error('address_line_2') is-invalid @enderror" 
                                           value="{{ old('address_line_2', $donor->address_line_2) }}" 
                                           placeholder="Additional address details">
                                </div>
                                @error('address_line_2')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="form-footer">
                    <a href="{{ route('donors.show', $donor->id) }}" class="btn btn-cancel">
                        Cancel
                    </a>
                    <button type="reset" class="btn btn-reset">
                        Reset Changes
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save mr-2"></i>
                        Update Donor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set max dates
            const today = new Date().toISOString().split('T')[0];
            const minBirthdate = new Date();
            minBirthdate.setFullYear(minBirthdate.getFullYear() - 18);
            
            document.getElementById('last_donation_date').max = today;
            document.getElementById('birthdate').max = minBirthdate.toISOString().split('T')[0];
            
            // Mobile number formatting
            const mobileInput = document.getElementById('mobile');
            
            mobileInput.addEventListener('input', function() {
                // Remove all non-digit and plus sign characters except the first plus
                let value = this.value;
                const hasPlus = value.startsWith('+');
                
                // Keep only digits
                value = value.replace(/\D/g, '');
                
                // Add back plus if it was there
                if (hasPlus && value) {
                    value = '+' + value;
                }
                
                this.value = value;
            });

            // Hemoglobin level validation
            const hemoglobinInput = document.getElementById('hemoglobin_level');
            
            hemoglobinInput.addEventListener('change', function() {
                const value = parseFloat(this.value);
                if (!isNaN(value) && (value < 0 || value > 20)) {
                    this.classList.add('is-invalid');
                    this.nextElementSibling.innerHTML = `
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Value must be between 0 and 20 g/dL
                    `;
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            // Blood pressure validation
            const systolicInput = document.getElementById('systolic');
            const diastolicInput = document.getElementById('diastolic');
            
            function validateBloodPressure() {
                const systolic = parseInt(systolicInput.value);
                const diastolic = parseInt(diastolicInput.value);
                
                if (!isNaN(systolic) && (systolic < 70 || systolic > 200)) {
                    systolicInput.classList.add('is-invalid');
                } else {
                    systolicInput.classList.remove('is-invalid');
                }
                
                if (!isNaN(diastolic) && (diastolic < 40 || diastolic > 130)) {
                    diastolicInput.classList.add('is-invalid');
                } else {
                    diastolicInput.classList.remove('is-invalid');
                }
                
                if (!isNaN(systolic) && !isNaN(diastolic) && systolic <= diastolic) {
                    systolicInput.classList.add('is-invalid');
                    diastolicInput.classList.add('is-invalid');
                    diastolicInput.nextElementSibling.innerHTML = `
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Systolic must be greater than diastolic
                    `;
                }
            }
            
            systolicInput.addEventListener('change', validateBloodPressure);
            diastolicInput.addEventListener('change', validateBloodPressure);

            // Form validation
            const form = document.querySelector('form');
            const requiredInputs = form.querySelectorAll('[required]');
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Validate required fields
                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    }
                });

                // Validate birthdate
                const birthdateInput = document.getElementById('birthdate');
                if (birthdateInput.value) {
                    const birthdate = new Date(birthdateInput.value);
                    const minDate = new Date();
                    minDate.setFullYear(minDate.getFullYear() - 18);
                    
                    if (birthdate > minDate) {
                        birthdateInput.classList.add('is-invalid');
                        isValid = false;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields correctly.');
                }
            });

            // Reset button functionality
            const resetButton = document.querySelector('button[type="reset"]');
            const originalValues = {
                name: '{{ old("name", $donor->name) }}',
                email: '{{ old("email", $donor->email) }}',
                mobile: '{{ old("mobile", $donor->mobile) }}',
                birthdate: '{{ old("birthdate", $donor->birthdate) }}',
                fk_blood_group_id: '{{ old("fk_blood_group_id", $donor->fk_blood_group_id) }}',
                hemoglobin_level: '{{ old("hemoglobin_level", $donor->hemoglobin_level) }}',
                systolic: '{{ old("systolic", $donor->systolic) }}',
                diastolic: '{{ old("diastolic", $donor->diastolic) }}',
                last_donation_date: '{{ old("last_donation_date", $donor->last_donation_date) }}',
                country: '{{ old("country", $donor->country) }}',
                address_line_1: '{{ old("address_line_1", $donor->address_line_1) }}',
                address_line_2: '{{ old("address_line_2", $donor->address_line_2) }}'
            };

            resetButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to reset all changes to original values?')) {
                    // Reset all form fields to original values
                    document.getElementById('name').value = originalValues.name;
                    document.getElementById('email').value = originalValues.email;
                    document.getElementById('mobile').value = originalValues.mobile;
                    document.getElementById('birthdate').value = originalValues.birthdate;
                    document.getElementById('fk_blood_group_id').value = originalValues.fk_blood_group_id;
                    document.getElementById('hemoglobin_level').value = originalValues.hemoglobin_level;
                    document.getElementById('systolic').value = originalValues.systolic;
                    document.getElementById('diastolic').value = originalValues.diastolic;
                    document.getElementById('last_donation_date').value = originalValues.last_donation_date;
                    document.getElementById('country').value = originalValues.country;
                    document.getElementById('address_line_1').value = originalValues.address_line_1;
                    document.getElementById('address_line_2').value = originalValues.address_line_2;
                    
                    // Clear validation errors
                    form.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <i class="fas fa-check-circle mr-2"></i>
                        Form has been reset to original values.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;
                    
                    const existingAlert = document.querySelector('.alert-success');
                    if (existingAlert) {
                        existingAlert.remove();
                    }
                    
                    form.parentNode.insertBefore(alertDiv, form.nextSibling);
                    
                    // Auto-remove success message after 3 seconds
                    setTimeout(() => {
                        if (alertDiv.parentNode) {
                            alertDiv.remove();
                        }
                    }, 3000);
                }
            });
        });
    </script>
@endsection

<style>
    /* Blood pressure input group */
    .bp-input-group {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .bp-input-group .input-group {
        flex: 1;
    }
    
    .bp-separator {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 18px;
        min-width: 20px;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .bp-input-group {
            flex-direction: column;
            gap: 10px;
        }
        
        .bp-separator {
            display: none;
        }
    }
    
    /* Section styling */
    .form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .section-title {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }
    
    /* Form row for responsive grid */
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }
    
    /* Help text styling */
    .form-text {
        margin-top: 5px;
        font-size: 13px;
    }
    
    .form-text i {
        font-size: 12px;
    }
</style>