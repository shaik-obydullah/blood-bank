@extends('dashboard.master')

@section('title', 'Patient Details')
@section('page-title', 'Patient Details')
@section('page-subtitle', 'View complete patient information')

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

    <!-- Patient Details Container -->
    <div class="form-container">
        <div class="form-header">
            <div class="form-title">
                <h3>Patient Details</h3>
                <p class="form-subtitle">View complete patient information</p>
            </div>
            <div>
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-back">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Patient
                </a>
                <a href="{{ route('patients.index') }}" class="btn btn-back ml-2">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>

        <div class="form-body">
            <!-- Patient Information Card -->
            <div class="preview-card">
                <h5>Personal Information</h5>
                <div class="preview-content">
                    <div class="preview-row">
                        <span class="preview-label">Patient ID:</span>
                        <span class="preview-value" id="preview-id">
                            <span class="data-code">{{ $patient->id }}</span>
                        </span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Full Name:</span>
                        <span class="preview-value" id="preview-name">
                            <strong>{{ $patient->name }}</strong>
                        </span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Email Address:</span>
                        <span class="preview-value">{{ $patient->email }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Address:</span>
                        <span class="preview-value">
                            {{ $patient->address ?: 'Not provided' }}
                        </span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Last Blood Taking Date:</span>
                        <span class="preview-value">
                            @if($patient->last_blood_taking_date)
                                {{ date('F j, Y', strtotime($patient->last_blood_taking_date)) }}
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Medical History Card -->
            @if($patient->medical_history)
            <div class="preview-card mt-4">
                <h5>Medical History</h5>
                <div class="preview-content">
                    <div class="preview-row">
                        <span class="preview-value">
                            {!! nl2br(e($patient->medical_history)) !!}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Account Information Card -->
            <div class="preview-card mt-4">
                <h5>Account Information</h5>
                <div class="preview-content">
                    <div class="preview-row">
                        <span class="preview-label">Account Created:</span>
                        <span class="preview-value">
                            {{ $patient->created_at->format('F j, Y \a\t h:i A') }}
                        </span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Last Updated:</span>
                        <span class="preview-value">
                            {{ $patient->updated_at->format('F j, Y \a\t h:i A') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('patients.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Patients
            </a>
            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-submit">
                <i class="fas fa-edit mr-2"></i>
                Edit Patient
            </a>
            <form action="{{ route('patients.destroy', $patient->id) }}" 
                  method="POST" 
                  class="d-inline delete-form">
                @csrf
                @method('DELETE')
                <button type="button" 
                        class="btn btn-cancel ml-2"
                        onclick="confirmDelete(this)">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Patient
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(button) {
        if (confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
            button.closest('.delete-form').submit();
        }
        return false;
    }

    // Add some CSS for the show view
    document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.textContent = `
            .preview-card {
                background: var(--light-gray);
                border: 1px solid var(--border);
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 20px;
            }
            .preview-card h5 {
                font-size: 16px;
                font-weight: 600;
                color: var(--text);
                margin: 0 0 15px 0;
                padding-bottom: 10px;
                border-bottom: 1px solid var(--border);
            }
            .preview-content {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .preview-row {
                display: flex;
                align-items: flex-start;
            }
            .preview-label {
                font-weight: 600;
                color: var(--text);
                min-width: 180px;
                font-size: 14px;
            }
            .preview-value {
                color: var(--text);
                font-size: 14px;
                flex: 1;
                word-break: break-word;
            }
            #preview-name {
                font-weight: 600;
                color: var(--primary);
            }
            #preview-id {
                font-family: "Courier New", monospace;
                background: var(--white);
                padding: 4px 8px;
                border-radius: 4px;
                border: 1px solid var(--border);
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endsection