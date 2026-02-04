@extends('dashboard.master')

@section('title', 'Add Doctor')
@section('page-title', 'Doctors Management')
@section('page-subtitle', 'Add a new doctor to the system')

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
                    <h3>Add New Doctor</h3>
                    <p class="form-subtitle">Fill in the details below to add a new doctor</p>
                </div>
                <a href="{{ route('doctors.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('doctors.store') }}" method="POST">
                @csrf

                <div class="form-body">
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Doctor Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Enter doctor's full name" 
                                   value="{{ old('name') }}"
                                   required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Enter the doctor's full name as it should appear in the system
                        </small>
                    </div>

                    <!-- Mobile -->
                    <div class="form-group">
                        <label for="mobile" class="form-label">
                            Mobile Number
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <input type="text" 
                                   id="mobile" 
                                   name="mobile" 
                                   class="form-control @error('mobile') is-invalid @enderror" 
                                   placeholder="Enter mobile number (e.g., 01712345678)" 
                                   value="{{ old('mobile') }}">
                        </div>
                        @error('mobile')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Optional: Doctor's contact number
                        </small>
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address" class="form-label">
                            Address
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <textarea id="address" 
                                      name="address" 
                                      class="form-control @error('address') is-invalid @enderror" 
                                      placeholder="Enter doctor's address (optional)" 
                                      rows="3">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Optional: Doctor's clinic or hospital address
                        </small>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="form-footer">
                    <a href="{{ route('doctors.index') }}" class="btn btn-cancel">
                        Cancel
                    </a>
                    <button type="reset" class="btn btn-reset">
                        Reset
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-plus mr-2"></i>
                        Add Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile number formatting
            const mobileInput = document.getElementById('mobile');
            
            mobileInput.addEventListener('input', function() {
                // Remove all non-digit characters
                let value = this.value.replace(/\D/g, '');
                
                // Format as 01XXX-XXXXXX if it's a Bangladeshi number
                if (value.length > 0 && value.startsWith('01')) {
                    if (value.length <= 3) {
                        value = value;
                    } else if (value.length <= 6) {
                        value = value.replace(/(\d{3})(\d+)/, '$1-$2');
                    } else {
                        value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1-$2-$3');
                    }
                }
                
                this.value = value;
            });

            // Character counter for address
            const addressInput = document.getElementById('address');
            
            // Create and add character counter
            const addressCounter = document.createElement('div');
            addressCounter.className = 'char-counter';
            addressCounter.style.cssText = 'text-align: right; color: var(--text-light); font-size: 12px; margin-top: 5px;';
            addressInput.parentNode.appendChild(addressCounter);

            function updateAddressCounter() {
                const length = addressInput.value.length;
                addressCounter.textContent = `${length} characters`;
                
                if (length > 500) {
                    addressCounter.style.color = 'var(--danger)';
                } else if (length > 400) {
                    addressCounter.style.color = 'var(--warning)';
                } else {
                    addressCounter.style.color = 'var(--text-light)';
                }
            }

            addressInput.addEventListener('input', updateAddressCounter);
            updateAddressCounter();

            // Form validation
            const form = document.querySelector('form');
            const nameInput = document.getElementById('name');
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Clear previous errors
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Validate name
                if (!nameInput.value.trim()) {
                    nameInput.classList.add('is-invalid');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in the required fields correctly.');
                }
            });
        });
    </script>
@endsection