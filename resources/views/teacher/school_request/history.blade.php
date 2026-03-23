@extends('layouts.teacher')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h3 class="mb-0">Your Requests</h3>
                <p class="text-muted">Review all school connection, subject, course, and class requests you've submitted.</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('teacher.requests') }}" class="btn btn-secondary">
                    <i class="fas fa-inbox me-2"></i> Request Center
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
                                    <th>Type</th>
                                    <th>Target</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Admin Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                    <tr>
                                        <td>{{ ucfirst($request->request_type ?? 'school') }}</td>
                                        <td>{{ $request->related_name ?? $request->school_name }}</td>
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
