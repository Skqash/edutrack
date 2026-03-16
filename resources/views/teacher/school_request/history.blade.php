@extends('layouts.teacher')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h3 class="mb-0">Your School Connection Requests</h3>
                <p class="text-muted">Review all connection requests you've submitted and their current status.</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('teacher.school-request.create') }}" class="btn btn-secondary">
                    <i class="fas fa-plus me-2"></i> New Request
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($requests->isEmpty())
                    <div class="alert alert-info">You have not submitted any school connection requests yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Admin Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                    <tr>
                                        <td>{{ $request->school_name }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->diffForHumans() }}</td>
                                        <td>{{ $request->admin_note ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
