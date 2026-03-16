@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h3 class="mb-0">School Connection Request</h3>
                <p class="text-muted">Review and update the request status.</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.school-requests.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Requests
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h5>Teacher Information</h5>
                        <p class="mb-1"><strong>Name:</strong> {{ $schoolRequest->user->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $schoolRequest->user->email }}</p>
                        <p class="mb-1"><strong>Requested:</strong>
                            {{ $schoolRequest->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-lg-6">
                        <h5>Request Details</h5>
                        <p class="mb-1"><strong>School:</strong> {{ $schoolRequest->school_name }}</p>
                        @if ($schoolRequest->school_email)
                            <p class="mb-1"><strong>School Email:</strong> {{ $schoolRequest->school_email }}</p>
                        @endif
                        @if ($schoolRequest->school_phone)
                            <p class="mb-1"><strong>School Phone:</strong> {{ $schoolRequest->school_phone }}</p>
                        @endif
                        @if ($schoolRequest->school_address)
                            <p class="mb-1"><strong>School Address:</strong> {{ $schoolRequest->school_address }}</p>
                        @endif
                        @if ($schoolRequest->note)
                            <p class="mb-1"><strong>Teacher Message:</strong> {{ $schoolRequest->note }}</p>
                        @endif
                    </div>
                </div>

                <hr>

                <form action="{{ route('admin.school-requests.update', $schoolRequest) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $schoolRequest->status === 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="approved" {{ $schoolRequest->status === 'approved' ? 'selected' : '' }}>
                                    Approved</option>
                                <option value="rejected" {{ $schoolRequest->status === 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Assign Connected School</label>
                            @php
                                $campuses = [
                                    'Victorias Campus',
                                    'Main Campus',
                                    'Candoni Campus',
                                    'Cauayan Campus',
                                    'Hinigaran Campus',
                                    'Hinoba-an Campus',
                                    'Ilog Campus',
                                    'Moises Padilla Campus',
                                    'San Carlos Campus',
                                    'Sipalay Campus',
                                ];

                                $currentConnected =
                                    old('connected_school') ??
                                    ($schoolRequest->user->teacher->connected_school ??
                                        null ??
                                        $schoolRequest->school_name);
                            @endphp
                            <select name="connected_school" class="form-select">
                                <option value="">(Select campus or use requested school)</option>
                                @foreach ($campuses as $campus)
                                    <option value="{{ $campus }}"
                                        {{ $currentConnected === $campus ? 'selected' : '' }}>
                                        {{ $campus }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Set the connected school when approving the request.</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Admin Note (optional)</label>
                            <textarea name="admin_note" class="form-control" rows="2">{{ old('admin_note', $schoolRequest->admin_note) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </form>

                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
