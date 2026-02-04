@extends('dashboard.master')

@section('title', 'Blood Distributions')
@section('page-title', 'Blood Distributions Management')
@section('page-subtitle', 'Manage blood distribution requests')

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

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-tint"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_requests'] }}</h3>
                <p>Total Requests</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['approved_requests'] }}</h3>
                <p>Approved Requests</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['rejected_requests'] }}</h3>
                <p>Rejected Requests</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['pending_requests'] }}</h3>
                <p>Pending Requests</p>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="list-container">
        <!-- Header -->
        <div class="list-header">
            <div class="list-title">
                <h3>Blood Distributions ({{ $distributions->total() }})</h3>
            </div>
            <div class="list-actions" style="display: flex; gap: 10px;">
                <a href="{{ route('blood-distributions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    New Request
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-header">
                <h4>Filters</h4>
                @if(request()->hasAny(['search', 'status', 'patient_id', 'blood_group_id', 'date_from', 'date_to', 'sort']))
                <a href="{{ route('blood-distributions.index') }}" class="btn-clear-filters">
                    Clear Filters
                </a>
                @endif
            </div>
            
            <form method="GET" action="{{ route('blood-distributions.index') }}">
                <div class="filter-grid">
                    <!-- Row 1: Search and Status -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="search-input" class="filter-label">
                                Search
                            </label>
                            <div class="search-box">
                                <input type="text" 
                                       name="search" 
                                       id="search-input"
                                       class="search-input"
                                       placeholder="Search by patient, blood group, or ID..."
                                       value="{{ request('search') }}">
                                @if(request('search'))
                                <button type="button" class="clear-search" onclick="clearSearch()">
                                    Clear
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <div class="filter-group">
                            <label for="status" class="filter-label">
                                Status
                            </label>
                            <select id="status" name="status" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Patient and Blood Group -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="patient_id" class="filter-label">
                                Patient
                            </label>
                            <select id="patient_id" name="patient_id" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Patients</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="blood_group_id" class="filter-label">
                                Blood Group
                            </label>
                            <select id="blood_group_id" name="blood_group_id" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Blood Groups</option>
                                @foreach($bloodGroups as $bg)
                                    <option value="{{ $bg->id }}" {{ request('blood_group_id') == $bg->id ? 'selected' : '' }}>
                                        {{ $bg->name }} ({{ $bg->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: Date Range and Sort -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">
                                Date Range
                            </label>
                            <div class="date-range">
                                <div class="date-input-group">
                                    <div class="date-input-wrapper">
                                        <input type="date" 
                                               name="date_from" 
                                               class="date-input"
                                               value="{{ request('date_from') }}"
                                               placeholder="From Date"
                                               onchange="this.form.submit()">
                                    </div>
                                    <span class="date-separator">to</span>
                                    <div class="date-input-wrapper">
                                        <input type="date" 
                                               name="date_to" 
                                               class="date-input"
                                               value="{{ request('date_to') }}"
                                               placeholder="To Date"
                                               onchange="this.form.submit()">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="filter-group">
                            <label for="sort" class="filter-label">
                                Sort By
                            </label>
                            <select id="sort" name="sort" class="filter-select" onchange="this.form.submit()">
                                <option value="">Default</option>
                                <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>Newest First</option>
                                <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>Oldest First</option>
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date (New to Old)</option>
                                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date (Old to New)</option>
                                <option value="request_desc" {{ request('sort') == 'request_desc' ? 'selected' : '' }}>Request (High to Low)</option>
                                <option value="request_asc" {{ request('sort') == 'request_asc' ? 'selected' : '' }}>Request (Low to High)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Blood Distributions Table -->
        <div class="table-container">
            @if($distributions->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Blood Group</th>
                            <th>Requested</th>
                            <th>Approved</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($distributions as $distribution)
                            <tr>
                                <td><span class="data-code">#{{ str_pad($distribution->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <div class="user-name">
                                        <i class="fas fa-user"></i>
                                        {{ $distribution->patient->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="blood-group">{{ $distribution->bloodGroup->code ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span>{{ $distribution->request_unit }} ML</span>
                                </td>
                                <td>
                                    @if($distribution->approved_unit === null)
                                        <span class="text-muted">-</span>
                                    @else
                                        <span>{{ $distribution->approved_unit }} ML</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusInfo = [
                                            'pending' => ['class' => 'status-pending', 'icon' => 'clock', 'label' => 'Pending'],
                                            'approved' => ['class' => 'status-completed', 'icon' => 'check-circle', 'label' => 'Approved'],
                                            'rejected' => ['class' => 'status-cancelled', 'icon' => 'times-circle', 'label' => 'Rejected'],
                                            'partially_approved' => ['class' => 'status-confirmed', 'icon' => 'check-double', 'label' => 'Partial'],
                                            'fully_approved' => ['class' => 'status-completed', 'icon' => 'check-circle', 'label' => 'Full'],
                                            'unknown' => ['class' => 'status-inactive', 'icon' => 'question-circle', 'label' => 'Unknown']
                                        ];
                                        $status = $distribution->status;
                                        $info = $statusInfo[$status] ?? $statusInfo['unknown'];
                                    @endphp
                                    <span class="status-badge {{ $info['class'] }}">
                                        <i class="fas fa-{{ $info['icon'] }}"></i>
                                        {{ $info['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="created-time">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $distribution->created_at->format('M d, Y') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('blood-distributions.show', $distribution->id) }}" 
                                           class="btn-action btn-view" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('blood-distributions.edit', $distribution->id) }}" 
                                           class="btn-action btn-edit-action" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($distribution->isPending())
                                        <button type="button" 
                                                class="btn-action" 
                                                title="Reject"
                                                onclick="confirmReject({{ $distribution->id }})"
                                                style="background: rgba(231, 76, 60, 0.1); color: #e74c3c;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <form action="{{ route('blood-distributions.destroy', $distribution->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn-action btn-delete-action" 
                                                    title="Delete"
                                                    onclick="confirmDelete(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($distributions->hasPages())
                    <div class="pagination-section">
                        <div class="pagination-info">
                            Showing {{ $distributions->firstItem() }} to {{ $distributions->lastItem() }} of {{ $distributions->total() }} entries
                        </div>
                        <div class="pagination-links">
                            <ul class="pagination-list">
                                {{-- Previous Button --}}
                                <li class="pagination-item {{ $distributions->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $distributions->previousPageUrl() ? $distributions->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
                                       class="pagination-link prev-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                {{-- Page Numbers --}}
                                @php
                                    $current = $distributions->currentPage();
                                    $last = $distributions->lastPage();
                                    $start = max($current - 2, 1);
                                    $end = min($current + 2, $last);
                                @endphp
                                
                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="pagination-item {{ $i == $current ? 'active' : '' }}">
                                        <a href="{{ $distributions->url($i) . '&' . http_build_query(request()->except('page')) }}" 
                                           class="pagination-link">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor
                                
                                {{-- Next Button --}}
                                <li class="pagination-item {{ !$distributions->hasMorePages() ? 'disabled' : '' }}">
                                    <a href="{{ $distributions->nextPageUrl() ? $distributions->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
                                       class="pagination-link next-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h4>No Blood Distributions Found</h4>
                    <p>
                        @if(request()->hasAny(['search', 'status', 'patient_id', 'blood_group_id', 'date_from', 'date_to']))
                            No blood distributions match your filter criteria. Try adjusting your filters.
                        @else
                            No blood distribution requests have been made yet.
                        @endif
                    </p>
                    <a href="{{ route('blood-distributions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Request
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete(button) {
        if (confirm('Are you sure you want to delete this blood distribution? This action cannot be undone.')) {
            button.closest('.delete-form').submit();
        }
        return false;
    }

    function confirmReject(distributionId) {
        if (confirm('Are you sure you want to reject this blood distribution request?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/blood-distributions/${distributionId}/reject`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    function showApproveModal(distributionId, maxAmount) {
        document.getElementById('max_amount').textContent = maxAmount;
        document.getElementById('approved_amount').max = maxAmount;
        document.getElementById('approved_amount').value = maxAmount;
        
        const form = document.getElementById('approveForm');
        form.action = `/blood-distributions/${distributionId}/approve`;
        
        $('#approveModal').modal('show');
    }

    function clearSearch() {
        const searchInput = document.getElementById('search-input');
        searchInput.value = '';
        searchInput.closest('form').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit search on input with debounce
        const searchInput = document.getElementById('search-input');
        let searchTimer;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    });
</script>
@endsection