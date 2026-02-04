@extends('dashboard.master')

@section('title', 'Doctors')
@section('page-title', 'Doctors Management')
@section('page-subtitle', 'Manage medical doctors in the system')

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
                <form method="GET" action="{{ route('doctors.index') }}" style="display: flex; align-items: center; gap: 15px; width: 100%;">
                    <div style="flex: 1; min-width: 0;">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search doctors..." class="search-input" id="search-input" value="{{ request('search') }}">
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
                        </select>
                    </div>
                    <a href="{{ route('doctors.create') }}" class="btn btn-primary btn-add">
                        <i class="fas fa-plus mr-2"></i>
                        Add Doctor
                    </a>
                </form>
            </div>

            <!-- Table -->
            <div class="table-container">
                @if($doctors->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $doctor)
                                <tr>
                                    <td>
                                        <strong>{{ $doctor->name }}</strong>
                                    </td>
                                    <td>
                                        @if($doctor->mobile)
                                            <i class="fas fa-phone mr-1 text-muted"></i>
                                            {{ $doctor->mobile }}
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($doctor->address)
                                            <span class="data-desc">{{ Str::limit($doctor->address, 50) }}</span>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn-action btn-edit-action" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="delete-form">
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
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4>No Doctors Found</h4>
                        <p>
                            @if(request('search'))
                                No doctors found for "{{ request('search') }}". Try a different search term.
                            @else
                                There are no doctors in the system yet.
                            @endif
                        </p>
                        <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Add First Doctor
                        </a>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($doctors->hasPages())
                <div class="pagination-section">
                    <div class="pagination-info">
                        Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }} entries
                    </div>
                    <div class="pagination-links">
                        <ul class="pagination-list">
                            {{-- Previous Button --}}
                            <li class="pagination-item {{ $doctors->onFirstPage() ? 'disabled' : '' }}">
                                <a href="{{ $doctors->previousPageUrl() ? $doctors->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}"
                                    class="pagination-link prev-link">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            {{-- Page Numbers --}}
                            @php
                                $current = $doctors->currentPage();
                                $last = $doctors->lastPage();
                                $start = max($current - 2, 1);
                                $end = min($current + 2, $last);
                            @endphp

                            @for ($i = $start; $i <= $end; $i++)
                                <li class="pagination-item {{ $i == $current ? 'active' : '' }}">
                                    <a href="{{ $doctors->url($i) . '&' . http_build_query(request()->except('page')) }}"
                                        class="pagination-link">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Next Button --}}
                            <li class="pagination-item {{ !$doctors->hasMorePages() ? 'disabled' : '' }}">
                                <a href="{{ $doctors->nextPageUrl() ? $doctors->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}"
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
            if (confirm('Are you sure you want to delete this doctor?')) {
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
        });
    </script>
@endsection