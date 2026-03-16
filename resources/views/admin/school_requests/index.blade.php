@extends('layouts.admin')

@section('content')
    <style>
        /* Modern Page Header */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
            color: white;
        }

        .page-header-modern .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header-modern .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header-modern .header-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .page-header-modern .header-title {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .page-header-modern .header-subtitle {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-modern .card-body {
            padding: 2rem;
        }

        .table-modern {
            margin-bottom: 0;
        }

        .table-modern thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
        }

        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge-modern {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .empty-state h5 {
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1.5rem;
            }

            .page-header-modern .header-title {
                font-size: 1.5rem;
            }

            .page-header-modern .header-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <div>
                        <h1 class="header-title">School Connection Requests</h1>
                        <p class="header-subtitle">Review and manage teacher school connection requests</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-modern">
            <div class="card-body">
                @if ($requests->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h5>No Connection Requests</h5>
                        <p>No school connection requests have been submitted yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user me-2"></i>Teacher</th>
                                    <th><i class="fas fa-school me-2"></i>School</th>
                                    <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                    <th><i class="fas fa-calendar me-2"></i>Submitted</th>
                                    <th class="text-end"><i class="fas fa-cog me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $request->user->name }}</strong>
                                                <br><small class="text-muted">{{ $request->user->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $request->school_name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-modern bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $request->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.school-requests.show', $request) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($requests->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $requests->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
