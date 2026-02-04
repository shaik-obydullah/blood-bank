@extends('dashboard.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Welcome to BloodBank Management System')

@section('content')
<div class="dashboard-content">
    @php
        $pendingCount = \App\Models\BloodDistribution::pending()->count();
    @endphp
    
    @if($pendingCount > 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-circle me-2"></i>
            You have <strong>{{ $pendingCount }}</strong> pending blood request(s).
            <a href="/blood-distributions" class="alert-link">Review them here</a>.
        </div>
    @endif
</div>
@endsection