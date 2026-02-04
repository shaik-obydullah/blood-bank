@extends('website.master')
@section('title', 'Register New Donor')
@section('home')
    <div class="alert" id="responseMessage" style="display: none;"></div>

    <div class="form-container">
        <div class="form-header">
            <h2><i class="fas fa-user-plus"></i> Register New Donor</h2>
            <p>Complete the form below to register a new blood donor in the system</p>
        </div>

        <form id="donorRegistrationForm" class="donor-form" method="POST">
            @csrf

            <!-- Personal Information Section -->
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-user-circle"></i> Personal Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="required">Full Name</label>
                        <input type="text" id="name" name="name" required placeholder="Enter donor's full name">
                        <div class="error-message" id="name-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="mobile" class="required">Mobile Number</label>
                        <input type="tel" id="mobile" name="mobile" required placeholder="e.g., +8801712345678">
                        <div class="error-message" id="mobile-error"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="required">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="donor@example.com">
                        <div class="error-message" id="email-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="birthdate" class="required">Date of Birth</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                        <div class="error-message" id="birthdate-error"></div>
                    </div>
                </div>
            </div>

            <!-- Blood Information Section -->
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-heartbeat"></i> Blood Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fk_blood_group_id">Blood Group</label>
                        <select id="fk_blood_group_id" name="fk_blood_group_id" class="form-select">
                            <option value="">Select Blood Group</option>
                            @foreach($bloodGroups ?? [] as $bloodGroup)
                                <option value="{{ $bloodGroup->id }}">{{ $bloodGroup->name }} ({{ $bloodGroup->code }})</option>
                            @endforeach
                        </select>
                        <small style="color: var(--text-light); display: block; margin-top: 5px;">
                            Select the donor's blood type from the database
                        </small>
                        <div class="error-message" id="fk_blood_group_id-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="hemoglobin_level">Hemoglobin Level (g/dL)</label>
                        <input type="number" id="hemoglobin_level" name="hemoglobin_level" step="0.01" min="0" max="20"
                            placeholder="e.g., 14.50">
                        <small style="color: var(--text-light); display: block; margin-top: 5px;">
                            Normal range: 12.0-16.0 g/dL for women, 13.5-17.5 g/dL for men
                        </small>
                        <div class="error-message" id="hemoglobin_level-error"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="systolic">Systolic BP (mmHg)</label>
                        <input type="number" id="systolic" name="systolic" min="70" max="200" placeholder="e.g., 120">
                        <div class="error-message" id="systolic-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="diastolic">Diastolic BP (mmHg)</label>
                        <input type="number" id="diastolic" name="diastolic" min="40" max="130" placeholder="e.g., 80">
                        <div class="error-message" id="diastolic-error"></div>
                    </div>
                </div>
            </div>

            <!-- Address Information Section -->
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Address Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="address_line_1">Address Line 1</label>
                        <input type="text" id="address_line_1" name="address_line_1" placeholder="House #, Street, Area">
                        <div class="error-message" id="address_line_1-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="address_line_2">Address Line 2</label>
                        <input type="text" id="address_line_2" name="address_line_2"
                            placeholder="Additional address details">
                        <div class="error-message" id="address_line_2-error"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="country" class="required">Country</label>
                        <select id="country" name="country" class="form-select" required>
                            <option value="">Select Country</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="USA">United States</option>
                            <option value="UK">United Kingdom</option>
                            <option value="India">India</option>
                            <option value="Canada">Canada</option>
                            <option value="Australia">Australia</option>
                            <option value="Other">Other</option>
                        </select>
                        <div class="error-message" id="country-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="last_donation_date">Last Donation Date</label>
                        <input type="date" id="last_donation_date" name="last_donation_date" class="form-date">
                        <small style="color: var(--text-light); display: block; margin-top: 5px;">
                            Leave empty if first-time donor
                        </small>
                        <div class="error-message" id="last_donation_date-error"></div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="submitText">Register Donor</span>
                    <div class="spinner" id="submitSpinner"></div>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('donorRegistrationForm');
            const responseMessage = document.getElementById('responseMessage');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            // Set max date for last donation date and birthdate
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('last_donation_date').max = today;
            document.getElementById('birthdate').max = today;

            // Form submission handler
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Reset previous errors
                resetErrors();

                // Show loading state
                setLoadingState(true);

                // Hide previous messages
                responseMessage.style.display = 'none';

                // Collect form data
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                // Convert empty strings to null for nullable fields
                Object.keys(data).forEach(key => {
                    if (data[key] === '') {
                        data[key] = null;
                    }
                });

                try {
                    // Send request to Laravel backend
                    const response = await fetch('/save-donor', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        // Show success message
                        showMessage(result.message + (result.data?.id ? ` (Donor ID: ${result.data.id})` : ''), 'success');

                        // Reset form on success
                        form.reset();

                        // Redirect to home page after 2 seconds
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 2000);
                    } else {
                        // Show validation errors
                        if (result.errors) {
                            showValidationErrors(result.errors);
                        } else {
                            showMessage(result.message || 'An error occurred', 'error');
                        }
                    }

                } catch (error) {
                    showMessage('Network error. Please check your connection.', 'error');
                    console.error('Error:', error);
                } finally {
                    setLoadingState(false);
                }
            });

            // Helper functions
            function setLoadingState(isLoading) {
                submitBtn.disabled = isLoading;
                submitText.textContent = isLoading ? 'Processing...' : 'Register Donor';
                submitSpinner.style.display = isLoading ? 'inline-block' : 'none';
            }

            function showMessage(message, type) {
                responseMessage.textContent = message;
                responseMessage.className = `alert alert-${type}`;
                responseMessage.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function resetErrors() {
                // Remove error classes from inputs
                document.querySelectorAll('.error').forEach(el => {
                    el.classList.remove('error');
                });

                // Hide all error messages
                document.querySelectorAll('.error-message').forEach(el => {
                    el.style.display = 'none';
                    el.textContent = '';
                });
            }

            function showValidationErrors(errors) {
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    const errorElement = document.getElementById(`${field}-error`);

                    if (input) {
                        input.classList.add('error');
                    }

                    if (errorElement) {
                        errorElement.textContent = messages[0];
                        errorElement.style.display = 'block';
                    }
                }
            }

            // Real-time validation
            const nameInput = document.getElementById('name');
            nameInput.addEventListener('blur', function () {
                validateName(this);
            });

            const mobileInput = document.getElementById('mobile');
            mobileInput.addEventListener('blur', function () {
                validateMobile(this);
            });

            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function () {
                validateEmail(this);
            });

            const birthdateInput = document.getElementById('birthdate');
            birthdateInput.addEventListener('blur', function () {
                validateBirthdate(this);
            });

            const hemoglobinInput = document.getElementById('hemoglobin_level');
            hemoglobinInput.addEventListener('blur', function () {
                validateHemoglobin(this);
            });

            const systolicInput = document.getElementById('systolic');
            systolicInput.addEventListener('blur', function () {
                validateSystolic(this);
            });

            const diastolicInput = document.getElementById('diastolic');
            diastolicInput.addEventListener('blur', function () {
                validateDiastolic(this);
            });

            const lastDonationInput = document.getElementById('last_donation_date');
            lastDonationInput.addEventListener('change', function () {
                validateLastDonationDate(this);
            });

            function validateName(input) {
                if (input.value.trim().length < 2) {
                    showFieldError(input, 'Name must be at least 2 characters long');
                    return false;
                }
                clearFieldError(input);
                return true;
            }

            function validateMobile(input) {
                if (!input.value.trim()) {
                    showFieldError(input, 'Mobile number is required');
                    return false;
                }
                if (!/^[\+]?[1-9][\d]{0,15}$/.test(input.value.replace(/\s+/g, ''))) {
                    showFieldError(input, 'Please enter a valid mobile number');
                    return false;
                }
                clearFieldError(input);
                return true;
            }

            function validateEmail(input) {
                if (!input.value.trim()) {
                    showFieldError(input, 'Email is required');
                    return false;
                }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
                    showFieldError(input, 'Please enter a valid email address');
                    return false;
                }
                clearFieldError(input);
                return true;
            }

            function validateBirthdate(input) {
                if (!input.value) {
                    showFieldError(input, 'Date of birth is required');
                    return false;
                }

                const birthDate = new Date(input.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 18) {
                    showFieldError(input, 'Donor must be at least 18 years old');
                    return false;
                }

                clearFieldError(input);
                return true;
            }

            function validateHemoglobin(input) {
                if (input.value) {
                    const value = parseFloat(input.value);
                    if (isNaN(value) || value < 0 || value > 20) {
                        showFieldError(input, 'Please enter a valid hemoglobin level (0-20 g/dL)');
                        return false;
                    }
                }
                clearFieldError(input);
                return true;
            }

            function validateSystolic(input) {
                if (input.value) {
                    const value = parseInt(input.value);
                    if (isNaN(value) || value < 70 || value > 200) {
                        showFieldError(input, 'Please enter a valid systolic value (70-200 mmHg)');
                        return false;
                    }
                }
                clearFieldError(input);
                return true;
            }

            function validateDiastolic(input) {
                if (input.value) {
                    const value = parseInt(input.value);
                    if (isNaN(value) || value < 40 || value > 130) {
                        showFieldError(input, 'Please enter a valid diastolic value (40-130 mmHg)');
                        return false;
                    }
                }
                clearFieldError(input);
                return true;
            }

            function validateLastDonationDate(input) {
                if (input.value && input.value > today) {
                    showFieldError(input, 'Last donation date cannot be in the future');
                    input.value = '';
                    return false;
                }
                clearFieldError(input);
                return true;
            }

            function showFieldError(input, message) {
                input.classList.add('error');
                const errorId = `${input.name}-error`;
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = 'block';
                }
            }

            function clearFieldError(input) {
                input.classList.remove('error');
                const errorId = `${input.name}-error`;
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }

            // Country selector enhancement
            const countrySelect = document.getElementById('country');
            countrySelect.addEventListener('change', function () {
                if (this.value === 'Other') {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'country';
                    input.id = 'country';
                    input.placeholder = 'Enter country name';
                    input.value = '';
                    input.style.width = '100%';
                    input.className = 'form-select';

                    this.parentNode.replaceChild(input, this);
                    input.focus();
                }
            });
        });
    </script>
@endpush