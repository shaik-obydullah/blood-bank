@extends('dashboard.master')

@section('title', 'Blood Inventory')
@section('page-title', 'Blood Inventory Management')
@section('page-subtitle', 'Manage all blood inventory in the system')

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
            <form method="GET" action="{{ route('blood-inventory.index') }}" style="display: flex; align-items: center; gap: 15px; width: 100%;">
                <div style="flex: 1; min-width: 0;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search blood group or donor..." class="search-input" id="search-input" value="{{ request('search') }}">
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
                        <option value="quantity_asc" {{ request('sort') == 'quantity_asc' ? 'selected' : '' }}>Quantity (Low to High)</option>
                        <option value="quantity_desc" {{ request('sort') == 'quantity_desc' ? 'selected' : '' }}>Quantity (High to Low)</option>
                    </select>
                </div>
                <a href="{{ route('blood-inventory.create') }}" class="btn btn-primary btn-add">
                    <i class="fas fa-plus mr-2"></i>
                    Add Blood Inventory
                </a>
            </form>
        </div>

        <!-- Blood Inventory Table -->
        <div class="table-container">
            @if($inventory->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Blood Group</th>
                            <th>Donor</th>
                            <th>Quantity</th>
                            <th>Collection Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventory as $item)
                            <tr>
                                <td><span class="data-code">#{{ $item->id }}</span></td>
                                <td>
                                    @if($item->bloodGroup)
                                        <span class="blood-group">
                                            {{ $item->bloodGroup->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $item->donor->name ?? 'N/A' }}</td>
                                <td>
                                    <span>{{ $item->quantity }} ml</span>
                                </td>
                                <td>{{ date('Y-m-d', strtotime($item->collection_date)) }}</td>
                                <td>{{ date('Y-m-d', strtotime($item->expiry_date)) }}</td>
                                <td>
                                    @php
                                        $expiryDate = strtotime($item->expiry_date);
                                        $today = strtotime('today');
                                        $diffDays = round(($expiryDate - $today) / (60 * 60 * 24));
                                        
                                        if($diffDays < 0) {
                                            echo '<span class="status-badge status-cancelled">Expired</span>';
                                        } elseif($diffDays <= 7) {
                                            echo '<span class="status-badge status-pending">Expiring Soon</span>';
                                        } else {
                                            echo '<span class="status-badge status-completed">Available</span>';
                                        }
                                    @endphp
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('blood-inventory.edit', $item->id) }}" 
                                           class="btn-action btn-edit-action" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('blood-inventory.destroy', $item->id) }}" 
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
                @if($inventory->hasPages())
                    <div class="pagination-section">
                        <div class="pagination-info">
                            Showing {{ $inventory->firstItem() }} to {{ $inventory->lastItem() }} of {{ $inventory->total() }} entries
                        </div>
                        <div class="pagination-links">
                            <ul class="pagination-list">
                                {{-- Previous Button --}}
                                <li class="pagination-item {{ $inventory->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $inventory->previousPageUrl() ? $inventory->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
                                       class="pagination-link prev-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                {{-- Page Numbers --}}
                                @php
                                    $current = $inventory->currentPage();
                                    $last = $inventory->lastPage();
                                    $start = max($current - 2, 1);
                                    $end = min($current + 2, $last);
                                @endphp
                                
                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="pagination-item {{ $i == $current ? 'active' : '' }}">
                                        <a href="{{ $inventory->url($i) . '&' . http_build_query(request()->except('page')) }}" 
                                           class="pagination-link">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                {{-- Next Button --}}
                                <li class="pagination-item {{ !$inventory->hasMorePages() ? 'disabled' : '' }}">
                                    <a href="{{ $inventory->nextPageUrl() ? $inventory->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" 
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
                    <h4>No Blood Inventory Found</h4>
                    <p>
                        @if(request('search'))
                            No inventory found for "{{ request('search') }}". Try a different search term.
                        @else
                            Start by adding your first blood inventory to the system.
                        @endif
                    </p>
                    <a href="{{ route('blood-inventory.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Add First Inventory
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete(button) {
        if (confirm('Are you sure you want to delete this inventory? This action cannot be undone.')) {
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