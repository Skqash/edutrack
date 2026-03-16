<?php $__env->startSection('content'); ?>
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">User Management</h1>
            <div>
                <a href="<?php echo e(route('super.users.export')); ?>"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">📥 Export CSV</a>
                <button onclick="document.getElementById('importModal').style.display='block'"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded ml-2">📤 Import CSV</button>
            </div>
        </div>

        <?php if($message = session('success')): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded"><?php echo e($message); ?></div>
        <?php endif; ?>
        <?php if($message = session('error')): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded"><?php echo e($message); ?></div>
        <?php endif; ?>

        <!-- Filter by Role -->
        <form method="GET" class="mb-6">
            <select name="role" class="border px-4 py-2 rounded" onchange="this.form.submit()">
                <option value="">All Roles</option>
                <option value="superadmin" <?php if(request('role') === 'superadmin'): echo 'selected'; endif; ?>>Super Admin</option>
                <option value="admin" <?php if(request('role') === 'admin'): echo 'selected'; endif; ?>>Admin</option>
                <option value="teacher" <?php if(request('role') === 'teacher'): echo 'selected'; endif; ?>>Teacher</option>
                <option value="student" <?php if(request('role') === 'student'): echo 'selected'; endif; ?>>Student</option>
            </select>
        </form>

        <!-- Users Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full border-collapse">
                <thead class="bg-gray-200 border-b">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Created</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3"><?php echo e($user->id); ?></td>
                            <td class="p-3"><?php echo e($user->name); ?></td>
                            <td class="p-3"><?php echo e($user->email); ?></td>
                            <td class="p-3">
                                <span
                                    class="px-2 py-1 rounded text-sm font-bold 
                                <?php if($user->role === 'superadmin'): ?> bg-red-200 text-red-800
                                <?php elseif($user->role === 'admin'): ?> bg-blue-200 text-blue-800
                                <?php elseif($user->role === 'teacher'): ?> bg-green-200 text-green-800
                                <?php else: ?> bg-gray-200 text-gray-800 <?php endif; ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                            <td class="p-3">
                                <span
                                    class="px-2 py-1 rounded text-sm 
                                <?php if($user->status === 'Active'): ?> bg-green-100 text-green-800
                                <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                    <?php echo e($user->status ?? 'Active'); ?>

                                </span>
                            </td>
                            <td class="p-3"><?php echo e($user->created_at->format('M d, Y')); ?></td>
                            <td class="p-3">
                                <a href="<?php echo e(route('super.users.show', $user->id)); ?>"
                                    class="text-blue-600 hover:underline">View</a> |
                                <form action="<?php echo e(route('super.users.destroy', $user->id)); ?>" method="POST"
                                    style="display:inline;" onsubmit="return confirm('Delete this user?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($users->links()); ?>

        </div>
    </div>

    <!-- Import CSV Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg max-w-md w-full mx-4">
            <h2 class="text-xl font-bold mb-4">Import Users from CSV</h2>
            <form action="<?php echo e(route('super.users.import')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="file" name="csv" required accept=".csv"
                    class="block w-full border px-3 py-2 rounded mb-4">
                <p class="text-sm text-gray-600 mb-4">CSV format: name, email, role (superadmin|admin|teacher|student)</p>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('importModal').style.display='none'"
                        class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Import</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .hidden {
            display: none !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\super\users\index.blade.php ENDPATH**/ ?>