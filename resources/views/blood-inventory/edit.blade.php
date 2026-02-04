@extends('dashboard.master')

@section('title', 'Edit Blood Inventory')
@section('page-title', 'Edit Blood Inventory')
@section('page-subtitle', 'Update blood inventory information')

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

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Form Container -->
    <div class="form-container">
        <div class="form-header">
            <div class="form-title">
                <h3>Edit Blood Inventory</h3>
                <p class="form-subtitle">Update blood inventory information</p>
            </div>
            <a href="{{ route('blood-inventory.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>

        <form action="{{ route('blood-inventory.update', $bloodInventory->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="fk_blood_group_id" class="form-label">
                            Blood Group <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-tint"></i>
                            <select name="fk_blood_group_id" id="fk_blood_group_id" class="form-control" required>
                                <option value="">Select Blood Group</option>
                                @foreach($bloodGroups as $group)
                                <option value="{{ $group->id }}" 
                                    {{ old('fk_blood_group_id', $bloodInventory->fk_blood_group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} ({{ $group->code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('fk_blood_group_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="fk_donor_id" class="form-label">Donor (Optional)</label>
                        <div class="input-group">
                            <i class="input-icon fas fa-user"></i>
                            <select name="fk_donor_id" id="fk_donor_id" class="form-control">
                                <option value="">Select Donor</option>
                                @foreach($donors as $donor)
                                <option value="{{ $donor->id }}" 
                                    {{ old('fk_donor_id', $bloodInventory->fk_donor_id) == $donor->id ? 'selected' : '' }}>
                                    {{ $donor->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('fk_donor_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity" class="form-label">
                            Quantity (in ml) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-weight"></i>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   class="form-control" 
                                   value="{{ old('quantity', $bloodInventory->quantity) }}"
                                   placeholder="Enter quantity in milliliters"
                                   min="0"
                                   required>
                        </div>
                        <span class="form-text">Enter the amount in milliliters (ml)</span>
                        @error('quantity')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="collection_date" class="form-label">
                            Collection Date <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-calendar-alt"></i>
                            <input type="date" 
                                   name="collection_date" 
                                   id="collection_date" 
                                   class="form-control" 
                                   value="{{ old('collection_date', date('Y-m-d', strtotime($bloodInventory->collection_date))) }}"
                                   required>
                        </div>
                        @error('collection_date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="expiry_date" class="form-label">
                        Expiry Date <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <i class="input-icon fas fa-clock"></i>
                        <input type="date" 
                               name="expiry_date" 
                               id="expiry_date" 
                               class="form-control" 
                               value="{{ old('expiry_date', date('Y-m-d', strtotime($bloodInventory->expiry_date))) }}"
                               required>
                    </div>
                    <span class="form-text">Blood typically expires 42 days after collection</span>
                    @error('expiry_date')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-footer">
                <a href="{{ route('blood-inventory.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="reset" class="btn btn-reset">
                    <i class="fas fa-redo mr-2"></i>
                    Reset
                </button>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save mr-2"></i>
                    Update Inventory
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const collectionDate = document.getElementById('collection_date');
        const expiryDate = document.getElementById('expiry_date');
        
        // Set today as max for collection date
        const today = new Date().toISOString().split('T')[0];
        collectionDate.max = today;
        
        // Set min date for expiry date based on collection date
        collectionDate.addEventListener('change', function() {
            if(this.value) {
                const minDate = new Date(this.value);
                minDate.setDate(minDate.getDate() + 1);
                expiryDate.min = minDate.toISOString().split('T')[0];
            }
        });
        
        // Initialize min date for expiry based on current collection date
        if(collectionDate.value) {
            const minDate = new Date(collectionDate.value);
            minDate.setDate(minDate.getDate() + 1);
            expiryDate.min = minDate.toISOString().split('T')[0];
        }
    });
</script>
@endsection