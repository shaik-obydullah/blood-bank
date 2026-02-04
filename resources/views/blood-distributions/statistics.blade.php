@extends('dashboard.master')

@section('title', 'Blood Distribution Statistics')
@section('page-title', 'Blood Distributions Management')
@section('page-subtitle', 'View statistics and analytics')

@section('content')
<div class="dashboard-content">
    <!-- Main Container -->
    <div class="stats-container">
        <!-- Header -->
        <div class="stats-header">
            <div class="stats-title">
                <h3>Blood Distribution Statistics</h3>
                <p class="stats-subtitle">Analytics and insights for blood distribution</p>
            </div>
            <a href="{{ route('blood-distributions.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>

        <!-- Summary Statistics -->
        <div class="summary-stats">
            <div class="stat-card large">
                <div class="stat-icon" style="background: #3498db;">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_requests'] }}</h3>
                    <p>Total Requests</p>
                    <div class="stat-trend">
                        <span class="trend-up">All time requests</span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card large">
                <div class="stat-icon" style="background: #2ecc71;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['approved_requests'] }}</h3>
                    <p>Approved Requests</p>
                    <div class="stat-trend">
                        @if($stats['total_requests'] > 0)
                            <span class="trend-up">{{ number_format(($stats['approved_requests'] / $stats['total_requests']) * 100, 1) }}% approval rate</span>
                        @else
                            <span class="trend-neutral">No data</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="stat-card large">
                <div class="stat-icon" style="background: #e74c3c;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['rejected_requests'] }}</h3>
                    <p>Rejected Requests</p>
                    <div class="stat-trend">
                        @if($stats['total_requests'] > 0)
                            <span class="trend-down">{{ number_format(($stats['rejected_requests'] / $stats['total_requests']) * 100, 1) }}% rejection rate</span>
                        @else
                            <span class="trend-neutral">No data</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Blood Amount Statistics -->
        <div class="amount-stats">
            <div class="stat-card large">
                <div class="stat-icon" style="background: #9b59b6;">
                    <i class="fas fa-prescription-bottle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['total_requested_ml']) }}</h3>
                    <p>Total Requested (ML)</p>
                    <div class="stat-trend">
                        <span class="trend-up">Blood requested</span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card large">
                <div class="stat-icon" style="background: #1abc9c;">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['total_approved_ml']) }}</h3>
                    <p>Total Approved (ML)</p>
                    <div class="stat-trend">
                        @if($stats['total_requested_ml'] > 0)
                            <span class="trend-up">{{ number_format(($stats['total_approved_ml'] / $stats['total_requested_ml']) * 100, 1) }}% of requested</span>
                        @else
                            <span class="trend-neutral">No data</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="stat-card large">
                <div class="stat-icon" style="background: #f39c12;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['pending_requests'] }}</h3>
                    <p>Pending Requests</p>
                    <div class="stat-trend">
                        @if($stats['total_requests'] > 0)
                            <span class="trend-neutral">{{ number_format(($stats['pending_requests'] / $stats['total_requests']) * 100, 1) }}% pending</span>
                        @else
                            <span class="trend-neutral">No data</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <!-- Status Distribution -->
            <div class="chart-card">
                <div class="chart-header">
                    <h4>Request Status Distribution</h4>
                </div>
                <div class="chart-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>

            <!-- Monthly Distribution -->
            <div class="chart-card">
                <div class="chart-header">
                    <h4>Monthly Distribution ({{ date('Y') }})</h4>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>

            <!-- Top Blood Groups -->
            <div class="chart-card full-width">
                <div class="chart-header">
                    <h4>Top Requested Blood Groups</h4>
                </div>
                <div class="chart-body">
                    <div class="table-responsive">
                        <table class="stats-table">
                            <thead>
                                <tr>
                                    <th>Blood Group</th>
                                    <th>Requests</th>
                                    <th>Requested (ML)</th>
                                    <th>Approved (ML)</th>
                                    <th>Approval Rate</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topBloodGroups as $item)
                                    @if($item->bloodGroup)
                                    <tr>
                                        <td>
                                            <span class="blood-badge">{{ $item->bloodGroup->code }}</span>
                                            <span class="blood-name">{{ $item->bloodGroup->name }}</span>
                                        </td>
                                        <td>{{ $item->request_count }}</td>
                                        <td>{{ number_format($item->total_requested) }} ML</td>
                                        <td>{{ number_format($item->total_approved) }} ML</td>
                                        <td>
                                            @if($item->total_requested > 0)
                                                <span class="approval-rate">{{ number_format(($item->total_approved / $item->total_requested) * 100, 1) }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->total_approved > 0)
                                                <span class="status-badge approved">Active</span>
                                            @else
                                                <span class="status-badge pending">No approvals</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Data Table -->
        <div class="data-section">
            <div class="section-header">
                <h4>Monthly Breakdown</h4>
                <p>Detailed monthly distribution data for {{ date('Y') }}</p>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Requests</th>
                            <th>Requested (ML)</th>
                            <th>Approved (ML)</th>
                            <th>Approval Rate</th>
                            <th>Efficiency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $months = [
                                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                            ];
                        @endphp
                        @foreach($months as $monthNum => $monthName)
                            @php
                                $monthData = $monthlyData->firstWhere('month', $monthNum);
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $monthName }} {{ date('Y') }}</strong>
                                </td>
                                <td>{{ $monthData->total_requests ?? 0 }}</td>
                                <td>{{ number_format($monthData->total_requested ?? 0) }} ML</td>
                                <td>{{ number_format($monthData->total_approved ?? 0) }} ML</td>
                                <td>
                                    @if(($monthData->total_requested ?? 0) > 0)
                                        <span class="approval-rate">{{ number_format((($monthData->total_approved ?? 0) / ($monthData->total_requested ?? 1)) * 100, 1) }}%</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        if(($monthData->total_requests ?? 0) > 0) {
                                            $efficiency = (($monthData->total_approved ?? 0) / max(($monthData->total_requested ?? 1), 1)) * 100;
                                            if($efficiency >= 80) {
                                                $efficiencyClass = 'high';
                                            } elseif($efficiency >= 50) {
                                                $efficiencyClass = 'medium';
                                            } else {
                                                $efficiencyClass = 'low';
                                            }
                                        } else {
                                            $efficiencyClass = 'none';
                                        }
                                    @endphp
                                    <span class="efficiency-badge {{ $efficiencyClass }}">
                                        @if($efficiencyClass == 'high')
                                            High
                                        @elseif($efficiencyClass == 'medium')
                                            Medium
                                        @elseif($efficiencyClass == 'low')
                                            Low
                                        @else
                                            No Data
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-container {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stats-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .stats-title h3 {
        color: #333;
        margin-bottom: 5px;
        font-size: 24px;
    }
    
    .stats-subtitle {
        color: #666;
        margin: 0;
        font-size: 14px;
    }
    
    .summary-stats, .amount-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card.large {
        background: white;
        border-radius: 8px;
        padding: 25px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
    }
    
    .stat-card.large .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: white;
        font-size: 24px;
    }
    
    .stat-card.large .stat-info h3 {
        margin: 0;
        font-size: 32px;
        font-weight: 600;
        color: #333;
    }
    
    .stat-card.large .stat-info p {
        margin: 5px 0;
        color: #666;
        font-size: 16px;
        font-weight: 500;
    }
    
    .stat-trend {
        margin-top: 8px;
        font-size: 13px;
    }
    
    .trend-up {
        color: #27ae60;
        background: #eafaf1;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }
    
    .trend-down {
        color: #e74c3c;
        background: #fdedec;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }
    
    .trend-neutral {
        color: #7f8c8d;
        background: #f8f9fa;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }
    
    .charts-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .chart-card {
        background: white;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .chart-card.full-width {
        grid-column: 1 / -1;
    }
    
    .chart-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }
    
    .chart-header h4 {
        margin: 0;
        color: #333;
        font-size: 16px;
        font-weight: 600;
    }
    
    .chart-body {
        padding: 20px;
    }
    
    .stats-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .stats-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        color: #333;
        font-weight: 600;
        font-size: 14px;
        border-bottom: 1px solid #eee;
    }
    
    .stats-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }
    
    .stats-table tr:hover {
        background: #f8f9fa;
    }
    
    .blood-badge {
        background: #c62828;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        margin-right: 8px;
    }
    
    .blood-name {
        color: #666;
        font-size: 14px;
    }
    
    .approval-rate {
        color: #27ae60;
        font-weight: 500;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-badge.approved {
        background: #eafaf1;
        color: #27ae60;
    }
    
    .status-badge.pending {
        background: #fef5e7;
        color: #f39c12;
    }
    
    .data-section {
        background: white;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 30px;
    }
    
    .section-header {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .section-header h4 {
        margin: 0 0 5px 0;
        color: #333;
        font-size: 18px;
    }
    
    .section-header p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    
    .efficiency-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    
    .efficiency-badge.high {
        background: #eafaf1;
        color: #27ae60;
    }
    
    .efficiency-badge.medium {
        background: #fef5e7;
        color: #f39c12;
    }
    
    .efficiency-badge.low {
        background: #fdedec;
        color: #e74c3c;
    }
    
    .efficiency-badge.none {
        background: #f8f9fa;
        color: #95a5a6;
    }
    
    @media (max-width: 1200px) {
        .summary-stats, .amount-stats {
            grid-template-columns: 1fr;
        }
        
        .charts-section {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .stats-container {
            padding: 20px;
        }
        
        .stats-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [{{ $statusData['pending'] }}, {{ $statusData['approved'] }}, {{ $statusData['rejected'] }}],
                    backgroundColor: ['#f39c12', '#27ae60', '#e74c3c'],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ $stats['total_requests'] }};
                                const value = context.raw;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Monthly Distribution Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        
        // Prepare monthly data
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const requestedData = [];
        const approvedData = [];
        
        // Initialize arrays with zeros
        for (let i = 1; i <= 12; i++) {
            const monthData = @json($monthlyData);
            const found = monthData.find(item => item.month === i);
            requestedData.push(found ? found.total_requested : 0);
            approvedData.push(found ? found.total_approved : 0);
        }

        const monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Requested (ML)',
                        data: requestedData,
                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Approved (ML)',
                        data: approvedData,
                        backgroundColor: 'rgba(46, 204, 113, 0.7)',
                        borderColor: 'rgba(46, 204, 113, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Milliliters (ML)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    });
</script>
@endsection