<?= view('templates/header', ['title' => $title]) ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">System Activity Logs</h1>
    <div>
        <a href="<?= base_url('admin/logs/clear') ?>" class="btn btn-danger"
            onclick="return confirm('Are you sure you want to clear all logs? This action cannot be undone.')">
            <i class="fas fa-trash"></i> Clear All Logs
        </a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs) && is_array($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= $log['id'] ?></td>
                                <td><?= $log['timestamp'] ?></td>
                                <td>
                                    <?php if ($log['username']): ?>
                                        <span class="fw-bold"><?= esc($log['username']) ?></span>
                                        <small class="text-muted d-block"><?= esc($log['full_name']) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">System/Guest</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($log['action']) ?></td>
                                <td><?= esc($log['details']) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/logs/delete/' . $log['id']) ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this log entry?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>