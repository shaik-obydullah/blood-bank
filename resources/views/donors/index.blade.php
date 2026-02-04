@extends('dashboard.master')

@section('title', 'Donors')
@section('page-title', 'Donors Management')
@section('page-subtitle', 'Manage blood donors in the system')

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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- List Container -->
        <div class="list-container">
            <!-- Header -->
            <div class="list-header">
                <div class="list-title">
                    <h3>Donors List</h3>
                </div>
                <div class="filter-row">
                    <!-- Search -->
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <form method="GET" action="{{ route('donors.index') }}" class="d-inline">
                            <input type="text" name="search" class="search-input"
                                placeholder="Search by name, mobile, or email..." value="{{ request('search') }}">
                        </form>
                    </div>

                    <!-- Sort -->
                    <form method="GET" action="{{ route('donors.index') }}" id="filter-form" class="d-inline">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="sort" class="sort-select" onchange="this.form.submit()">
                            <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>Sort by: ID (Asc)
                            </option>
                            <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>Sort by: ID (Desc)
                            </option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Sort by: Name (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Sort by: Name
                                (Z-A)</option>
                            <option value="recent_donors" {{ request('sort') == 'recent_donors' ? 'selected' : '' }}>Recent
                                Donors</option>
                            <option value="eligible_first" {{ request('sort') == 'eligible_first' ? 'selected' : '' }}>
                                Eligible First</option>
                        </select>
                    </form>

                    <!-- Add Button -->
                    <a href="{{ route('donors.create') }}" class="btn btn-add">
                        <i class="fas fa-user-plus mr-2"></i>
                        <span class="ml-2">Add Donor</span>
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                @if($donors->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name & Country</th>
                                <th>Blood Group</th>
                                <th>Contact</th>
                                <th>Birthdate & Age</th>
                                <th>Last Donation</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donors as $donor)
                                <tr>
                                    <td>
                                        <span class="data-id">#{{ $donor->id }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $donor->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-globe mr-1"></i>
                                            {{ $donor->country ?? 'Not specified' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($donor->bloodGroup)
                                            <span
                                                class="blood-badge blood-{{ strtolower(str_replace(['+', '-'], ['p', 'n'], $donor->bloodGroup->code)) }}">
                                                {{ $donor->bloodGroup->code }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            @if($donor->mobile)
                                                <div>
                                                    <i class="fas fa-phone mr-1 text-muted"></i>
                                                    {{ $donor->mobile }}
                                                </div>
                                            @endif
                                            @if($donor->email)
                                                <div>
                                                    <i class="fas fa-envelope mr-1 text-muted"></i>
                                                    <span class="data-desc">{{ Str::limit($donor->email, 25) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($donor->birthdate)
                                            {{ \Carbon\Carbon::parse($donor->birthdate)->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">
                                                ({{ \Carbon\Carbon::parse($donor->birthdate)->age }} years)
                                            </small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($donor->last_donation_date)
                                            {{ \Carbon\Carbon::parse($donor->last_donation_date)->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($donor->last_donation_date)->diffForHumans() }}
                                            </small>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $eligibility = $donor->getEligibilityStatus();
                                        @endphp
                                        @if($eligibility['eligible'])
                                            <span class="status-badge status-active">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Eligible
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Not Eligible
                                                @if(isset($eligibility['reason']))
                                                    <br><small>{{ $eligibility['reason'] }}</small>
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('donors.show', $donor->id) }}" class="btn-action btn-view"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('donors.edit', $donor->id) }}" class="btn-action btn-edit"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('donors.destroy', $donor->id) }}" method="POST"
                                                class="delete-form"
                                                onsubmit="return confirm('Are you sure you want to delete this donor? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <h4>No Donors Found</h4>
                        <p>There are no donors in the system yet.</p>
                        <a href="{{ route('donors.create') }}" class="btn btn-add">
                            <i class="fas fa-user-plus mr-2"></i>
                            Add First Donor
                        </a>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($donors->hasPages())
                <div class="pagination-section">
                    <div class="pagination-info">
                        Showing {{ $donors->firstItem() }} to {{ $donors->lastItem() }} of {{ $donors->total() }} entries
                    </div>
                    <div class="pagination-links">
                        <ul class="pagination-list">
                            {{-- Previous Button --}}
                            <li class="pagination-item {{ $donors->onFirstPage() ? 'disabled' : '' }}">
                                <a href="{{ $donors->previousPageUrl() ? $donors->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}"
                                    class="pagination-link prev-link">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            {{-- Page Numbers --}}
                            @php
                                $current = $donors->currentPage();
                                $last = $donors->lastPage();
                                $start = max($current - 2, 1);
                                $end = min($current + 2, $last);
                            @endphp

                            @for ($i = $start; $i <= $end; $i++)
                                <li class="pagination-item {{ $i == $current ? 'active' : '' }}">
                                    <a href="{{ $donors->url($i) . '&' . http_build_query(request()->except('page')) }}"
                                        class="pagination-link">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Next Button --}}
                            <li class="pagination-item {{ !$donors->hasMorePages() ? 'disabled' : '' }}">
                                <a href="{{ $donors->nextPageUrl() ? $donors->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}"
                                    class="pagination-link next-link">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-submit search on input change with delay
            const searchInput = document.querySelector('.search-input');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.closest('form').submit();
                    }, 500);
                });

                // Submit form when Enter is pressed in search
                searchInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.closest('form').submit();
                    }
                });
            }

            // Add confirmation for delete actions
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (!confirm('Are you sure you want to delete this donor? This action cannot be undone.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
@push('styles')
@endpush