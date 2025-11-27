<!-- app/Views/backup_view.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Database Backup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1>Database Backup</h1>
            <button class="btn btn-primary" onclick="createBackup()">Create New Backup</button>
        </div>
    </div>

    <div id="message"></div>

    <?php if (empty($files)): ?>
        <div class="alert alert-info text-center">No backup files found</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Filename</th>
                    <th>Date</th>
                    <th>Size</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($files as $file): ?>
                    <?php
                    $filePath = WRITEPATH . 'backups/' . $file;
                    $fileSize = filesize($filePath);
                    $fileDate = date('Y-m-d H:i:s', filemtime($filePath));

                    // Format file size
                    if ($fileSize >= 1048576) {
                        $formattedSize = round($fileSize / 1048576, 2) . ' MB';
                    } elseif ($fileSize >= 1024) {
                        $formattedSize = round($fileSize / 1024, 2) . ' KB';
                    } else {
                        $formattedSize = $fileSize . ' bytes';
                    }
                    ?>
                    <tr>
                        <td><?= esc($file) ?></td>
                        <td><?= $fileDate ?></td>
                        <td class="text-muted"><?= $formattedSize ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('database-backup/download/' . $file) ?>"
                                   class="btn btn-sm btn-outline-primary">Download</a>
                                <button onclick="deleteBackup('<?= $file ?>')"
                                        class="btn btn-sm btn-outline-danger">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showMessage(type, text) {
        const messageDiv = document.getElementById('message');
        messageDiv.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${text}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
    }

    function createBackup() {
        fetch('<?= base_url('database-backup/backup') ?>', {
            method: 'POST'
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showMessage('success', data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage('danger', data.message);
                }
            })
            .catch(error => {
                showMessage('danger', 'An error occurred while creating the backup');
            });
    }

    function deleteBackup(filename) {
        if (confirm('Are you sure you want to delete this backup?')) {
            fetch('<?= base_url('database-backup/delete') ?>/' + filename, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showMessage('success', data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showMessage('danger', data.message);
                    }
                })
                .catch(error => {
                    showMessage('danger', 'An error occurred while deleting the backup');
                });
        }
    }
</script>
</body>
</html>