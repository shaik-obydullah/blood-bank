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
            <!-- Header -->
            <div class="list-header">
                <div class="list-title">
                    <h3>Doctors List</h3>
                </div>
                <div class="filter-row">
                    <!-- Search -->
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <form method="GET" action="{{ route('doctors.index') }}" class="d-inline">
                            <input type="text" name="search" class="search-input" placeholder="Search doctors..."
                                value="{{ request('search') }}">
                        </form>
                    </div>

                    <!-- Sort -->
                    <form method="GET" action="{{ route('doctors.index') }}" id="filter-form" class="d-inline">
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
                        </select>
                    </form>

                    <!-- Add Button -->
                    <a href="{{ route('doctors.create') }}" class="btn btn-add">
                        <i class="fas fa-plus mr-2"></i>
                        Add Doctor
                    </a>
                </div>
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

                                            <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn-action btn-edit"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST"
                                                class="delete-form"
                                                onsubmit="return confirm('Are you sure you want to delete this doctor?')">
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
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4>No Doctors Found</h4>
                        <p>There are no doctors in the system yet.</p>
                        <a href="{{ route('doctors.create') }}" class="btn btn-add">
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
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-submit search on input change with delay
            const searchInput = document.querySelector('.search-input');
            let searchTimeout;

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
        });
    </script>
@endsection