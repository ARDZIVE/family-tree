<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container py-4">
        <?= view('family_tree/flashdata'); ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Family Members</h5>
                <div>
                    <a href="<?= base_url('family-tree/create') ?>" class="btn btn-light btn-sm ms-2">
                        <i class="bi bi-plus-circle me-2"></i>Add
                    </a>
                    <a href="<?= base_url('family-tree/chart') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-diagram-3-fill me-2"></i>Chart
                    </a>
                </div>
            </div>
            <div class="card-body">

                    <table id="familyMembersTable" class="table table-striped table-hover table-responsive">
                        <thead class="table-secondary">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="auto">Name</th>
                            <th width="200px">Date</th>
                            <th width="30%">Parent</th>
                            <th width="100px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($familyMembers as $member): ?>
                            <tr>
                                <td><?= $member['id'] ?></td>
                                <td><?= $member['name'] ?></td>
                                <td><?php
                                    if (!empty($member['birth_year']) && !empty($member['dead_year'])) {
                                        echo $member['birth_year'] . ' - ' . $member['dead_year'];
                                    } else if (!empty($member['birth_year'])) {
                                        echo $member['birth_year'] . ' - ...';
                                    } else if (!empty($member['dead_year'])) {
                                        echo '... - ' . $member['dead_year'];
                                    } else {
                                        echo '&nbsp;';
                                    }
                                    ?></td>
                                <td>
                                    <?php
                                    $parentName = '';
                                    foreach($familyMembers as $potentialParent) {
                                        if ($potentialParent['id'] == $member['parent_id']) {
                                            $parentName = $potentialParent['name'];
                                            break;
                                        }
                                    }
                                    echo $parentName ?: 'No Parent';
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url("family-tree/view/{$member['id']}") ?>"
                                           class="btn btn-sm btn-light-outline">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url("family-tree/edit/{$member['id']}") ?>"
                                           class="btn btn-sm btn-light-outline">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button onclick="showDeleteModal(<?= $member['id'] ?>)"
                                                class="btn btn-sm btn-light-outline">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
<!--                <div class="modal-header bg-danger text-white pt-reduced pb-reduced h-50" >-->
<!--                    <h6 class="modal-title">Confirm Deletion</h6>-->
<!--                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>-->
<!--                </div>-->
                <div class="modal-body">
                    Are you sure you want to delete this family member?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="deleteLink" class="btn btn-danger btn-sm">Delete</a>
                </div>
            </div>
        </div>
    </div>


<?= $this->section('scripts') ?>


    <script>
        $(document).ready(function() {
            $('#familyMembersTable').DataTable({
                responsive: true,
                stateSave: true,
                pageLength: 10,
                order: [[0, 'asc']],
                language: {
                    search: '_INPUT_',
                    searchPlaceholder: 'Search family members...',
                    lengthMenu: '_MENU_ records per page',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'Showing 0 to 0 of 0 entries',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    zeroRecords: 'No matching records found',
                    paginate:{
                        previous: '‹',
                        next: '›'
                    }
                },
                columnDefs: [
                    {
                        targets: -1,
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        function confirmDelete(id) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteLink = document.getElementById('deleteLink');
            deleteLink.href = '<?= base_url("family-tree/delete") ?>/' + id;
            deleteModal.show();
        }

        function showDeleteModal(id) {
            const deleteModal = new bootstrap.Modal('#deleteModal');
            const deleteLink = document.getElementById('deleteLink');

            deleteLink.onclick = function(e) {
                e.preventDefault();

                $.ajax({
                    url: `<?= base_url('family-tree/delete') ?>/${id}`,
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        deleteModal.hide();
                        if (response.success) {
                            window.location.href = '<?= base_url('family-tree') ?>';
                        } else {
                            window.location.reload();
                        }
                    },
                    error: function() {
                        deleteModal.hide();
                        window.location.reload();
                    }
                });
            };

            deleteModal.show();
        }

        function deleteMember(id) {
            if (confirm('Are you sure you want to delete this member?')) {
                $.ajax({
                    url: `<?= base_url('family-tree/delete') ?>/${id}`,
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error occurred during deletion');
                    }
                });
            }
        }
    </script>



<?= $this->endSection() ?>
<?= $this->endSection() ?>