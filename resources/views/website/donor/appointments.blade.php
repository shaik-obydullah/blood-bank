@extends('website.master')
@section('title', 'My Appointments')
@section('home')
<div class="dashboard-content">
    <!-- Header -->
    <div class="list-header">
        <div class="list-title">
            <h3>My Appointments</h3>
            <p>View and manage your donation appointments</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <div class="alert-content">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="close" onclick="this.parentElement.style.display='none'">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <div class="alert-content">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="close" onclick="this.parentElement.style.display='none'">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <div class="alert-content">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <div class="alert-title">Please fix the following errors:</div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="close" onclick="this.parentElement.style.display='none'">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <!-- Book Appointment Form -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3>Schedule New Donation</h3>
                <p>Choose a date and time for your blood donation appointment</p>
            </div>
        </div>
        
        <div class="card-body">
            <form action="{{ url('/appointment-store') }}" method="POST" class="appointment-form">
                @csrf
                <div class="form-grid">
                    <!-- Date Selection -->
                    <div class="form-group">
                        <label class="form-label">Select Date</label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <input type="date" 
                                   name="appointment_date" 
                                   class="form-control" 
                                   id="appointment-date"
                                   required
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('appointment_date') }}">
                        </div>
                    </div>
                    
                    <!-- Time Selection -->
                    <div class="form-group">
                        <label class="form-label">Select Time</label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <select name="appointment_time" 
                                    class="form-control" 
                                    id="appointment-time" 
                                    required>
                                <option value="">Choose time</option>
                                <option value="09:00" {{ old('appointment_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                <option value="10:00" {{ old('appointment_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                <option value="11:00" {{ old('appointment_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                <option value="12:00" {{ old('appointment_time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                <option value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                <option value="15:00" {{ old('appointment_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                <option value="16:00" {{ old('appointment_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                <option value="17:00" {{ old('appointment_time') == '17:00' ? 'selected' : '' }}>5:00 PM</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden datetime field for submission -->
                <input type="hidden" name="appointment_datetime" id="appointment-datetime" value="{{ old('appointment_datetime') }}">
                
                <!-- Submit Button -->
                <div class="form-footer">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-calendar-check"></i> Book Appointment
                    </button>
                    <div class="info-note">
                        <i class="fas fa-info-circle"></i>
                        <span>Appointments typically take 30-45 minutes</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Upcoming Appointments Section -->
    @if($appointments->where('status', '!=', 'Cancelled')->where('appointment_time', '>=', now())->count() > 0)
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3>Upcoming Appointments</h3>
            </div>
        </div>
        
        <div class="card-body">
            <div class="appointments-grid">
                @foreach($appointments->where('status', '!=', 'Cancelled')->where('appointment_time', '>=', now())->sortBy('appointment_time') as $appointment)
                <div class="stat-card">
                    <div class="stat-icon" style="background: {{ $appointment->status == 'Confirmed' ? '#d4edda' : '#fff3cd' }}">
                        <i class="fas fa-calendar-alt" style="color: {{ $appointment->status == 'Confirmed' ? '#155724' : '#856404' }};"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $appointment->appointment_time->format('g:i A') }}</h3>
                        <p>{{ $appointment->appointment_time->format('l, F j, Y') }}</p>
                        <span class="status-badge {{ $appointment->status == 'Confirmed' ? 'status-confirmed' : 'status-pending' }}">
                            <i class="fas {{ $appointment->status == 'Confirmed' ? 'fa-check' : 'fa-clock' }}"></i>
                            {{ $appointment->status }}
                        </span>
                        
                        @if($appointment->doctor)
                        <div class="doctor-info">
                            <div class="doctor-label">Doctor</div>
                            <div class="doctor-name">{{ $appointment->doctor->name }}</div>
                        </div>
                        @endif
                        
                        @if($appointment->isUpcoming())
                        <div class="time-info">
                            <i class="fas fa-hourglass-half"></i>
                            {{ $appointment->time_until ?? 'Upcoming' }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Past Appointments Section -->
    @if($appointments->where('status', 'Cancelled')->count() > 0 || $appointments->where('appointment_time', '<', now())->where('status', '!=', 'Cancelled')->count() > 0)
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3>Appointment History</h3>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Doctor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $pastAppointments = $appointments->where('status', 'Cancelled')
                            ->merge($appointments->where('appointment_time', '<', now())
                            ->where('status', '!=', 'Cancelled'))
                            ->sortByDesc('appointment_time');
                    @endphp
                    
                    @foreach($pastAppointments as $appointment)
                    <tr>
                        <td>
                            <div class="appointment-time">{{ $appointment->appointment_time->format('M d, Y') }}</div>
                            <div class="appointment-date">{{ $appointment->appointment_time->format('h:i A') }}</div>
                        </td>
                        <td>
                            @if($appointment->status == 'Completed')
                                <span class="status-badge status-badge-completed">Completed</span>
                            @elseif($appointment->status == 'Cancelled')
                                <span class="status-badge status-badge-cancelled">Cancelled</span>
                            @else
                                <span class="status-badge status-badge-past">Past</span>
                            @endif
                        </td>
                        <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                        <td>
                            <div class="action-group">
                                @if($appointment->status == 'Pending' && $appointment->appointment_time > now())
                                <form action="{{ route('appointment.cancel', $appointment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                            class="cancel-btn">
                                        <i class="fas fa-times"></i>
                                        Cancel
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($appointments->count() == 0)
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-calendar-plus"></i>
        </div>
        <h4>Ready to Make a Difference?</h4>
        <p>You haven't scheduled any appointments yet. Book your first donation appointment above to start saving lives today!</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div>
                    <h3>Save Lives</h3>
                    <p>Each donation can save up to 3 lives</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: #28a745;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h3>Quick Process</h3>
                    <p>Takes only 30-45 minutes</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('appointment-date');
    const timeSelect = document.getElementById('appointment-time');
    const datetimeInput = document.getElementById('appointment-datetime');
    
    function updateDateTime() {
        if (dateInput.value && timeSelect.value) {
            const dateTimeString = `${dateInput.value}T${timeSelect.value}:00`;
            datetimeInput.value = dateTimeString;
        }
    }
    
    dateInput.addEventListener('change', updateDateTime);
    timeSelect.addEventListener('change', updateDateTime);
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    
    // Restore form values if there was an error
    if(dateInput.value) {
        updateDateTime();
    }
});
</script>
@endsection