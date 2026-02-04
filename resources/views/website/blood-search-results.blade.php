@extends('website.master')
@section('title', 'Blood Search - ' . ($bloodType ?? 'Results'))
@section('home')
    <div class="simple-blood-results">
        @if(isset($error))
            <div class="simple-error">
                <i class="fas fa-exclamation-circle"></i>
                <h3>{{ $error }}</h3>
                <a href="{{ url('/') }}" class="btn">Search Again</a>
            </div>
        @else
            <div class="results-summary">
                <h2>{{ $bloodType }} Blood</h2>
                <p class="total-count">{{ $totalQuantity }} ML available</p>
            </div>
            
            @if($inventory->count() > 0)
                <div class="simple-inventory">
                    @foreach($inventory as $item)
                        <div class="blood-item">
                            <div class="item-info">
                                <span class="blood-group">{{ $bloodType }}</span>
                                <span class="quantity">{{ $item->quantity }} ML</span>
                                <span class="expiry">Expires: {{ \Carbon\Carbon::parse($item->expiry_date)->format('M d, Y') }}</span>
                            </div>
                            <button class="request-btn" onclick="openRequestForm({{ $item->id }}, '{{ $bloodType }}', {{ $item->quantity }}, {{ $bloodGroup->id }})">
                                Request
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-blood">
                    <i class="fas fa-tint-slash"></i>
                    <h3>No {{ $bloodType }} blood available</h3>
                    <a href="{{ url('/') }}">Search another type</a>
                </div>
            @endif
        @endif
    </div>
    
    <!-- Request Form Modal -->
    <div class="modal-overlay" id="requestModal" style="display: none;">
        <div class="modal-container">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Request Blood</h3>
                    <button class="close-btn" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="bloodRequestForm">
                        @csrf
                        <input type="hidden" id="inventoryId" name="inventory_id">
                        <input type="hidden" id="bloodType" name="blood_type">
                        <input type="hidden" id="bloodGroupId" name="blood_group_id">
                        <input type="hidden" id="maxML" name="max_ml">
                        
                        <div class="request-info">
                            <p><strong>Blood Type:</strong> <span id="displayBloodType"></span></p>
                            <p><strong>Available:</strong> <span id="displayMaxML"></span> ML</p>
                        </div>
                        
                        <div class="form-section">
                            <h4>Patient Information</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="patient_name">Full Name *</label>
                                    <input type="text" id="patient_name" name="patient_name" placeholder="Enter full name" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" placeholder="Enter email address" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" placeholder="Enter phone number">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" placeholder="Enter address (optional)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h4>Blood Request Details</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="request_ml">Request Amount (ML) *</label>
                                    <div class="ml-input">
                                        <input type="number" id="request_ml" name="request_ml" min="250" value="250" required>
                                        <span class="ml-label">ML</span>
                                    </div>
                                    <small>Minimum: 250 ML | Maximum: <span id="maxMLLabel">0</span> ML</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                            <button type="submit" class="submit-btn">
                                <span id="submitText">Submit Request</span>
                                <span id="loadingSpinner" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Modal functions
    function openRequestForm(inventoryId, bloodType, maxML, bloodGroupId) {
        try {
            document.getElementById('inventoryId').value = inventoryId;
            document.getElementById('bloodType').value = bloodType;
            document.getElementById('bloodGroupId').value = bloodGroupId;
            document.getElementById('maxML').value = maxML;
            
            // Update display
            document.getElementById('displayBloodType').textContent = bloodType;
            document.getElementById('displayMaxML').textContent = maxML;
            document.getElementById('maxMLLabel').textContent = maxML;
            document.getElementById('request_ml').max = maxML;
            document.getElementById('request_ml').value = Math.min(250, maxML);
            
            // Reset form
            document.getElementById('bloodRequestForm').reset();
            
            // Show modal
            document.getElementById('requestModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } catch (error) {
            console.warn('Error in openRequestForm:', error);
            document.getElementById('requestModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeModal() {
        document.getElementById('requestModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Handle form submission
    document.getElementById('bloodRequestForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Get form data
        const formElement = document.getElementById('bloodRequestForm');
        const formData = new FormData(formElement);
        
        // Add additional fields
        formData.append('inventory_id', document.getElementById('inventoryId').value);
        formData.append('blood_group_id', document.getElementById('bloodGroupId').value);
        formData.append('max_ml', document.getElementById('maxML').value);
        
        // Validation
        const patientName = document.getElementById('patient_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const requestML = parseInt(document.getElementById('request_ml').value);
        const maxML = parseInt(document.getElementById('maxML').value);
        
        if (!patientName) {
            alert('Please enter patient name');
            return;
        }
        
        if (!email) {
            alert('Please enter email address');
            return;
        }
        
        if (!requestML || requestML < 250 || requestML > maxML) {
            alert(`Please enter a valid amount between 250 and ${maxML} ML`);
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('.submit-btn');
        const submitText = document.getElementById('submitText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        
        submitText.style.display = 'none';
        loadingSpinner.style.display = 'inline';
        submitBtn.disabled = true;
        
        try {
            // Using your exact route: /blood-request
            const response = await fetch("/blood-request", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (response.ok) {
                showSuccessMessage({
                    patient_name: patientName,
                    blood_type: document.getElementById('bloodType').value,
                    request_ml: requestML,
                    email: email
                });
            } else {
                throw new Error(result.message || 'Submission failed');
            }
            
        } catch (error) {
            alert('Error: ' + error.message);
        } finally {
            submitText.style.display = 'inline';
            loadingSpinner.style.display = 'none';
            submitBtn.disabled = false;
        }
    });
    
    function showSuccessMessage(formData) {
        // Hide form
        document.querySelector('.modal-body').style.display = 'none';
        
        // Show success message
        const successHTML = `
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <h3>Request Submitted Successfully!</h3>
                <div class="success-details">
                    <p><strong>Patient:</strong> ${formData.patient_name}</p>
                    <p><strong>Blood Type:</strong> ${formData.blood_type}</p>
                    <p><strong>Requested Amount:</strong> ${formData.request_ml} ML</p>
                    <p><strong>Contact Email:</strong> ${formData.email}</p>
                    <p><strong>Account Created:</strong> Your patient account has been created</p>
                </div>
                <p class="confirmation-note">We will contact you at <strong>${formData.email}</strong> within 24 hours.</p>
                <button class="close-success-btn" onclick="closeModal()">Close</button>
            </div>
        `;
        
        document.querySelector('.modal-content').innerHTML = `
            <div class="modal-header">
                <h3>Request Submitted</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            ${successHTML}
        `;
    }
    
    // Close modal when clicking outside
    document.getElementById('requestModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Validate ML input
    document.getElementById('request_ml').addEventListener('change', function() {
        const max = parseInt(this.max);
        const min = 250;
        let value = parseInt(this.value);
        
        if (isNaN(value) || value < min) {
            value = min;
            this.value = min;
        } else if (value > max) {
            value = max;
            this.value = max;
            alert(`Maximum ${max} ML available`);
        }
        
        this.value = value;
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>


@endpush