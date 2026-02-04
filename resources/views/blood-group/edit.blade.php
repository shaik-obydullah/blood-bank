@extends('dashboard.master')

@section('title', 'Edit Blood Group')
@section('page-title', 'Blood Groups Management')
@section('page-subtitle', 'Edit existing blood group details')

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
                    <h3>Edit Blood Group</h3>
                    <p class="form-subtitle">Update the details for: <strong>{{ $bloodGroup->name }}
                            ({{ $bloodGroup->code }})</strong></p>
                </div>
                <a href="{{ route('blood-groups.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="ml-2">Back to List</span>
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('blood-groups.update', $bloodGroup->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-body">
                    <!-- Blood Group Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Blood Group Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter blood group name (e.g., A+, B-, O+, AB-, Bombay)"
                                value="{{ old('name', $bloodGroup->name) }}" required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Enter the blood group name as it should appear in the system
                        </small>
                    </div>

                    <!-- Code -->
                    <div class="form-group">
                        <label for="code" class="form-label">
                            Code <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <input type="text" id="code" name="code"
                                class="form-control @error('code') is-invalid @enderror"
                                placeholder="Enter unique code (e.g., A+, B-, O+, AB-)"
                                value="{{ old('code', $bloodGroup->code) }}" required>
                        </div>
                        @error('code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Unique identifier code for this blood group
                        </small>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            Description
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-align-left"></i>
                            </div>
                            <textarea id="description" name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter description (optional)"
                                rows="4">{{ old('description', $bloodGroup->description) }}</textarea>
                        </div>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Optional: Add any important notes or details about this blood group
                        </small>
                    </div>

                    <!-- Preview Card -->
                    <div class="preview-card">
                        <h5>Current Details</h5>
                        <div class="preview-content">
                            <div class="preview-row">
                                <span class="preview-label">ID:</span>
                                <span class="preview-value">{{ $bloodGroup->id }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Name:</span>
                                <span class="preview-value"
                                    style="color: var(--danger); font-weight: 600;">{{ $bloodGroup->name }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Code:</span>
                                <span class="preview-value">{{ $bloodGroup->code }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Current Description:</span>
                                <span class="preview-value">{{ $bloodGroup->description ?? 'No description' }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Created:</span>
                                <span class="preview-value">{{ $bloodGroup->created_at }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Last Updated:</span>
                                <span class="preview-value">{{ $bloodGroup->updated_at }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Card -->
                    <div class="preview-card mt-4">
                        <h5>Live Preview (New Values)</h5>
                        <div class="preview-content">
                            <div class="preview-row">
                                <span class="preview-label">Blood Group:</span>
                                <span id="preview-name" class="preview-value">{{ $bloodGroup->name }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Code:</span>
                                <span id="preview-code" class="preview-value">{{ $bloodGroup->code }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Description:</span>
                                <span id="preview-description"
                                    class="preview-value">{{ $bloodGroup->description ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="form-footer">
                    <a href="{{ route('blood-groups.index') }}" class="btn btn-cancel">
                        Cancel
                    </a>
                    <a href="{{ route('blood-groups.show', $bloodGroup->id) }}" class="btn btn-reset">
                        <i class="fas fa-eye mr-2"></i>
                        <span class="ml-2">View Details</span>
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save mr-2"></i>
                        <span class="ml-2">Update Blood Group</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('name');
            const codeInput = document.getElementById('code');
            const descriptionInput = document.getElementById('description');

            const previewName = document.getElementById('preview-name');
            const previewCode = document.getElementById('preview-code');
            const previewDescription = document.getElementById('preview-description');

            function updatePreview() {
                if (nameInput.value.trim()) {
                    previewName.textContent = nameInput.value;
                    previewName.style.color = 'var(--danger)';
                    previewName.style.fontWeight = '600';
                } else {
                    previewName.textContent = '-';
                    previewName.style.color = 'inherit';
                    previewName.style.fontWeight = 'normal';
                }

                if (codeInput.value.trim()) {
                    previewCode.textContent = codeInput.value;
                } else {
                    previewCode.textContent = '-';
                }

                if (descriptionInput.value.trim()) {
                    previewDescription.textContent = descriptionInput.value;
                } else {
                    previewDescription.textContent = '-';
                }
            }

            codeInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
                this.value = this.value.replace(/\s+/g, '_');
            });

            nameInput.addEventListener('input', updatePreview);
            codeInput.addEventListener('input', updatePreview);
            descriptionInput.addEventListener('input', updatePreview);

            updatePreview();

            const descriptionCounter = document.createElement('div');
            descriptionCounter.className = 'char-counter';
            descriptionCounter.style.cssText = `
                    text-align: right;
                    color: var(--text-light);
                    font-size: 12px;
                    margin-top: 5px;
                `;
            descriptionInput.parentNode.appendChild(descriptionCounter);

            function updateCharCounter() {
                const length = descriptionInput.value.length;
                descriptionCounter.textContent = `${length} characters`;

                if (length > 500) {
                    descriptionCounter.style.color = 'var(--danger)';
                } else if (length > 400) {
                    descriptionCounter.style.color = 'var(--warning)';
                } else {
                    descriptionCounter.style.color = 'var(--text-light)';
                }
            }

            descriptionInput.addEventListener('input', updateCharCounter);
            updateCharCounter();

            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                let isValid = true;

                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                if (!nameInput.value.trim()) {
                    nameInput.classList.add('is-invalid');
                    isValid = false;
                }

                if (!codeInput.value.trim()) {
                    codeInput.classList.add('is-invalid');
                    isValid = false;
                } else if (!/^[A-Z0-9_]+$/.test(codeInput.value)) {
                    codeInput.classList.add('is-invalid');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields correctly.');
                }
            });
        });
    </script>
@endsection