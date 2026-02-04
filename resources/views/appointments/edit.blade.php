@extends('dashboard.master')

@section('title', 'Edit Appointment')
@section('page-title', 'Appointments Management')
@section('page-subtitle', 'Edit existing appointment details')

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
                    <h3>Edit Appointment</h3>
                    <p class="form-subtitle">Update appointment details for: <strong>Appointment #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
                </div>
                <a href="{{ route('appointments.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="ml-2">Back to List</span>
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-body">
                    <div class="form-row">
                        <!-- Doctor Selection -->
                        <div class="form-group">
                            <label for="fk_doctor_id" class="form-label">
                                Doctor <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-icon">
                                    <i class="fas fa-user-md"></i>
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
                                    <i class="fas fa-user-injured"></i>
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

                    <div class="form-row">
                        <!-- Appointment Date -->
                        <div class="form-group">
                            <label for="appointment_date" class="form-label">
                                Appointment Date <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-icon">
                                    <i class="fas fa-calendar-alt"></i>
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
                                    <i class="fas fa-clock"></i>
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

                    <!-- Status Selection -->
                    <div class="form-group">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-info-circle"></i>
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
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <textarea id="notes" name="notes"
                                class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Add any notes about this appointment..."
                                rows="3">{{ old('notes', $appointment->notes ?? '') }}</textarea>
                        </div>
                        @error('notes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Optional: Add any important notes or details about this appointment
                        </small>
                    </div>

                    <!-- Preview Card - Current Details -->
                    <div class="preview-card">
                        <h5>Current Appointment Details</h5>
                        <div class="preview-content">
                            <div class="preview-row">
                                <span class="preview-label">Appointment ID:</span>
                                <span class="preview-value">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Doctor:</span>
                                <span class="preview-value" style="color: var(--danger); font-weight: 600;">
                                    {{ $appointment->doctor->name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Donor:</span>
                                <span class="preview-value" style="color: var(--danger); font-weight: 600;">
                                    {{ $appointment->donor->name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Date & Time:</span>
                                <span class="preview-value">
                                    {{ $appointment->appointment_time->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Status:</span>
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
                                    <span class="status-badge" 
                                          style="color: {{ $info['color'] }}; background-color: {{ $info['bg_color'] }}; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                                        <i class="fas fa-{{ $info['icon'] }} mr-1"></i>
                                        {{ $appointment->status }}
                                    </span>
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Created:</span>
                                <span class="preview-value">{{ $appointment->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Last Updated:</span>
                                <span class="preview-value">{{ $appointment->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Card - New Values -->
                    <div class="preview-card mt-4">
                        <h5>Live Preview (New Values)</h5>
                        <div class="preview-content">
                            <div class="preview-row">
                                <span class="preview-label">Doctor:</span>
                                <span id="preview-doctor" class="preview-value" style="color: var(--danger); font-weight: 600;">
                                    {{ $appointment->doctor->name ?? 'Select a doctor' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Donor:</span>
                                <span id="preview-donor" class="preview-value" style="color: var(--danger); font-weight: 600;">
                                    {{ $appointment->donor->name ?? 'Select a donor' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Date & Time:</span>
                                <span id="preview-datetime" class="preview-value">
                                    {{ $appointment->appointment_time->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Status:</span>
                                <span id="preview-status" class="preview-value">
                                    <span class="status-badge" 
                                          style="color: {{ $info['color'] }}; background-color: {{ $info['bg_color'] }}; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                                        <i class="fas fa-{{ $info['icon'] }} mr-1"></i>
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
        <div class="left-actions">
            <a href="{{ route('appointments.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
        <div class="right-actions">
            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-secondary">
                <i class="fas fa-eye mr-2"></i>
                View Details
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>
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

            // Status badge styles
            const statusStyles = {
                'Pending': { color: '#ff9800', bg_color: '#fff3e0', icon: 'clock' },
                'Confirmed': { color: '#2196f3', bg_color: '#e3f2fd', icon: 'check-circle' },
                'Cancelled': { color: '#f44336', bg_color: '#ffebee', icon: 'times-circle' },
                'Completed': { color: '#4caf50', bg_color: '#f1f8e9', icon: 'check-double' }
            };

            function updatePreview() {
                // Update doctor preview
                const selectedDoctorId = doctorSelect.value;
                if (selectedDoctorId && doctorOptions[selectedDoctorId]) {
                    previewDoctor.textContent = doctorOptions[selectedDoctorId];
                    previewDoctor.style.color = 'var(--danger)';
                    previewDoctor.style.fontWeight = '600';
                } else {
                    previewDoctor.textContent = 'Select a doctor';
                    previewDoctor.style.color = '#666';
                    previewDoctor.style.fontWeight = 'normal';
                }

                // Update donor preview
                const selectedDonorId = donorSelect.value;
                if (selectedDonorId && donorOptions[selectedDonorId]) {
                    previewDonor.textContent = donorOptions[selectedDonorId];
                    previewDonor.style.color = 'var(--danger)';
                    previewDonor.style.fontWeight = '600';
                } else {
                    previewDonor.textContent = 'Select a donor';
                    previewDonor.style.color = '#666';
                    previewDonor.style.fontWeight = 'normal';
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
                    } else {
                        previewDatetime.textContent = 'Invalid date/time';
                    }
                } else {
                    previewDatetime.textContent = 'Select date and time';
                }

                // Update status preview
                const selectedStatus = statusSelect.value;
                const style = statusStyles[selectedStatus] || statusStyles['Pending'];
                previewStatus.innerHTML = `
                    <span class="status-badge" 
                          style="color: ${style.color}; background-color: ${style.bg_color}; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                        <i class="fas fa-${style.icon} mr-1"></i>
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
            const notesCounter = document.createElement('div');
            notesCounter.className = 'char-counter';
            notesCounter.style.cssText = `
                text-align: right;
                color: var(--text-light);
                font-size: 12px;
                margin-top: 5px;
            `;
            notesTextarea.parentNode.appendChild(notesCounter);

            function updateNotesCounter() {
                const length = notesTextarea.value.length;
                notesCounter.textContent = `${length} characters`;

                if (length > 1000) {
                    notesCounter.style.color = 'var(--danger)';
                } else if (length > 800) {
                    notesCounter.style.color = 'var(--warning)';
                } else {
                    notesCounter.style.color = 'var(--text-light)';
                }
            }

            notesTextarea.addEventListener('input', updateNotesCounter);
            updateNotesCounter();

            // Form validation - simplified without date/time constraints
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                let isValid = true;

                // Clear previous error styles
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Validate required fields
                if (!doctorSelect.value) {
                    doctorSelect.classList.add('is-invalid');
                    isValid = false;
                }

                if (!donorSelect.value) {
                    donorSelect.classList.add('is-invalid');
                    isValid = false;
                }

                if (!dateInput.value) {
                    dateInput.classList.add('is-invalid');
                    isValid = false;
                }

                if (!timeInput.value) {
                    timeInput.classList.add('is-invalid');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });

            // Initialize preview
            updatePreview();
        });
    </script>
@endsection