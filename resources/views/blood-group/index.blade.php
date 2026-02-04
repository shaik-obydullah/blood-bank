@extends('dashboard.master')

@section('title', 'Blood Groups')
@section('page-title', 'Blood Groups Management')
@section('page-subtitle', 'Manage all blood groups in the system')

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
            <div class="list-header">
                <form method="GET" action="{{ route('blood-groups.index') }}" style="display: flex; align-items: center; gap: 15px; width: 100%;">
                    <div style="flex: 1; min-width: 0;">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search blood groups..." class="search-input" id="search-input" value="{{ request('search') }}">
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
                        </select>
                    </div>
                    <a href="{{ route('blood-groups.create') }}" class="btn btn-primary btn-add">
                        <i class="fas fa-plus mr-2"></i>
                        Add Blood Group
                    </a>
                </form>
            </div>

            <!-- Blood Groups Table -->
            <div class="table-container">
                @if($bloodGroups->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Blood Group</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bloodGroups as $index => $bloodGroup)
                                <tr>
                                    <td>
                                        <span class="blood-group">
                                            {{ $bloodGroup->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="data-code">{{ $bloodGroup->code }}</span>
                                    </td>
                                    <td>
                                        @if($bloodGroup->description)
                                            <span class="data-desc">
                                                {{ Str::limit($bloodGroup->description, 60) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('blood-groups.edit', $bloodGroup->id) }}"
                                                class="btn-action btn-edit-action"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('blood-groups.destroy', $bloodGroup->id) }}"
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
                    @if($bloodGroups->hasPages())
                        <div class="pagination-section">
                            <div class="pagination-info">
                                Showing {{ $bloodGroups->firstItem() }} to {{ $bloodGroups->lastItem() }} of {{ $bloodGroups->total() }} entries
                            </div>
                            <div class="pagination-links">
                                <ul class="pagination-list">
                                    {{-- Previous Button --}}
                                    <li class="pagination-item {{ $bloodGroups->onFirstPage() ? 'disabled' : '' }}">
                                        <a href="{{ $bloodGroups->previousPageUrl() ? $bloodGroups->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}"
                                            class="pagination-link prev-link">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>

                                    {{-- Page Numbers --}}
                                    @php
                                        $current = $bloodGroups->currentPage();
                                        $last = $bloodGroups->lastPage();
                                        $start = max($current - 2, 1);
                                        $end = min($current + 2, $last);
                                    @endphp

                                    @for ($i = $start; $i <= $end; $i++)
                                        <li class="pagination-item {{ $i == $current ? 'active' : '' }}">
                                            <a href="{{ $bloodGroups->url($i) . '&' . http_build_query(request()->except('page')) }}"
                                                class="pagination-link">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    {{-- Next Button --}}
                                    <li class="pagination-item {{ !$bloodGroups->hasMorePages() ? 'disabled' : '' }}">
                                        <a href="{{ $bloodGroups->nextPageUrl() ? $bloodGroups->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}"
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
                        <h4>No Blood Groups Found</h4>
                        <p>
                            @if(request('search'))
                                No blood groups found for "{{ request('search') }}". Try a different search term.
                            @else
                                Start by adding your first blood group to the system.
                            @endif
                        </p>
                        <a href="{{ route('blood-groups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Add First Blood Group
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(button) {
            if (confirm('Are you sure you want to delete this blood group? This action cannot be undone.')) {
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

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    this.closest('form').submit();
                }, 500);
            });

            // Table row hover effects
            const tableRows = document.querySelectorAll('.data-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.backgroundColor = 'rgba(198, 40, 40, 0.02)';
                });
                row.addEventListener('mouseleave', function () {
                    this.style.backgroundColor = '';
                });
            });
        });
    </script>
@endsection