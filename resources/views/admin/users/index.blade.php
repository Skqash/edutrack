@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-purple text-white me-2">
                    <i class="fas fa-users"></i>
                </span>
                User Management (Teachers & Students)
            </h3>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.users.create') }}" class="btn btn-gradient-success">
                <i class="fas fa-plus"></i> Add User
            </a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <!-- Desktop View -->
        <div class="card shadow-sm d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ 
                                        $user->role == 'teacher' ? 'bg-primary' : 'bg-info'
                                    }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons justify-content-center">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-action btn-edit btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-delete btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Mobile View -->
        <div class="d-md-none">
            @forelse ($users as $user)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title mb-2">{{ $user->name }}</h5>
                                <p class="mb-2 text-muted">
                                    <strong>Email:</strong> {{ $user->email }}
                                </p>
                                <p class="mb-2">
                                    <strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}
                                </p>
                                <p class="mb-3">
                                    <strong>Role:</strong>
                                    <span class="badge {{ 
                                        $user->role == 'teacher' ? 'bg-primary' : 'bg-info'
                                    }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-action btn-edit w-100 mb-2">
                                <i class="fas fa-edit"></i> Edit User
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete w-100">
                                    <i class="fas fa-trash"></i> Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle"></i> No users found
                </div>
            @endforelse

            <!-- Mobile Pagination -->
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-success {
        background: linear-gradient(45deg, #27ae60, #2ecc71);
        color: white;
        border: none;
    }

    .btn-gradient-success:hover {
        background: linear-gradient(45deg, #229954, #27ae60);
        color: white;
    }

    .page-title-icon {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(45deg, #6f42c1, #9966ff) !important;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }

    .btn-edit {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .btn-edit:hover {
        background-color: #0056b3;
        color: white;
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
        border: none;
    }

    .btn-delete:hover {
        background-color: #c82333;
        color: white;
    }

    @media (max-width: 768px) {
        .card-title {
            font-size: 16px !important;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
    }
</style>
@endsection
