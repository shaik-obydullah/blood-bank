@extends('dashboard.master')

@section('title', 'Edit Doctor')
@section('page-title', 'Doctors Management')
@section('page-subtitle', 'Edit doctor details')

@section('content')
    <div class="dashboard-content">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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

        <!-- Form Container -->
        <div class="form-container">
            <!-- Header -->
            <div class="form-header">
                <div class="form-title">
                    <h3>Edit Doctor</h3>
                    <p class="form-subtitle">Update details for: <strong>{{ $doctor->name }}</strong></p>
                </div>
                <a href="{{ route('doctors.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('doctors.update', $doctor->id) }}" method="POST">
                @csrf
                @method('PUT')

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
                                   value="{{ old('name', $doctor->name) }}"
                                   required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                                   placeholder="Enter mobile number" 
                                   value="{{ old('mobile', $doctor->mobile) }}">
                        </div>
                        @error('mobile')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                                      placeholder="Enter doctor's address" 
                                      rows="3">{{ old('address', $doctor->address) }}</textarea>
                        </div>
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Current Details -->
                    <div class="preview-card">
                        <h5>Current Details</h5>
                        <div class="preview-content">
                            <div class="preview-row">
                                <span class="preview-label">ID:</span>
                                <span class="preview-value">{{ $doctor->id }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Created:</span>
                                <span class="preview-value">{{ $doctor->created_at }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-label">Last Updated:</span>
                                <span class="preview-value">{{ $doctor->updated_at }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="form-footer">
                    <a href="{{ route('doctors.index') }}" class="btn btn-cancel">
                        Cancel
                    </a>
                    <button type="reset" class="btn btn-reset">
                        Reset Changes
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save mr-2"></i>
                        Update Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format mobile number
            const mobileInput = document.getElementById('mobile');
            
            mobileInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                
                if (value.length > 2 && value.startsWith('01')) {
                    if (value.length <= 6) {
                        value = value.replace(/(\d{3})(\d+)/, '$1-$2');
                    } else {
                        value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1-$2-$3');
                    }
                }
                
                this.value = value;
            });
            
            // Reset form to original values
            document.querySelector('.btn-reset').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('form').reset();
            });
        });
    </script>
@endsection