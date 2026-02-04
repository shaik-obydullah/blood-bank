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
    <div class="stats-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: white; border-radius: 8px; padding: 20px; display: flex; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="stat-icon" style="width: 50px; height: 50px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: white; font-size: 20px; background: #3498db;">
                <i class="fas fa-tint"></i>
            </div>
            <div class="stat-info">
                <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #333;">{{ $stats['total_requests'] }}</h3>
                <p style="margin: 5px 0 0; color: #666; font-size: 14px;">Total Requests</p>
            </div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 8px; padding: 20px; display: flex; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="stat-icon" style="width: 50px; height: 50px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: white; font-size: 20px; background: #2ecc71;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #333;">{{ $stats['approved_requests'] }}</h3>
                <p style="margin: 5px 0 0; color: #666; font-size: 14px;">Approved Requests</p>
            </div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 8px; padding: 20px; display: flex; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="stat-icon" style="width: 50px; height: 50px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: white; font-size: 20px; background: #e74c3c;">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #333;">{{ $stats['rejected_requests'] }}</h3>
                <p style="margin: 5px 0 0; color: #666; font-size: 14px;">Rejected Requests</p>
            </div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 8px; padding: 20px; display: flex; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="stat-icon" style="width: 50px; height: 50px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: white; font-size: 20px; background: #f39c12;">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-info">
                <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #333;">{{ $stats['pending_requests'] }}</h3>
                <p style="margin: 5px 0 0; color: #666; font-size: 14px;">Pending Requests</p>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="list-container" style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div class="list-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding: 30px 30px 20px; border-bottom: 1px solid #eee;">
            <div class="list-title">
                <h3 style="color: #333; margin-bottom: 5px;">Blood Distributions ({{ $distributions->total() }})</h3>
            </div>
            <div class="list-actions" style="display: flex; gap: 10px;">
                <a href="{{ route('blood-distributions.create') }}" style="background: var(--primary); color: white; border: none; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center;">
                    <i class="fas fa-plus mr-2"></i>
                    New Request
                </a>
                <a href="{{ route('blood-distributions.statistics') }}" style="background: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center;">
                    <i class="fas fa-chart-bar mr-2"></i>
                    View Statistics
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section" style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin: 0 30px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div class="filter-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                <h4 style="margin: 0; color: #333; font-size: 16px; font-weight: 600;"><i class="fas fa-filter mr-2"></i>Filters</h4>
                @if(request()->hasAny(['search', 'status', 'patient_id', 'blood_group_id', 'date_from', 'date_to', 'sort']))
                <a href="{{ route('blood-distributions.index') }}" style="background: #f8f9fa; color: #666; border: 1px solid #dee2e6; padding: 6px 12px; border-radius: 4px; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center;">
                    <i class="fas fa-times mr-2"></i>
                    Clear Filters
                </a>
                @endif
            </div>
            
            <form method="GET" action="{{ route('blood-distributions.index') }}" style="width: 100%;">
                <div class="filter-grid" style="display: flex; flex-direction: column; gap: 15px;">
                    <!-- Row 1: Search and Status -->
                    <div class="filter-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="filter-group" style="display: flex; flex-direction: column;">
                            <label for="search-input" style="color: #555; font-size: 13px; font-weight: 500; margin-bottom: 6px; display: flex; align-items: center;">
                                <i class="fas fa-search mr-2"></i>Search
                            </label>
                            <div class="search-box" style="position: relative; width: 100%;">
                                <input type="text" 
                                       name="search" 
                                       id="search-input"
                                       style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; padding-right: 35px;"
                                       placeholder="Search by patient, blood group, or ID..."
                                       value="{{ request('search') }}">
                                @if(request('search'))
                                <button type="button" class="clear-search" onclick="clearSearch()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #999; cursor: pointer; padding: 4px;">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <div class="filter-group" style="display: flex; flex-direction: column;">
                            <label for="status" style="color: #555; font-size: 13px; font-weight: 500; margin-bottom: 6px; display: flex; align-items: center;">
                                <i class="fas fa-info-circle mr-2"></i>Status
                            </label>
                            <select id="status" name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #333; font-size: 14px; height: 38px;" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Patient and Blood Group -->
                    <div class="filter-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="filter-group" style="display: flex; flex-direction: column;">
                            <label for="patient_id" style="color: #555; font-size: 13px; font-weight: 500; margin-bottom: 6px; display: flex; align-items: center;">
                                <i class="fas fa-user-injured mr-2"></i>Patient
                            </label>
                            <select id="patient_id" name="patient_id" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #333; font-size: 14px; height: 38px;" onchange="this.form.submit()">
                                <option value="">All Patients</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group" style="display: flex; flex-direction: column;">
                            <label for="blood_group_id" style="color: #555; font-size: 13px; font-weight: 500; margin-bottom: 6px; display: flex; align-items: center;">
                                <i class="fas fa-tint mr-2"></i>Blood Group
                            </label>
                            <select id="blood_group_id" name="blood_group_id" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #333; font-size: 14px; height: 38px;" onchange="this.form.submit()">
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
                    <div class="filter-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="filter-group" style="display: flex; flex-direction: column;">
                            <label style="color: #555; font-size: 13px; font-weight: 500; margin-bottom: 6px; display: flex; align-items: center;">
                                <i class="fas fa-calendar-alt mr-2"></i>Date Range
                            </label>
                            <div class="date-range" style="width: 100%;">
                                <div class="date-input-group" style="display: flex; align-items: center; gap: 10px;">
                                    <input type="date" 
                                           name="date_from" 
                                           style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; height: 38px;"
                                           value="{{ request('date_from') }}"
                                           placeholder="From Date"
                                           onchange="this.form.submit()">
                                    <span class="date-separator" style="color: #666; font-size: 14px; white-space: nowrap;">to</span>
                                    <input type="date" 
                                           name="date_to" 
                                           style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; height: 38px;"
                                           value="{{ request('date_to') }}"
                                           placeholder="To Date"
                                           onchange="this.form.submit()">
                                </div>
                            </div>
                        </div>
                        
                        <div class="filter-group" style="display: flex; flex-direction: column;">
                            <label for="sort" style="color: #555; font-size: 13px; font-weight: 500; margin-bottom: 6px; display: flex; align-items: center;">
                                <i class="fas fa-sort mr-2"></i>Sort By
                            </label>
                            <select id="sort" name="sort" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #333; font-size: 14px; height: 38px;" onchange="this.form.submit()">
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
        <div class="table-container" style="padding: 0 30px 30px;">
            @if($distributions->count() > 0)
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 12px 15px; text-align: left; color: #333; font-weight: 600; font-size: 14px; border-bottom: 2px solid #eee; background: #f8f9fa;">ID</th>
                            <th style="padding: 12px 15px; text-align: left; color: #333; font-weight: 600; font-size: 14px; border-bottom: 2px solid #eee; background: #f8f9fa;">Patient</th>
                            <th style="padding: 12px 15px; text-align: left; color: #333; font-weight: 600; font-size: 14px; border-bottom: 2px solid #eee; background: #f8f9fa;">Blood Group</th>
                            <th style="padding: 12px 15px; text-align: left; color: #333; font-weight: 600; font-size: 14px; border-bottom: 2px solid #eee; background: #f8f9fa;">Requested</th>
                            <th style="padding: 12px 15px; text-align: left; color: #333; font-weight: 600; font-size: 14px; border-bottom: 2px solid #eee; background: #f8f9fa;">Approved</th>
                            <th style="padding: 12px 15px; text-align: left; color: #333; font-weight: 600; font-size: 14px; border-bottom: 2px solid #eee; background: #f8f9fa;">Status</th>
                            <th style="padding: 12px 15px; text-align: left; color: #333; font-weight: 600; font-size: 14px; border-bottom: 2px solid #eee; background: #f8f9fa;">Created At</th>
                            <th style="padding: 12px 15px; text-align: left; color: #333; font-weight: 600; font-size: 14px; border-bottom: 2px solid #eee; background: #f8f9fa; width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($distributions as $distribution)
                            <tr>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; color: #333;">#{{ str_pad($distribution->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; color: #333;">
                                    <div class="user-name" style="font-weight: 500; color: #333;">{{ $distribution->patient->name ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; color: #333;">
                                    <div class="blood-group" style="display: flex; flex-direction: column;">
                                        <span class="blood-badge" style="background: #c62828; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; display: inline-block; width: fit-content;">{{ $distribution->bloodGroup->code ?? 'N/A' }}</span>
                                        <span class="blood-name" style="font-size: 12px; color: #666; margin-top: 2px;">{{ $distribution->bloodGroup->name ?? '' }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; color: #333;">
                                    <span class="request-unit" style="font-weight: 500; color: #333;">{{ $distribution->request_unit }} ML</span>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; color: #333;">
                                    @if($distribution->approved_unit === null)
                                        <span class="text-muted" style="color: #999;">-</span>
                                    @else
                                        <span class="approved-unit" style="font-weight: 500; color: #333;">{{ $distribution->approved_unit }} ML</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; color: #333;">
                                    @php
                                        $statusInfo = [
                                            'pending' => ['color' => '#f39c12', 'bg_color' => '#fef5e7', 'icon' => 'clock', 'label' => 'Pending'],
                                            'approved' => ['color' => '#27ae60', 'bg_color' => '#eafaf1', 'icon' => 'check-circle', 'label' => 'Approved'],
                                            'rejected' => ['color' => '#e74c3c', 'bg_color' => '#fdedec', 'icon' => 'times-circle', 'label' => 'Rejected'],
                                            'partially_approved' => ['color' => '#3498db', 'bg_color' => '#ebf5fb', 'icon' => 'check-double', 'label' => 'Partial'],
                                            'fully_approved' => ['color' => '#27ae60', 'bg_color' => '#eafaf1', 'icon' => 'check-circle', 'label' => 'Full'],
                                            'unknown' => ['color' => '#95a5a6', 'bg_color' => '#f8f9fa', 'icon' => 'question-circle', 'label' => 'Unknown']
                                        ];
                                        $status = $distribution->status;
                                        $info = $statusInfo[$status] ?? $statusInfo['unknown'];
                                    @endphp
                                    <span class="status-badge" 
                                          style="color: {{ $info['color'] }}; background-color: {{ $info['bg_color'] }}; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; white-space: nowrap;">
                                        <i class="fas fa-{{ $info['icon'] }} mr-1"></i>
                                        {{ $info['label'] }}
                                    </span>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; color: #333;">
                                    <span class="created-time" style="font-size: 13px; color: #666;">
                                        {{ $distribution->created_at->format('M d, Y') }}
                                    </span>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; color: #333;">
                                    <div class="action-group" style="display: flex; gap: 5px;">
                                        <a href="{{ route('blood-distributions.show', $distribution->id) }}" 
                                           class="btn-action btn-view" 
                                           title="View"
                                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; background: rgba(52, 152, 219, 0.1); color: #3498db;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('blood-distributions.edit', $distribution->id) }}" 
                                           class="btn-action btn-edit" 
                                           title="Edit"
                                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; background: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($distribution->isPending())
                                        <button type="button" 
                                                class="btn-action btn-approve" 
                                                title="Approve"
                                                onclick="showApproveModal({{ $distribution->id }}, {{ $distribution->request_unit }})"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; background: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn-action btn-reject" 
                                                title="Reject"
                                                onclick="confirmReject({{ $distribution->id }})"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; background: rgba(231, 76, 60, 0.1); color: #e74c3c;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <form action="{{ route('blood-distributions.destroy', $distribution->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn-action btn-delete" 
                                                    title="Delete"
                                                    onclick="confirmDelete(this)"
                                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; background: rgba(231, 76, 60, 0.1); color: #e74c3c;">
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
                    <div class="pagination-section" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                        <div class="pagination-info" style="color: #666; font-size: 14px;">
                            Showing {{ $distributions->firstItem() }} to {{ $distributions->lastItem() }} of {{ $distributions->total() }} entries
                        </div>
                        <div class="pagination-links">
                            <ul class="pagination-list" style="display: flex; list-style: none; margin: 0; padding: 0; gap: 5px;">
                                {{-- Previous Button --}}
                                <li class="pagination-item {{ $distributions->onFirstPage() ? 'disabled' : '' }}" style="opacity: {{ $distributions->onFirstPage() ? '0.5' : '1' }};">
                                    <a href="{{ $distributions->previousPageUrl() ? $distributions->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
                                       class="pagination-link prev-link"
                                       style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 4px; background: #f8f9fa; color: #333; text-decoration: none; border: 1px solid #dee2e6;">
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
                                           class="pagination-link"
                                           style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 4px; background: {{ $i == $current ? 'var(--primary)' : '#f8f9fa' }}; color: {{ $i == $current ? 'white' : '#333' }}; text-decoration: none; border: 1px solid #dee2e6;">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor
                                
                                {{-- Next Button --}}
                                <li class="pagination-item {{ !$distributions->hasMorePages() ? 'disabled' : '' }}" style="opacity: {{ !$distributions->hasMorePages() ? '0.5' : '1' }}">
                                    <a href="{{ $distributions->nextPageUrl() ? $distributions->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
                                       class="pagination-link next-link"
                                       style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 4px; background: #f8f9fa; color: #333; text-decoration: none; border: 1px solid #dee2e6;">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state" style="text-align: center; padding: 60px 20px;">
                    <div class="empty-icon" style="margin-bottom: 20px;">
                        <i class="fas fa-tint" style="font-size: 60px; color: #ddd;"></i>
                    </div>
                    <h4 style="margin: 0 0 10px; color: #333; font-size: 20px;">No Blood Distributions Found</h4>
                    <p style="color: #666; margin-bottom: 20px; max-width: 400px; margin-left: auto; margin-right: auto;">
                        @if(request()->hasAny(['search', 'status', 'patient_id', 'blood_group_id', 'date_from', 'date_to']))
                            No blood distributions match your filter criteria. Try adjusting your filters.
                        @else
                            No blood distribution requests have been made yet.
                        @endif
                    </p>
                    <a href="{{ route('blood-distributions.create') }}" style="background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center;">
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

<style>
    @media (max-width: 992px) {
        .filter-row {
            grid-template-columns: 1fr !important;
            gap: 15px !important;
        }
        
        .date-input-group {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        
        .date-separator {
            text-align: center !important;
        }
        
        .list-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 15px !important;
        }
        
        .list-actions {
            width: 100% !important;
        }
        
        .list-actions a {
            flex: 1 !important;
            justify-content: center !important;
        }
    }
    
    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr !important;
        }
        
        .filter-section {
            padding: 15px !important;
            margin: 0 15px 20px !important;
        }
        
        .table-container {
            padding: 0 15px 30px !important;
        }
        
        .data-table {
            display: block;
            overflow-x: auto;
        }
        
        .action-group {
            flex-wrap: wrap;
        }
    }
</style>
@endsection