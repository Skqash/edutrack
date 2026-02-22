@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3"><i class="fas fa-database"></i> Database Cleanup</h2>
            <a href="{{ route('super.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Dangerous Operation</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning!</strong> This action will delete large amounts of data from the database. Make
                            sure to backup your database first.
                        </div>

                        <h5 class="mt-4">Available Cleanup Operations:</h5>

                        <div class="list-group mt-3">
                            <!-- Delete Old Logs -->
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Clear Old Logs</h6>
                                        <small class="text-muted">Delete logs older than 90 days</small>
                                    </div>
                                    <form action="{{ route('super.cleanup.logs') }}" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete all logs older than 90 days?')">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Clear Logs</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Clear Cache -->
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Clear All Caches</h6>
                                        <small class="text-muted">Remove all application cache files</small>
                                    </div>
                                    <form action="{{ route('super.cleanup.cache') }}" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Clear all caches?')">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Clear Cache</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Delete Inactive Users -->
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Delete Inactive Users</h6>
                                        <small class="text-muted">Remove users with no login activity for 6 months</small>
                                    </div>
                                    <form action="{{ route('super.cleanup.inactive-users') }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Delete all inactive users (6+ months)?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Delete Users</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Delete Old Submissions -->
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Archive Old Submissions</h6>
                                        <small class="text-muted">Archive assignment submissions older than 1 year</small>
                                    </div>
                                    <form action="{{ route('super.cleanup.old-submissions') }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Archive submissions older than 1 year?')">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Archive</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Delete Orphaned Records -->
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Delete Orphaned Records</h6>
                                        <small class="text-muted">Remove records with missing foreign key references</small>
                                    </div>
                                    <form action="{{ route('super.cleanup.orphaned') }}" method="POST"
                                        style="display:inline;" onsubmit="return confirm('Delete all orphaned records?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Delete Orphaned</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Reset All Data -->
                            <div class="list-group-item border-danger">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 text-danger">🔴 Reset Entire Database</h6>
                                        <small class="text-muted">Delete ALL data and reseed from scratch</small>
                                    </div>
                                    <form action="{{ route('super.cleanup.reset-all') }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('RESET ALL DATA? This CANNOT be undone!\\nType YES to confirm:') && prompt('Type YES to confirm:') === 'YES'">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Reset All</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Database Stats -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Database Statistics</h5>
                    </div>
                    <div class="card-body">
                        @if (isset($stats))
                            <div class="row g-3">
                                @foreach ($stats as $table => $count)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-muted">
                                                    {{ ucfirst(str_replace('_', ' ', $table)) }}</h6>
                                                <h3 class="card-text text-primary">{{ $count }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Database statistics not available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
