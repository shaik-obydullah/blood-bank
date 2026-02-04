@extends('dashboard.master')

@section('title', 'Appointments')
@section('page-title', 'Appointments Management')
@section('page-subtitle', 'Manage all appointments in the system')

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

    <!-- Main Container -->
    <div class="list-container">
        <!-- Header -->
        <div class="list-header">
            <div class="list-title">
                <h3>Appointments ({{ $appointments->total() }})</h3>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-header">
                <h4><i class="fas fa-filter mr-2"></i>Filters</h4>
                @if(request()->hasAny(['search', 'status', 'doctor_id', 'donor_id', 'date_from', 'date_to', 'sort']))
                <a href="{{ route('appointments.index') }}" class="btn btn-clear-filters">
                    <i class="fas fa-times mr-2"></i>
                    Clear Filters
                </a>
                @endif
            </div>
            
            <form method="GET" action="{{ route('appointments.index') }}" class="filter-form">
                <div class="filter-grid">
                    <!-- Row 1: Search and Status -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="search-input" class="filter-label">
                                <i class="fas fa-search mr-2"></i>Search
                            </label>
                            <div class="search-box">
                                <input type="text" 
                                       name="search" 
                                       id="search-input"
                                       class="search-input"
                                       placeholder="Search by doctor, donor, or ID..."
                                       value="{{ request('search') }}">
                                @if(request('search'))
                                <button type="button" class="clear-search" onclick="clearSearch()">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <div class="filter-group">
                            <label for="status" class="filter-label">
                                <i class="fas fa-info-circle mr-2"></i>Status
                            </label>
                            <select id="status" name="status" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Doctor and Donor -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="doctor_id" class="filter-label">
                                <i class="fas fa-user-md mr-2"></i>Doctor
                            </label>
                            <select id="doctor_id" name="doctor_id" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Doctors</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="donor_id" class="filter-label">
                                <i class="fas fa-user-injured mr-2"></i>Donor
                            </label>
                            <select id="donor_id" name="donor_id" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Donors</option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}" {{ request('donor_id') == $donor->id ? 'selected' : '' }}>
                                        {{ $donor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: Date Range and Sort -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-alt mr-2"></i>Date Range
                            </label>
                            <div class="date-range">
                                <div class="date-input-group">
                                    <input type="date" 
                                           name="date_from" 
                                           class="date-input"
                                           value="{{ request('date_from') }}"
                                           placeholder="From Date"
                                           onchange="this.form.submit()">
                                    <span class="date-separator">to</span>
                                    <input type="date" 
                                           name="date_to" 
                                           class="date-input"
                                           value="{{ request('date_to') }}"
                                           placeholder="To Date"
                                           onchange="this.form.submit()">
                                </div>
                            </div>
                        </div>
                        
                        <div class="filter-group">
                            <label for="sort" class="filter-label">
                                <i class="fas fa-sort mr-2"></i>Sort By
                            </label>
                            <select id="sort" name="sort" class="filter-select" onchange="this.form.submit()">
                                <option value="">Default</option>
                                <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>Newest First</option>
                                <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>Oldest First</option>
                                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date (Old to New)</option>
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date (New to Old)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Appointments Table -->
        <div class="table-container">
            @if($appointments->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Doctor</th>
                            <th>Donor</th>
                            <th>Appointment Time</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="user-name">{{ $appointment->doctor->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="user-name">{{ $appointment->donor->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="appointment-time">
                                        {{ $appointment->appointment_time->format('M d, Y h:i A') }}
                                    </span>
                                </td>
                                <td>
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
                                          style="color: {{ $info['color'] }}; background-color: {{ $info['bg_color'] }};">
                                        <i class="fas fa-{{ $info['icon'] }} mr-1"></i>
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="created-time">
                                        {{ $appointment->created_at->format('M d, Y') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('appointments.show', $appointment->id) }}" 
                                           class="btn-action btn-view" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('appointments.edit', $appointment->id) }}" 
                                           class="btn-action btn-edit" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('appointments.destroy', $appointment->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn-action btn-delete" 
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
                @if($appointments->hasPages())
                    <div class="pagination-section">
                        <div class="pagination-info">
                            Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of {{ $appointments->total() }} entries
                        </div>
                        <div class="pagination-links">
                            <ul class="pagination-list">
                                {{-- Previous Button --}}
                                <li class="pagination-item {{ $appointments->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $appointments->previousPageUrl() ? $appointments->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
                                       class="pagination-link prev-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                {{-- Page Numbers --}}
                                @php
                                    $current = $appointments->currentPage();
                                    $last = $appointments->lastPage();
                                    $start = max($current - 2, 1);
                                    $end = min($current + 2, $last);
                                @endphp
                                
                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="pagination-item {{ $i == $current ? 'active' : '' }}">
                                        <a href="{{ $appointments->url($i) . '&' . http_build_query(request()->except('page')) }}" 
                                           class="pagination-link">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                {{-- Next Button --}}
                                <li class="pagination-item {{ !$appointments->hasMorePages() ? 'disabled' : '' }}">
                                    <a href="{{ $appointments->nextPageUrl() ? $appointments->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
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
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4>No Appointments Found</h4>
                    <p>
                        @if(request()->hasAny(['search', 'status', 'doctor_id', 'donor_id', 'date_from', 'date_to']))
                            No appointments match your filter criteria. Try adjusting your filters.
                        @else
                            No appointments have been scheduled yet.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .filter-section {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .filter-header h4 {
        margin: 0;
        color: #333;
        font-size: 16px;
        font-weight: 600;
    }
    
    .btn-clear-filters {
        background: #f8f9fa;
        color: #666;
        border: 1px solid #dee2e6;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-clear-filters:hover {
        background: #e9ecef;
        text-decoration: none;
    }
    
    .filter-grid {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-label {
        color: #555;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
    }
    
    .search-box {
        position: relative;
        width: 100%;
    }
    
    .search-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        padding-right: 35px;
    }
    
    .clear-search {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 4px;
    }
    
    .filter-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
        color: #333;
        font-size: 14px;
        height: 38px;
    }
    
    .date-range {
        width: 100%;
    }
    
    .date-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .date-input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        height: 38px;
    }
    
    .date-separator {
        color: #666;
        font-size: 14px;
        white-space: nowrap;
    }
    
    .user-name {
        font-weight: 500;
        color: #333;
    }
    
    .appointment-time {
        font-weight: 500;
        color: #333;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    
    .created-time {
        font-size: 13px;
        color: #666;
    }
    
    .action-group {
        display: flex;
        gap: 5px;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }
    
    .btn-view {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }
    
    .btn-view:hover {
        background: rgba(52, 152, 219, 0.2);
    }
    
    .btn-edit {
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }
    
    .btn-edit:hover {
        background: rgba(46, 204, 113, 0.2);
    }
    
    .btn-delete {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }
    
    .btn-delete:hover {
        background: rgba(231, 76, 60, 0.2);
    }
    
    @media (max-width: 992px) {
        .filter-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .date-input-group {
            flex-direction: column;
            align-items: stretch;
        }
        
        .date-separator {
            text-align: center;
        }
    }
    
    @media (max-width: 768px) {
        .filter-section {
            padding: 15px;
        }
    }
</style>

<script>
    function confirmDelete(button) {
        if (confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
            button.closest('.delete-form').submit();
        }
        return false;
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

        // Table row hover effects
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(198, 40, 40, 0.02)';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
</script>
@endsection