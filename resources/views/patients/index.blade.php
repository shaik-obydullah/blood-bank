@extends('dashboard.master')

@section('title', 'Patients')
@section('page-title', 'Patient Management')
@section('page-subtitle', 'Manage all patients in the system')

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
        <!-- Header with horizontal layout -->
        <div class="list-header">
            <form method="GET" action="{{ route('patients.index') }}" style="display: flex; align-items: center; gap: 15px; width: 100%;">
                <div style="flex: 1; min-width: 0;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search by name, email or ID..." class="search-input" id="search-input" value="{{ request('search') }}">
                        @if(request('search'))
                        <button type="button" class="clear-search" onclick="clearSearch()">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <div style="min-width: 150px;">
                    <select class="filter-select" id="sort-select" name="sort" onchange="this.form.submit()">
                        <option value="">Sort by</option>
                        <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>ID (Ascending)</option>
                        <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>ID (Descending)</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="email_asc" {{ request('sort') == 'email_asc' ? 'selected' : '' }}>Email (A-Z)</option>
                        <option value="email_desc" {{ request('sort') == 'email_desc' ? 'selected' : '' }}>Email (Z-A)</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Last Blood Date (Oldest)</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Last Blood Date (Newest)</option>
                    </select>
                </div>
                <a href="{{ route('patients.create') }}" class="btn btn-primary btn-add">
                    <i class="fas fa-plus mr-2"></i>
                    Add Patient
                </a>
            </form>
        </div>

        <!-- Patients Table -->
        <div class="table-container">
            @if($patients->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Last Blood Taking</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>
                                    <span class="data-code">#{{ $patient->id }}</span>
                                </td>
                                <td>
                                    <div class="user-name">
                                        <strong>{{ $patient->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $patient->email }}</td>
                                <td>
                                    @if($patient->address)
                                        <span class="data-desc">
                                            {{ Str::limit($patient->address, 40) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($patient->last_blood_taking_date)
                                        <div class="created-time">
                                            {{ date('Y-m-d', strtotime($patient->last_blood_taking_date)) }}
                                        </div>
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('patients.show', $patient->id) }}" 
                                           class="btn-action btn-view" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient->id) }}" 
                                           class="btn-action btn-edit-action" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('patients.destroy', $patient->id) }}" 
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
                @if($patients->hasPages())
                    <div class="pagination-section">
                        <div class="pagination-info">
                            Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} entries
                        </div>
                        <div class="pagination-links">
                            <ul class="pagination-list">
                                {{-- Previous Button --}}
                                <li class="pagination-item {{ $patients->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $patients->previousPageUrl() ? $patients->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
                                       class="pagination-link prev-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                {{-- Page Numbers --}}
                                @php
                                    $current = $patients->currentPage();
                                    $last = $patients->lastPage();
                                    $start = max($current - 2, 1);
                                    $end = min($current + 2, $last);
                                @endphp
                                
                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="pagination-item {{ $i == $current ? 'active' : '' }}">
                                        <a href="{{ $patients->url($i) . '&' . http_build_query(request()->except('page')) }}" 
                                           class="pagination-link">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                {{-- Next Button --}}
                                <li class="pagination-item {{ !$patients->hasMorePages() ? 'disabled' : '' }}">
                                    <a href="{{ $patients->nextPageUrl() ? $patients->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
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
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <h4>No Patients Found</h4>
                    <p>
                        @if(request('search'))
                            No patients found for "{{ request('search') }}". Try a different search term.
                        @else
                            Start by adding your first patient to the system.
                        @endif
                    </p>
                    <a href="{{ route('patients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Add First Patient
                    </a>
                </div>
            @endif
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