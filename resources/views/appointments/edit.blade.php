@extends('dashboard.master')

@section('title', 'Edit Appointment')
@section('page-title', 'Appointments Management')
@section('page-subtitle', 'Edit existing appointment details')

@section('content')
    <div class="dashboard-content">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 pl-3 mt-2">
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
                    <h3>Edit Appointment</h3>
                    <p class="form-subtitle">
                        Update appointment details for: 
                        <strong>#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</strong>
                    </p>
                </div>
                <a href="{{ route('appointments.index') }}" class="btn btn-back">
                    <span>Back to List</span>
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-body">
                    <div class="form-section">
                        <h4 class="section-title">Participants</h4>
                        <div class="form-row">
                            <!-- Doctor Selection -->
                            <div class="form-group">
                                <label for="fk_doctor_id" class="form-label">
                                    Doctor <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <!-- Icon removed -->
                                    </div>
                                    <select id="fk_doctor_id" name="fk_doctor_id"
                                        class="form-control @error('fk_doctor_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select a doctor</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                {{ old('fk_doctor_id', $appointment->fk_doctor_id) == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }}
                                                @if($doctor->mobile)
                                                    ({{ $doctor->mobile }})
                                                @endif
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

                            <!-- Donor Selection -->
                            <div class="form-group">
                                <label for="fk_donor_id" class="form-label">
                                    Donor <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <!-- Icon removed -->
                                    </div>
                                    <select id="fk_donor_id" name="fk_donor_id"
                                        class="form-control @error('fk_donor_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select a donor</option>
                                        @foreach($donors as $donor)
                                            <option value="{{ $donor->id }}"
                                                {{ old('fk_donor_id', $appointment->fk_donor_id) == $donor->id ? 'selected' : '' }}>
                                                {{ $donor->name }}
                                                @if($donor->mobile)
                                                    ({{ $donor->mobile }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('fk_donor_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Select the donor for this appointment
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="section-title">Schedule</h4>
                        <div class="form-row">
                            <!-- Appointment Date -->
                            <div class="form-group">
                                <label for="appointment_date" class="form-label">
                                    Appointment Date <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <!-- Icon removed -->
                                    </div>
                                    <input type="date" id="appointment_date" name="appointment_date"
                                        class="form-control @error('appointment_date') is-invalid @enderror"
                                        value="{{ old('appointment_date', $appointment->appointment_time->format('Y-m-d')) }}"
                                        required>
                                </div>
                                @error('appointment_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Select the date for the appointment
                                </small>
                            </div>

                            <!-- Appointment Time -->
                            <div class="form-group">
                                <label for="appointment_time" class="form-label">
                                    Appointment Time <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <!-- Icon removed -->
                                    </div>
                                    <input type="time" id="appointment_time" name="appointment_time"
                                        class="form-control @error('appointment_time') is-invalid @enderror"
                                        value="{{ old('appointment_time', $appointment->appointment_time->format('H:i')) }}"
                                        required>
                                </div>
                                @error('appointment_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Select the time for the appointment
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="section-title">Status & Details</h4>
                        <!-- Status Selection -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-icon">
                                    <!-- Icon removed -->
                                </div>
                                <select id="status" name="status"
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                    <option value="Pending" {{ old('status', $appointment->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Confirmed" {{ old('status', $appointment->status) == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="Completed" {{ old('status', $appointment->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ old('status', $appointment->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Current status of the appointment
                            </small>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes" class="form-label">
                                Notes (Optional)
                            </label>
                            <div class="input-group">
                                <div class="input-icon">
                                    <!-- Icon removed -->
                                </div>
                                <textarea id="notes" name="notes"
                                    class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Add any notes about this appointment..."
                                    rows="3">{{ old('notes', $appointment->notes ?? '') }}</textarea>
                            </div>
                            @error('notes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="form-text text-muted">
                                    Optional: Add any important notes or details
                                </small>
                                <small class="form-text text-muted char-counter" id="notes-counter">
                                    0 characters
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Card - Current Details -->
                    <div class="preview-card">
                        <h5>Current Appointment Details</h5>
                        <div class="preview-content">
                            <div class="preview-row">
                                <span class="preview-label">Appointment ID</span>
                                <span class="preview-value">
                                    <span class="data-code">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Doctor</span>
                                <span class="preview-value text-danger fw-bold">
                                    {{ $appointment->doctor->name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Donor</span>
                                <span class="preview-value text-danger fw-bold">
                                    {{ $appointment->donor->name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Date & Time</span>
                                <span class="preview-value">
                                    {{ $appointment->appointment_time->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Status</span>
                                <span class="preview-value">
                                    @php
                                        $statusInfo = [
                                            'Pending' => ['color' => '#ff9800', 'bg_color' => '#fff3e0', 'icon' => 'clock'],
                                            'Confirmed' => ['color' => '#2196f3', 'bg_color' => '#e3f2fd', 'icon' => 'check-circle'],
                                            'Cancelled' => ['color' => '#f44336', 'bg_color' => '#ffebee', 'icon' => 'times-circle'],
                                            'Completed' => ['color' => '#4caf50', 'bg_color' => '#f1f8e9', 'icon' => 'check-double']
                                        ];
                                        $info = $statusInfo[$appointment->status] ?? $statusInfo['Pending'];
                                    @endphp
                                    <span class="status-badge status-{{ strtolower($appointment->status) }}">
                                        {{ $appointment->status }}
                                    </span>
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Created</span>
                                <span class="preview-value">
                                    {{ $appointment->created_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Last Updated</span>
                                <span class="preview-value">
                                    {{ $appointment->updated_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Card - New Values -->
                    <div class="preview-card mt-4">
                        <h5>Live Preview (New Values)</h5>
                        <div class="preview-content">
                            <div class="preview-row">
                                <span class="preview-label">Doctor</span>
                                <span id="preview-doctor" class="preview-value text-danger fw-bold">
                                    {{ $appointment->doctor->name ?? 'Select a doctor' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Donor</span>
                                <span id="preview-donor" class="preview-value text-danger fw-bold">
                                    {{ $appointment->donor->name ?? 'Select a donor' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Date & Time</span>
                                <span id="preview-datetime" class="preview-value">
                                    {{ $appointment->appointment_time->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Status</span>
                                <span id="preview-status" class="preview-value">
                                    <span class="status-badge status-pending">
                                        {{ $appointment->status }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Form Footer -->
                <div class="form-footer">
                    <div class="footer-actions">
                        <div class="right-actions">
                            <button type="reset" class="btn btn-reset">
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Update Appointment
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const doctorSelect = document.getElementById('fk_doctor_id');
            const donorSelect = document.getElementById('fk_donor_id');
            const dateInput = document.getElementById('appointment_date');
            const timeInput = document.getElementById('appointment_time');
            const statusSelect = document.getElementById('status');

            const previewDoctor = document.getElementById('preview-doctor');
            const previewDonor = document.getElementById('preview-donor');
            const previewDatetime = document.getElementById('preview-datetime');
            const previewStatus = document.getElementById('preview-status');

            // Store doctor and donor options for preview
            const doctorOptions = {};
            const donorOptions = {};

            @foreach($doctors as $doctor)
                doctorOptions[{{ $doctor->id }}] = '{{ $doctor->name }}';
            @endforeach

            @foreach($donors as $donor)
                donorOptions[{{ $donor->id }}] = '{{ $donor->name }}';
            @endforeach

            // Status badge classes
            const statusClasses = {
                'Pending': 'status-pending',
                'Confirmed': 'status-confirmed',
                'Cancelled': 'status-cancelled',
                'Completed': 'status-completed'
            };

            function updatePreview() {
                // Update doctor preview
                const selectedDoctorId = doctorSelect.value;
                if (selectedDoctorId && doctorOptions[selectedDoctorId]) {
                    previewDoctor.textContent = doctorOptions[selectedDoctorId];
                    previewDoctor.className = 'preview-value text-danger fw-bold';
                } else {
                    previewDoctor.textContent = 'Select a doctor';
                    previewDoctor.className = 'preview-value text-muted';
                }

                // Update donor preview
                const selectedDonorId = donorSelect.value;
                if (selectedDonorId && donorOptions[selectedDonorId]) {
                    previewDonor.textContent = donorOptions[selectedDonorId];
                    previewDonor.className = 'preview-value text-danger fw-bold';
                } else {
                    previewDonor.textContent = 'Select a donor';
                    previewDonor.className = 'preview-value text-muted';
                }

                // Update datetime preview
                if (dateInput.value && timeInput.value) {
                    const date = new Date(dateInput.value + 'T' + timeInput.value);
                    if (!isNaN(date.getTime())) {
                        const options = {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        };
                        previewDatetime.textContent = date.toLocaleDateString('en-US', options);
                        previewDatetime.className = 'preview-value';
                    } else {
                        previewDatetime.textContent = 'Invalid date/time';
                        previewDatetime.className = 'preview-value text-warning';
                    }
                } else {
                    previewDatetime.textContent = 'Select date and time';
                    previewDatetime.className = 'preview-value text-muted';
                }

                // Update status preview
                const selectedStatus = statusSelect.value;
                const statusClass = statusClasses[selectedStatus] || 'status-pending';
                previewStatus.innerHTML = `
                    <span class="status-badge ${statusClass}">
                        ${selectedStatus}
                    </span>
                `;
            }

            // Add event listeners
            doctorSelect.addEventListener('change', updatePreview);
            donorSelect.addEventListener('change', updatePreview);
            dateInput.addEventListener('input', updatePreview);
            timeInput.addEventListener('input', updatePreview);
            statusSelect.addEventListener('change', updatePreview);

            // Character counter for notes
            const notesTextarea = document.getElementById('notes');
            const notesCounter = document.getElementById('notes-counter');

            function updateNotesCounter() {
                const length = notesTextarea.value.length;
                notesCounter.textContent = `${length} characters`;

                if (length > 1000) {
                    notesCounter.className = 'form-text text-danger';
                } else if (length > 800) {
                    notesCounter.className = 'form-text text-warning';
                } else {
                    notesCounter.className = 'form-text text-muted';
                }
            }

            notesTextarea.addEventListener('input', updateNotesCounter);
            updateNotesCounter();

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                let isValid = true;
                const errorMessages = [];

                // Clear previous error styles
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Validate required fields
                if (!doctorSelect.value) {
                    doctorSelect.classList.add('is-invalid');
                    errorMessages.push('Please select a doctor');
                    isValid = false;
                }

                if (!donorSelect.value) {
                    donorSelect.classList.add('is-invalid');
                    errorMessages.push('Please select a donor');
                    isValid = false;
                }

                if (!dateInput.value) {
                    dateInput.classList.add('is-invalid');
                    errorMessages.push('Please select a date');
                    isValid = false;
                }

                if (!timeInput.value) {
                    timeInput.classList.add('is-invalid');
                    errorMessages.push('Please select a time');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    
                    // Show error alert
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 pl-3 mt-2">
                            ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;
                    
                    // Insert after existing alerts
                    const existingAlerts = document.querySelector('.dashboard-content').querySelectorAll('.alert');
                    if (existingAlerts.length > 0) {
                        existingAlerts[existingAlerts.length - 1].after(alertDiv);
                    } else {
                        document.querySelector('.dashboard-content').insertBefore(alertDiv, document.querySelector('.form-container'));
                    }
                    
                    // Scroll to first error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });

            // Reset button functionality
            const resetButton = document.querySelector('.btn-reset');
            resetButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to reset all changes? All unsaved data will be lost.')) {
                    form.reset();
                    updatePreview();
                    updateNotesCounter();
                    
                    // Reset validation state
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                    
                    // Show success message
                    const resetAlert = document.createElement('div');
                    resetAlert.className = 'alert alert-info alert-dismissible fade show';
                    resetAlert.innerHTML = `
                        <strong>Form reset!</strong> All fields have been reset to their original values.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;
                    
                    const existingAlerts = document.querySelector('.dashboard-content').querySelectorAll('.alert');
                    if (existingAlerts.length > 0) {
                        existingAlerts[existingAlerts.length - 1].after(resetAlert);
                    } else {
                        document.querySelector('.dashboard-content').insertBefore(resetAlert, document.querySelector('.form-container'));
                    }
                }
            });

            // Initialize preview
            updatePreview();
        });
    </script>
@endsection