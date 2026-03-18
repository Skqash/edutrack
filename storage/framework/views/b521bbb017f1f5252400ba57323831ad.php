

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto p-6">
        <div class="mb-6">
            <a href="<?php echo e(route('super.dashboard')); ?>" class="text-blue-600 hover:underline">&larr; Back to Dashboard</a>
            <h1 class="text-3xl font-bold mt-2">Database Query Runner</h1>
            <p class="text-gray-600 mt-2">⚠️ <strong>READ-ONLY:</strong> Only SELECT queries are allowed for security.</p>
        </div>

        <?php if($message = session('error')): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded"><?php echo e($message); ?></div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded shadow mb-6">
            <form action="<?php echo e(route('super.tools.query.run')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <label class="block font-bold mb-2">SQL Query (SELECT only)</label>
                <textarea name="sql" class="w-full border px-3 py-2 rounded font-mono mb-4" rows="6"
                    placeholder="SELECT * FROM users WHERE role = 'teacher';" required><?php echo e($sql ?? ''); ?></textarea>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-bold">Run
                    Query</button>
            </form>
        </div>

        <?php if(isset($results)): ?>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Results (<?php echo e(count($results)); ?> rows)</h2>

                <?php if(count($results) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-sm">
                            <thead class="bg-gray-200 border">
                                <tr>
                                    <?php $__currentLoopData = (array) $results[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="p-2 border text-left"><?php echo e($key); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border hover:bg-gray-50">
                                        <?php $__currentLoopData = (array) $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td class="p-2 border"><?php echo e($value ?? 'NULL'); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600">No results found.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/super/tools/query.blade.php ENDPATH**/ ?>