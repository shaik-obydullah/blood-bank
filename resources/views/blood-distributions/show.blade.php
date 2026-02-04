@extends('dashboard.master')

@section('title', 'Blood Distribution Details')
@section('page-title', 'Blood Distributions Management')
@section('page-subtitle', 'View blood distribution details')

@section('content')
<div class="dashboard-content">
    <!-- Main Container -->
    <div class="detail-container">
        <!-- Header with Back Button -->
        <div class="detail-header">
            <div class="detail-title">
                <h3>Blood Distribution Details</h3>
                <p class="detail-subtitle">Request #{{ str_pad($bloodDistribution->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="detail-actions">
                <a href="{{ route('blood-distributions.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
                <a href="{{ route('blood-distributions.edit', $bloodDistribution->id) }}" class="btn btn-edit">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            </div>
        </div>

        <!-- Blood Distribution Details -->
        <div class="detail-body">
            <div class="detail-row">
                <!-- Left Column: Status & Patient -->
                <div class="detail-column">
                    <!-- Status Card -->
                    <div class="detail-card status-card">
                        <div class="card-header">
                            <h5>Request Status</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $statusInfo = [
                                    'pending' => ['color' => '#f39c12', 'bg_color' => '#fef5e7', 'icon' => 'clock', 'label' => 'Pending'],
                                    'approved' => ['color' => '#27ae60', 'bg_color' => '#eafaf1', 'icon' => 'check-circle', 'label' => 'Approved'],
                                    'rejected' => ['color' => '#e74c3c', 'bg_color' => '#fdedec', 'icon' => 'times-circle', 'label' => 'Rejected'],
                                    'partially_approved' => ['color' => '#3498db', 'bg_color' => '#ebf5fb', 'icon' => 'check-double', 'label' => 'Partially Approved'],
                                    'fully_approved' => ['color' => '#27ae60', 'bg_color' => '#eafaf1', 'icon' => 'check-circle', 'label' => 'Fully Approved'],
                                    'unknown' => ['color' => '#95a5a6', 'bg_color' => '#f8f9fa', 'icon' => 'question-circle', 'label' => 'Unknown']
                                ];
                                $status = $bloodDistribution->status;
                                $info = $statusInfo[$status] ?? $statusInfo['unknown'];
                            @endphp
                            <div class="status-display" style="border-left: 4px solid {{ $info['color'] }}; background-color: {{ $info['bg_color'] }}; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                                <div style="display: flex; align-items: center;">
                                    <div style="margin-right: 15px; font-size: 24px; color: {{ $info['color'] }};">
                                        <i class="fas fa-{{ $info['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h4 style="color: {{ $info['color'] }}; margin: 0; font-size: 18px;">{{ $info['label'] }}</h4>
                                        <p style="color: #666; margin: 5px 0 0; font-size: 14px;">Current request status</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Actions -->
                            @if($bloodDistribution->isPending())
                            <div class="status-actions" style="margin-top: 20px;">
                                <button type="button" 
                                        style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; margin-right: 10px;"
                                        onclick="showApproveModal({{ $bloodDistribution->id }}, {{ $bloodDistribution->request_unit }})">
                                    <i class="fas fa-check mr-2"></i>
                                    Approve Request
                                </button>
                                <form action="{{ route('blood-distributions.reject', $bloodDistribution->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="button" 
                                            style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center;"
                                            onclick="if(confirm('Are you sure you want to reject this blood distribution request?')) { this.closest('form').submit(); }">
                                        <i class="fas fa-times mr-2"></i>
                                        Reject Request
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Patient Information -->
                    <div class="detail-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px; overflow: hidden;">
                        <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <h5 style="margin: 0; color: #333;">Patient Information</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">
                            @if($bloodDistribution->patient)
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-user-injured" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Patient Name
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $bloodDistribution->patient->name }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-id-card" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Patient ID
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    #{{ str_pad($bloodDistribution->patient->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                            @else
                            <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 12px; border-radius: 4px; font-size: 14px;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Patient information not available
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Blood & Amount -->
                <div class="detail-column">
                    <!-- Blood Information -->
                    <div class="detail-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px; overflow: hidden;">
                        <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <h5 style="margin: 0; color: #333;">Blood Information</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">
                            @if($bloodDistribution->bloodGroup)
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-tint" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Blood Group
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    <span style="background: #c62828; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                                        {{ $bloodDistribution->bloodGroup->name }} ({{ $bloodDistribution->bloodGroup->code }})
                                    </span>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-info-circle" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Description
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    {{ $bloodDistribution->bloodGroup->description ?? 'No description' }}
                                </div>
                            </div>
                            @else
                            <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 12px; border-radius: 4px; font-size: 14px;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Blood group information not available
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Amount Information -->
                    <div class="detail-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px; overflow: hidden;">
                        <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <h5 style="margin: 0; color: #333;">Amount Information</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-prescription-bottle" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Requested Amount
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    <span style="color: #c62828; font-size: 16px; font-weight: 600;">
                                        {{ $bloodDistribution->request_unit }} ML
                                    </span>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-check-circle" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Approved Amount
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    @if($bloodDistribution->approved_unit === null)
                                        <span style="color: #999;">Pending</span>
                                    @elseif($bloodDistribution->approved_unit == 0)
                                        <span style="color: #e74c3c;">Rejected (0 ML)</span>
                                    @else
                                        <span style="color: #27ae60; font-size: 16px; font-weight: 600;">
                                            {{ $bloodDistribution->approved_unit }} ML
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($bloodDistribution->approved_unit !== null && $bloodDistribution->approved_unit > 0)
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                    <i class="fas fa-percentage" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                    Approval Rate
                                </div>
                                <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                    <span style="color: #3498db; font-size: 16px; font-weight: 600;">
                                        {{ number_format($bloodDistribution->approval_percentage, 1) }}%
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="detail-card system-card" style="background: white; border: 1px solid #eee; border-radius: 6px; margin-top: 20px; overflow: hidden;">
                <div class="card-header" style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee;">
                    <h5 style="margin: 0; color: #333;">System Information</h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                <i class="fas fa-hashtag" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                Request ID
                            </div>
                            <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                #{{ str_pad($bloodDistribution->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                <i class="fas fa-calendar-plus" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                Created At
                            </div>
                            <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                {{ $bloodDistribution->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                <i class="fas fa-calendar-check" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                Last Updated
                            </div>
                            <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                {{ $bloodDistribution->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="color: #666; display: flex; align-items: center; font-size: 14px;">
                                <i class="fas fa-clock" style="margin-right: 8px; width: 20px; text-align: center;"></i>
                                Duration
                            </div>
                            <div style="color: #333; font-weight: 500; text-align: right; max-width: 60%; word-break: break-word;">
                                Active for {{ $bloodDistribution->created_at->diffForHumans(now(), true) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="detail-card danger-card" style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; margin-top: 20px; overflow: hidden;">
                <div class="card-header" style="background: #f8d7da; padding: 15px 20px; border-bottom: 1px solid #f5c6cb;">
                    <h5 style="margin: 0; color: #721c24;">Danger Zone</h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <p style="color: #856404; margin-bottom: 15px;">
                        Once you delete this blood distribution, there is no going back. Please be certain.
                    </p>
                    <form action="{{ route('blood-distributions.destroy', $bloodDistribution->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center;"
                                onclick="if(confirm('Are you sure you want to delete blood distribution request #{{ str_pad($bloodDistribution->id, 6, '0', STR_PAD_LEFT) }}? This action cannot be undone.')) { this.closest('form').submit(); }">
                            <i class="fas fa-trash mr-2"></i>
                            Delete this Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Blood Distribution</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="approved_amount">Approved Amount (ML)</label>
                        <input type="number" 
                               class="form-control" 
                               id="approved_amount" 
                               name="approved_unit"
                               min="1"
                               required>
                        <small class="form-text text-muted">
                            Maximum amount: <span id="max_amount">0</span> ML
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showApproveModal(distributionId, maxAmount) {
        document.getElementById('max_amount').textContent = maxAmount;
        document.getElementById('approved_amount').max = maxAmount;
        document.getElementById('approved_amount').value = maxAmount;
        
        const form = document.getElementById('approveForm');
        form.action = `/blood-distributions/${distributionId}/approve`;
        
        $('#approveModal').modal('show');
    }
</script>
@endsection