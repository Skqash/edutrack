<?php $__env->startSection('content'); ?>
    <div class="container mx-auto p-6">
        <div class="mb-6">
            <a href="<?php echo e(route('super.dashboard')); ?>" class="text-blue-600 hover:underline">&larr; Back to Dashboard</a>
            <h1 class="text-3xl font-bold mt-2">System Logs</h1>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Recent Log Entries</h2>
                <form action="<?php echo e(route('super.logs.view')); ?>" method="GET" class="flex gap-2">
                    <input type="number" name="lines" value="200" min="50" max="1000"
                        class="border px-2 py-1 rounded" placeholder="Number of lines">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">Load</button>
                </form>
            </div>

            <div class="bg-gray-900 text-gray-100 p-4 rounded font-mono text-sm overflow-x-auto"
                style="max-height: 600px; overflow-y: auto;">
                <?php $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(trim($line)): ?>
                        <div class="whitespace-pre-wrap break-words"><?php echo e($line); ?></div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\super\tools\logs.blade.php ENDPATH**/ ?>