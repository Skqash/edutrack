@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3"><i class="fas fa-heartbeat"></i> System Health Check</h2>
            <a href="{{ route('super.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close"
                    data-bs-dismiss="alert"></button></div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close"
                    data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row">
            <!-- Database Health -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-database"></i> Database</h5>
                        <span class="badge bg-{{ $health['database']['status'] === 'healthy' ? 'success' : 'danger' }}">
                            {{ ucfirst($health['database']['status']) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td><strong>Connection:</strong></td>
                                <td>{{ $health['database']['connected'] ? '✓ Connected' : '✗ Failed' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Database:</strong></td>
                                <td>{{ $health['database']['name'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tables:</strong></td>
                                <td>{{ $health['database']['table_count'] }} tables</td>
                            </tr>
                            <tr>
                                <td><strong>Total Records:</strong></td>
                                <td>{{ $health['database']['total_records'] }} records</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Storage Health -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-folder"></i> Storage</h5>
                        <span class="badge bg-{{ $health['storage']['status'] === 'healthy' ? 'success' : 'warning' }}">
                            {{ ucfirst($health['storage']['status']) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td><strong>Writable:</strong></td>
                                <td>{{ $health['storage']['writable'] ? '✓ Yes' : '✗ No' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Space:</strong></td>
                                <td>{{ $health['storage']['total_space'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Free Space:</strong></td>
                                <td>{{ $health['storage']['free_space'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Usage:</strong></td>
                                <td>{{ $health['storage']['usage_percent'] }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cache Health -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-bolt"></i> Cache</h5>
                        <span class="badge bg-{{ $health['cache']['status'] === 'healthy' ? 'success' : 'warning' }}">
                            {{ ucfirst($health['cache']['status']) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td><strong>Driver:</strong></td>
                                <td>{{ ucfirst($health['cache']['driver']) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Accessible:</strong></td>
                                <td>{{ $health['cache']['accessible'] ? '✓ Yes' : '✗ No' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Size:</strong></td>
                                <td>{{ $health['cache']['size'] ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Log Health -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-file-alt"></i> Logs</h5>
                        <span class="badge bg-{{ $health['logs']['status'] === 'healthy' ? 'success' : 'warning' }}">
                            {{ ucfirst($health['logs']['status']) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td><strong>Log File:</strong></td>
                                <td>{{ $health['logs']['file_exists'] ? '✓ Exists' : '✗ Missing' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Size:</strong></td>
                                <td>{{ $health['logs']['size'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Writable:</strong></td>
                                <td>{{ $health['logs']['writable'] ? '✓ Yes' : '✗ No' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Recent Errors:</strong></td>
                                <td><span
                                        class="badge bg-{{ $health['logs']['error_count'] > 0 ? 'warning' : 'success' }}">{{ $health['logs']['error_count'] }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Overall Health -->
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-chart-pie"></i> System Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <h6>Overall Status</h6>
                                    <h4 class="text-{{ $health['overall'] === 'Healthy' ? 'success' : 'warning' }}">
                                        {{ $health['overall'] }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <h6>Last Check</h6>
                                    <p>{{ now()->format('M d, Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <h6>Uptime</h6>
                                    <p>✓ Running</p>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <form action="{{ route('super.health.refresh') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-sync"></i> Refresh Check
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        @if ($health['recommendations'])
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Recommendations</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @foreach ($health['recommendations'] as $recommendation)
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-warning"></i>
                                        {{ $recommendation }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
