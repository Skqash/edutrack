@extends('layouts.teacher')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2>Request Center</h2>
                        <p class="text-muted mb-0">Manage all your school connection, subject, and course requests.</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('teacher.school-request.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-school me-1"></i> School Connection Request
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#courseRequestModal">
                            <i class="fas fa-book me-1"></i> Course Request
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#subjectRequestModal">
                            <i class="fas fa-book-reader me-1"></i> Subject Request
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#classRequestModal">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Class Request
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Pending Requests</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Details</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($requests as $request)
                                        <tr>
                                            <td>{{ ucfirst($request->request_type) }}</td>
                                            <td>{{ $request->related_name ?? $request->school_name }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($request->note, 80) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('admin.school-requests.show', $request) }}"
                                                    class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No requests found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($requests->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $requests->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Request Modal -->
    <div class="modal fade" id="subjectRequestModal" tabindex="-1" aria-labelledby="subjectRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('teacher.subjects.request') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="subjectRequestModalLabel">Subject Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Subject Name</label>
                            <input type="text" name="subject_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Code</label>
                            <input type="text" name="subject_code" class="form-control" placeholder="Optional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Subject Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Course Request Modal -->
    <div class="modal fade" id="courseRequestModal" tabindex="-1" aria-labelledby="courseRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('teacher.courses.request') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="courseRequestModalLabel">Course Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" name="course_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Course Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Class Request Modal -->
    <div class="modal fade" id="classRequestModal" tabindex="-1" aria-labelledby="classRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('teacher.classes.request') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="classRequestModalLabel">Class Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Class Name</label>
                            <input type="text" name="class_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course</label>
                            <x-searchable-dropdown
                                name="course_id"
                                id="course_id"
                                placeholder="Search and select course..."
                                api-url="{{ route('api.courses') }}"
                                required="true"
                                class="form-select"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Section</label>
                            <input type="text" name="section" class="form-control" value="A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year Level</label>
                            <input type="number" name="year_level" class="form-control" min="1" max="4"
                                value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Class Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
