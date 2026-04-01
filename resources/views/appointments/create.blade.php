@extends('dashboard.master')

@section('title', 'Add Appointment')
@section('page-title', 'Appointments Management')
@section('page-subtitle', 'Schedule a new appointment in the system')

@section('content')
    <div class="dashboard-content">
        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 pl-3 mt-1">
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
                    <h3>Schedule New Appointment</h3>
                    <p class="form-subtitle">Fill in the details below to schedule a new appointment</p>
                </div>
                <a href="{{ route('appointments.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('appointments.store') }}" method="POST" id="appointmentForm">
                @csrf

                <div class="form-body">
                    <!-- Doctor Selection -->
                    <div class="form-group">
                        <label for="fk_doctor_id" class="form-label">
                            Select Doctor <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <select id="fk_doctor_id" 
                                    name="fk_doctor_id" 
                                    class="form-control @error('fk_doctor_id') is-invalid @enderror" 
                                    required>
                                <option value="">-- Select Doctor --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('fk_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('fk_doctor_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Select the doctor for this appointment
                        </small>
                    </div>

                    <!-- Doctor Info Display (Dynamic) -->
                    <div id="doctorInfo" class="info-card" style="display: none;">
                        <div class="info-card-header">
                            <i class="fas fa-stethoscope"></i>
                            <strong>Doctor Information</strong>
                        </div>
                        <div class="info-card-body">
                            <div class="info-row">
                                <span class="info-label">Mobile:</span>
                                <span class="info-value" id="doctorMobile">-</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Address:</span>
                                <span class="info-value" id="doctorAddress">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Date & Time -->
                    <div class="form-group">
                        <label for="appointment_time" class="form-label">
                            Appointment Date & Time <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <input type="datetime-local" 
                                   id="appointment_time" 
                                   name="appointment_time" 
                                   class="form-control @error('appointment_time') is-invalid @enderror" 
                                   value="{{ old('appointment_time') }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   required>
                        </div>
                        @error('appointment_time')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Select the date and time for the appointment (must be in the future)
                        </small>
                    </div>

                    <!-- Status (Hidden, default to Pending) -->
                    <input type="hidden" name="status" value="Pending">
                </div>

                <!-- Form Footer -->
                <div class="form-footer">
                    <a href="{{ route('appointments.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="reset" class="btn btn-reset">
                        <i class="fas fa-undo mr-2"></i>
                        Reset
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Schedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doctorSelect = document.getElementById('fk_doctor_id');
            const appointmentTime = document.getElementById('appointment_time');
            const doctorInfoDiv = document.getElementById('doctorInfo');
            const doctorMobileSpan = document.getElementById('doctorMobile');
            const doctorAddressSpan = document.getElementById('doctorAddress');
            const form = document.getElementById('appointmentForm');

            // Doctors data from PHP
            const doctors = @json($doctors);

            // Doctor selection change
            doctorSelect.addEventListener('change', function() {
                const doctorId = this.value;
                if (doctorId) {
                    const doctor = doctors.find(d => d.id == doctorId);
                    if (doctor) {
                        doctorInfoDiv.style.display = 'block';
                        doctorMobileSpan.textContent = doctor.mobile || 'Not provided';
                        doctorAddressSpan.textContent = doctor.address || 'Not provided';
                    }
                } else {
                    doctorInfoDiv.style.display = 'none';
                }
            });

            // Appointment time validation
            appointmentTime.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const now = new Date();
                
                if (selectedDate <= now) {
                    alert('Appointment time must be in the future!');
                    this.value = '';
                }
            });

            // Form validation
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Clear previous errors
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Validate doctor
                if (!doctorSelect.value) {
                    doctorSelect.classList.add('is-invalid');
                    isValid = false;
                }

                // Validate appointment time
                if (!appointmentTime.value) {
                    appointmentTime.classList.add('is-invalid');
                    isValid = false;
                } else {
                    const selectedDate = new Date(appointmentTime.value);
                    const now = new Date();
                    if (selectedDate <= now) {
                        appointmentTime.classList.add('is-invalid');
                        alert('Appointment time must be in the future!');
                        isValid = false;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields correctly.');
                }
            });

            // Set minimum date for appointment
            const minDate = new Date();
            minDate.setMinutes(minDate.getMinutes() + 30);
            const year = minDate.getFullYear();
            const month = String(minDate.getMonth() + 1).padStart(2, '0');
            const day = String(minDate.getDate()).padStart(2, '0');
            const hours = String(minDate.getHours()).padStart(2, '0');
            const minutes = String(minDate.getMinutes()).padStart(2, '0');
            appointmentTime.min = `${year}-${month}-${day}T${hours}:${minutes}`;

            // Trigger doctor info if pre-selected (from old input)
            if (doctorSelect.value) {
                doctorSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection