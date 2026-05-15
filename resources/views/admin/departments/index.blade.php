<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Departments</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.departments.create')); ?>" class="btn btn-primary">
                            Add New Department
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>
                        </div>
                    <?php endif; ?>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($department->id); ?></td>
                                    <td><?php echo e($department->name); ?></td>
                                    <td><?php echo e($department->description); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo e($department->status === 'active' ? 'success' : 'danger'); ?>">
                                            <?php echo e($department->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin.departments.show', $department->id)); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.departments.edit', $department->id)); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.departments.destroy', $department->id)); ?>" method="POST" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>