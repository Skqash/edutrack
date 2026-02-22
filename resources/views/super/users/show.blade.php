@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="mb-6">
            <a href="{{ route('super.users.index') }}" class="text-blue-600 hover:underline">&larr; Back to Users</a>
            <h1 class="text-3xl font-bold mt-2">User Details</h1>
        </div>

        @if ($message = session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">{{ $message }}</div>
        @endif
        @if ($message = session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">{{ $message }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- User Info Card -->
            <div class="col-span-2 bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-bold mb-4">{{ $user->name }}</h2>
                <div class="space-y-3">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p><strong>Role:</strong>
                        <span
                            class="px-2 py-1 rounded text-sm font-bold 
                        @if ($user->role === 'superadmin') bg-red-200 text-red-800
                        @elseif($user->role === 'admin') bg-blue-200 text-blue-800
                        @elseif($user->role === 'teacher') bg-green-200 text-green-800
                        @else bg-gray-200 text-gray-800 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>
                    <p><strong>Status:</strong>
                        <span
                            class="px-2 py-1 rounded 
                        @if ($user->status === 'Active') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                            {{ $user->status ?? 'Active' }}
                        </span>
                    </p>
                    <p><strong>Email Verified:</strong> {{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                    <p><strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-gray-50 p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">Actions</h3>
                <button onclick="document.getElementById('editModal').style.display='block'"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-2">Edit User</button>
                <button onclick="document.getElementById('roleModal').style.display='block'"
                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mb-2">Change Role</button>
                <button onclick="document.getElementById('passwordModal').style.display='block'"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded mb-2">Reset
                    Password</button>
                <form action="{{ route('super.users.destroy', $user->id) }}" method="POST"
                    onsubmit="return confirm('Delete this user permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Delete
                        User</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg max-w-md w-full mx-4 max-h-96 overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Edit User</h2>
            <form action="{{ route('super.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block font-bold mb-2">Name</label>
                    <input type="text" name="name" value="{{ $user->name }}"
                        class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('editModal').style.display='none'"
                        class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Role Modal -->
    <div id="roleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg max-w-md w-full mx-4">
            <h2 class="text-xl font-bold mb-4">Change User Role</h2>
            <form action="{{ route('super.users.toggle-role', $user->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold mb-2">New Role</label>
                    <select name="role" class="w-full border px-3 py-2 rounded" required>
                        <option value="">-- Select Role --</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('roleModal').style.display='none'"
                        class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Change Role</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="passwordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg max-w-md w-full mx-4">
            <h2 class="text-xl font-bold mb-4">Reset User Password</h2>
            <p class="text-gray-600 mb-4">A temporary password will be set. The user should change it after login.</p>
            <form action="{{ route('super.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block font-bold mb-2">New Temporary Password</label>
                    <input type="password" name="password" class="w-full border px-3 py-2 rounded" value="TempPass@123"
                        required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('passwordModal').style.display='none'"
                        class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded">Set Password</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .hidden {
            display: none !important;
        }
    </style>
@endsection
