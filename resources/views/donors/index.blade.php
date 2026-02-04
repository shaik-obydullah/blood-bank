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
            <!-- Header with horizontal layout -->
            <div class="list-header">
                <form method="GET" action="{{ route('donors.index') }}" style="display: flex; align-items: center; gap: 15px; width: 100%;">
                    <div style="flex: 1; min-width: 0;">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search by name, mobile, or email..." class="search-input" id="search-input" value="{{ request('search') }}">
                            @if(request('search'))
                            <button type="button" class="clear-search" onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div style="min-width: 150px;">
                        <select class="filter-select" id="sort-select" name="sort" onchange="this.form.submit()">
                            <option value="id_asc" {{ request('sort') == 'id_asc' ? 'selected' : '' }}>Sort by: ID (Asc)</option>
                            <option value="id_desc" {{ request('sort') == 'id_desc' ? 'selected' : '' }}>Sort by: ID (Desc)</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Sort by: Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Sort by: Name (Z-A)</option>
                            <option value="recent_donors" {{ request('sort') == 'recent_donors' ? 'selected' : '' }}>Recent Donors</option>
                            <option value="eligible_first" {{ request('sort') == 'eligible_first' ? 'selected' : '' }}>Eligible First</option>
                        </select>
                    </div>
                    <a href="{{ route('donors.create') }}" class="btn btn-primary btn-add">
                        <i class="fas fa-user-plus mr-2"></i>
                        Add Donor
                    </a>
                </form>
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
                                        <div class="user-name">
                                            <strong>{{ $donor->name }}</strong>
                                        </div>
                                        <div>
                                            <small class="text-muted">
                                                <i class="fas fa-globe mr-1"></i>
                                                {{ $donor->country ?? 'Not specified' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($donor->bloodGroup)
                                            <span class="blood-group">
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
                                            <div class="created-time">
                                                {{ \Carbon\Carbon::parse($donor->birthdate)->format('M d, Y') }}
                                            </div>
                                            <small class="text-muted">
                                                ({{ \Carbon\Carbon::parse($donor->birthdate)->age }} years)
                                            </small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($donor->last_donation_date)
                                            <div class="created-time">
                                                {{ \Carbon\Carbon::parse($donor->last_donation_date)->format('M d, Y') }}
                                            </div>
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
                                                <i class="fas fa-check-circle"></i>
                                                Eligible
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="fas fa-times-circle"></i>
                                                Not Eligible
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('donors.show', $donor->id) }}" class="btn-action btn-view" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('donors.edit', $donor->id) }}" class="btn-action btn-edit-action" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('donors.destroy', $donor->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-action btn-delete-action" title="Delete" onclick="confirmDelete(this)">
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
                        <p>
                            @if(request('search'))
                                No donors found for "{{ request('search') }}". Try a different search term.
                            @else
                                There are no donors in the system yet.
                            @endif
                        </p>
                        <a href="{{ route('donors.create') }}" class="btn btn-primary">
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
        function confirmDelete(button) {
            if (confirm('Are you sure you want to delete this donor? This action cannot be undone.')) {
                button.closest('.delete-form').submit();
            }
            return false;
        }

        function clearSearch() {
            const searchInput = document.getElementById('search-input');
            searchInput.value = '';
            searchInput.closest('form').submit();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Auto-submit search on input with debounce
            const searchInput = document.getElementById('search-input');
            let searchTimer;
            
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        this.closest('form').submit();
                    }, 500);
                });
            }
        });
    </script>
@endsection