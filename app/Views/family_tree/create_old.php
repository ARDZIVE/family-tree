<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container py-4">
        <div class="card shadow-lg">
            <?= form_open('family-tree/store', ['id' => 'familyMemberForm']) ?>
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Family Member</h5>
                <div>
                    <a href="<?= base_url('family-tree') ?>" class="btn btn-light btn-sm ms-2">
                        <i class="bi bi-arrow-left-square"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="bi bi-floppy"></i> Save
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control <?= validation_show_error('name') ? 'is-invalid' : '' ?>"
                               id="name"
                               name="name"
                               value="<?= old('name') ?>"
                               required
                                autofocus>
                        <?php if(validation_show_error('name')): ?>
                            <div class="invalid-feedback">
                                <?= validation_show_error('name') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="birth_year" class="form-label">Birth Year</label>
                        <input type="number"
                               class="form-control <?= validation_show_error('birth_year') ? 'is-invalid' : '' ?>"
                               id="birth_year"
                               name="birth_year"
                               value="<?= old('birth_year') ?>"
                               max="<?= date('Y') ?>">
                        <?php if(validation_show_error('birth_year')): ?>
                            <div class="invalid-feedback">
                                <?= validation_show_error('birth_year') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                    <div class="mb-3">
                        <label for="parent_name" class="form-label">Parent</label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="parent_name"
                                   readonly
                                   placeholder="Select a parent..."
                                   value="<?= old('parent_name') ?>">
                            <input type="hidden"
                                   name="parent_id"
                                   id="parent_id"
                                   value="<?= old('parent_id') ?>">
                            <button class="btn btn-outline-secondary"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#parentSelectModal">
                                <i class="bi bi-diagram-2"></i> Select Parent
                            </button>
                            <button class="btn btn-outline-danger"
                                    type="button"
                                    id="clearParent">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>


            </div>
            <?= form_close() ?>
        </div>
    </div>

    <!-- Parent Selection Modal -->
    <div class="modal fade" id="parentSelectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Parent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text"
                               class="form-control"
                               id="treeSearch"
                               placeholder="Search family members..."
                               autofocus>
                    </div>
                    <div id="familyTreeView" class="overflow-auto" style="max-height: 400px;">
                        <!-- Tree will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>



<?= $this->section('scripts') ?>
    <script>
        $(document).ready(function() {
            // Load family tree when modal opens
            $('#parentSelectModal').on('show.bs.modal', function () {
                loadFamilyTree();
            });

            // Clear parent selection
            $('#clearParent').click(function() {
                $('#parent_id').val('');
                $('#parent_name').val('');
            });

            // Search functionality
            $('#treeSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('#familyTreeView .tree-item').each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(searchTerm));
                });
            });

            // Proper modal cleanup on hide
            $('#parentSelectModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                document.body.style.overflow = 'auto';
                document.body.style.paddingRight = '';
            });
        });

        function loadFamilyTree() {
            $('#familyTreeView').html('<div class="text-center"><i class="bi bi-hourglass-split"></i> Loading...</div>');

            $.ajax({
                url: '<?= base_url('family-tree/get-tree') ?>',
                method: 'GET',
                dataType: 'html',
                success: function(response) {
                    if (response) {
                        $('#familyTreeView').html(response);
                        initializeTreeBehavior();
                    } else {
                        $('#familyTreeView').html('<div class="alert alert-info">No family members found.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#familyTreeView').html(
                        '<div class="alert alert-danger">' +
                        'Error loading family tree. Please try again.' +
                        '<br><small>Error details: ' + error + '</small>' +
                        '</div>'
                    );
                }
            });
        }

        function initializeTreeBehavior() {
            // Toggle child items
            $('.tree-toggle').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const $parentLi = $(this).closest('li');
                $parentLi.children('ul').toggle(300);
                $(this).find('.bi').toggleClass('bi-chevron-right bi-chevron-down');
            });

            // Select parent
            $('.select-parent').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#parent_id').val(id);
                $('#parent_name').val(name);

                // Properly close the modal
                const modal = bootstrap.Modal.getInstance($('#parentSelectModal'));
                if (modal) {
                    modal.hide();
                }
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                document.body.style.overflow = 'auto';
                document.body.style.paddingRight = '';
            });
        }
        $(document).ready(function() {
            // Load family tree when modal opens
            $('#parentSelectModal').on('show.bs.modal', function () {
                loadFamilyTree();
            });

            // Clear parent selection
            $('#clearParent').click(function() {
                $('#parent_id').val('');
                $('#parent_name').val('');
            });

            // Search functionality
            $('#treeSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('#familyTreeView .tree-item').each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(searchTerm));
                });
            });
        });
    </script>

    <style>
        .tree-item {
            list-style-type: none;
            margin: 5px 0;
        }

        .tree-toggle {
            cursor: pointer;
            color: #0d6efd;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .tree-toggle:hover {
            background-color: #e9ecef;
        }

        .tree-content {
            padding: 8px;
            margin: 2px 0;
            border-radius: 4px;
            border: 1px solid transparent;
        }

        .tree-content:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .select-parent {
            color: #0d6efd;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .select-parent:hover {
            background-color: #e9ecef;
            text-decoration: none;
        }

        #familyTreeView {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        #familyTreeView ul {
            margin-bottom: 0;
        }
    </style>
<?= $this->endSection() ?>
<?= $this->endSection() ?>