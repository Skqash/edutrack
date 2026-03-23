@extends('layouts.teacher')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Request School Connection</h4>
                        <small class="text-white-75">Send a request to admin to connect your institution.</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('teacher.requests') }}" class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-inbox me-1"></i> Request Center
                            </a>
                            <a href="{{ route('teacher.subjects') }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-book-reader me-1"></i> My Subjects
                            </a>
                            <a href="{{ route('teacher.classes') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-door-open me-1"></i> My Classes
                            </a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($existing)
                            <div class="alert alert-info">
                                <h5 class="mb-2">Existing Request</h5>
                                <p class="mb-1"><strong>School:</strong> {{ $existing->school_name }}</p>
                                <p class="mb-1"><strong>Status:</strong>
                                    <span
                                        class="badge bg-{{ $existing->status === 'approved' ? 'success' : ($existing->status === 'rejected' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($existing->status) }}
                                    </span>
                                </p>
                                @if ($existing->admin_note)
                                    <p class="mb-1"><strong>Admin Note:</strong> {{ $existing->admin_note }}</p>
                                @endif
                                <p class="mb-0"><small class="text-muted">Submitted
                                        {{ $existing->created_at->diffForHumans() }}</small></p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('teacher.school-request.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">School / Institution Name <span
                                        class="text-danger">*</span></label>
                                <input name="school_name" value="{{ old('school_name') }}"
                                    class="form-control @error('school_name') is-invalid @enderror" required>
                                @error('school_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Email</label>
                                    <input type="email" name="school_email" value="{{ old('school_email') }}"
                                        class="form-control @error('school_email') is-invalid @enderror">
                                    @error('school_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">School Phone</label>
                                    <input type="tel" name="school_phone" value="{{ old('school_phone') }}"
                                        class="form-control @error('school_phone') is-invalid @enderror">
                                    @error('school_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">School Address</label>
                                <input name="school_address" value="{{ old('school_address') }}"
                                    class="form-control @error('school_address') is-invalid @enderror">
                                @error('school_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Additional Notes (optional)</label>
                                <textarea name="note" rows="3" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
