@extends('website.master')
@section('title', 'Donor Login')
@section('home')
    <div class="donor-login-container">
        <!-- Login Card -->
        <div class="login-card">

            <!-- Header -->
            <div class="login-header">
                <div class="login-icon-container">
                    <i class="fas fa-heartbeat login-icon"></i>
                </div>
                <h2 class="login-title">Donor Login</h2>
                <p class="login-subtitle">Sign in to your donor account</p>
            </div>

            <!-- Messages -->
            @if(session('error'))
                <div class="alert-message alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert-message alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Login Form -->
            <form id="donorLoginForm" method="POST" action="{{ url('/check-donor-login') }}">
                @csrf

                <!-- Email Field -->
                <div class="login-form-group">
                    <label for="email" class="login-form-label">
                        <i class="fas fa-envelope login-form-label-icon"></i>Email Address
                    </label>
                    <div class="login-input-wrapper">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="Enter your email"
                            class="login-form-input {{ $errors->has('email') ? 'login-input-error' : '' }}">
                        <i class="fas fa-envelope login-input-icon"></i>
                    </div>
                    @error('email')
                        <div class="login-error-message">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="login-form-group">
                    <label for="password" class="login-form-label">
                        <i class="fas fa-lock login-form-label-icon"></i>Password
                    </label>
                    <div class="login-input-wrapper">
                        <input type="password" id="password" name="password" required 
                            placeholder="Enter your password"
                            class="login-form-input {{ $errors->has('password') ? 'login-input-error' : '' }}">
                        <i class="fas fa-lock login-input-icon"></i>
                        <button type="button" id="togglePassword" class="login-password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="login-error-message">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="login-submit-btn">
                    <span id="submitText">Sign In</span>
                    <div id="submitSpinner" class="login-submit-spinner"></div>
                </button>
            </form>

            <!-- Register Link -->
            <div class="login-register-section">
                <p class="login-subtitle">Don't have an account?</p>
                <a href="{{ url('/donor-registration') }}" class="login-register-link">
                    <i class="fas fa-user-plus"></i>Register as Donor
                </a>
            </div>

            <!-- Back to Home -->
            <div class="login-back-home">
                <a href="{{ url('/') }}" class="login-back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Homepage</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('donorLoginForm');
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            // Toggle password visibility
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password'
                    ? '<i class="fas fa-eye"></i>'
                    : '<i class="fas fa-eye-slash"></i>';
            });

            // Form validation
            const emailInput = document.getElementById('email');

            function validateEmail() {
                const email = emailInput.value.trim();

                if (!email) {
                    emailInput.classList.add('login-input-error');
                    return false;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    emailInput.classList.add('login-input-error');
                    return false;
                }

                emailInput.classList.remove('login-input-error');
                return true;
            }

            function validatePassword() {
                const password = passwordInput.value.trim();

                if (!password) {
                    passwordInput.classList.add('login-input-error');
                    return false;
                }

                if (password.length < 6) {
                    passwordInput.classList.add('login-input-error');
                    return false;
                }

                passwordInput.classList.remove('login-input-error');
                return true;
            }

            // Real-time validation
            emailInput.addEventListener('blur', validateEmail);
            passwordInput.addEventListener('blur', validatePassword);

            // Clear validation on input
            emailInput.addEventListener('input', function () {
                if (this.classList.contains('login-input-error')) {
                    validateEmail();
                }
            });

            passwordInput.addEventListener('input', function () {
                if (this.classList.contains('login-input-error')) {
                    validatePassword();
                }
            });

            // Form submission
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Validate form
                const isEmailValid = validateEmail();
                const isPasswordValid = validatePassword();

                if (!isEmailValid || !isPasswordValid) {
                    // Focus on first invalid field
                    if (!isEmailValid) {
                        emailInput.focus();
                    } else if (!isPasswordValid) {
                        passwordInput.focus();
                    }
                    return false;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitText.textContent = 'Signing in...';
                submitSpinner.style.display = 'block';

                // Submit the form
                this.submit();
            });

            // Auto-focus email field
            if (!emailInput.value) {
                emailInput.focus();
            }

            // Auto-hide alerts after 5 seconds
            setTimeout(function () {
                document.querySelectorAll('.alert-message').forEach(function (alert) {
                    alert.style.opacity = '0';
                    setTimeout(function () {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                });
            }, 5000);
        });
    </script>
@endsection