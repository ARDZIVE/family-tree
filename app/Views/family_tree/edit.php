<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container py-4">
        <div class="card">
            <?= form_open('family-tree/update/' . $member['id'], ['id' => 'familyMemberForm']) ?>
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Family Member</h5>
                <div>
                    <a href="<?= base_url('family-tree') ?>" class="btn btn-light btn-sm ms-2">
                        <i class="bi bi-ban"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="bi bi-floppy"></i> Save
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?= view('family_tree/flashdata') ?>
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $member['id'] ?>">
                <!-- Hidden input to store current member ID for JS -->
                <input type="hidden" id="current_member_id" value="<?= $member['id'] ?>">

                <div class="row">
                    <!-- Name Field -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= validation_show_error('name') ? 'is-invalid' : '' ?>"
                               id="name" name="name" value="<?= old('name', $member['name']) ?>" required>
                        <?php if(validation_show_error('name')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('name') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Alias Field -->
                    <div class="col-md-6 mb-3">
                        <label for="alias" class="form-label">Alias</label>
                        <input type="text" class="form-control <?= validation_show_error('alias') ? 'is-invalid' : '' ?>"
                               id="alias" name="alias" value="<?= old('alias', $member['alias']) ?>">
                        <?php if(validation_show_error('alias')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('alias') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Gender Field -->
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select <?= validation_show_error('gender') ? 'is-invalid' : '' ?>"
                                id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="1" <?= old('gender', $member['gender']) == '1' ? 'selected' : '' ?>>Male</option>
                            <option value="2" <?= old('gender', $member['gender']) == '2' ? 'selected' : '' ?>>Female</option>
                        </select>
                        <?php if(validation_show_error('gender')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('gender') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Birth Year Field -->
                    <div class="col-md-6 mb-3">
                        <label for="birth_year" class="form-label">Birth Year</label>
                        <input type="number" class="form-control <?= validation_show_error('birth_year') ? 'is-invalid' : '' ?>"
                               id="birth_year" name="birth_year" value="<?= old('birth_year', $member['birth_year']) ?>"
                               max="<?= date('Y') ?>">
                        <?php if(validation_show_error('birth_year')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('birth_year') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Birth Place Field -->
                    <div class="col-md-6 mb-3">
                        <label for="birth_place" class="form-label">Birth Place</label>
                        <input type="text" class="form-control <?= validation_show_error('birth_place') ? 'is-invalid' : '' ?>"
                               id="birth_place" name="birth_place" value="<?= old('birth_place', $member['birth_place']) ?>">
                        <?php if(validation_show_error('birth_place')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('birth_place') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Dead Year Field -->
                    <div class="col-md-6 mb-3">
                        <label for="dead_year" class="form-label">Dead Year</label>
                        <input type="number" class="form-control <?= validation_show_error('dead_year') ? 'is-invalid' : '' ?>"
                               id="dead_year" name="dead_year" value="<?= old('dead_year', $member['dead_year']) ?>"
                               max="<?= date('Y') ?>">
                        <?php if(validation_show_error('dead_year')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('dead_year') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Dead Place Field -->
                    <div class="col-md-6 mb-3">
                        <label for="dead_place" class="form-label">Dead Place</label>
                        <input type="text" class="form-control <?= validation_show_error('dead_place') ? 'is-invalid' : '' ?>"
                               id="dead_place" name="dead_place" value="<?= old('dead_place', $member['dead_place']) ?>">
                        <?php if(validation_show_error('dead_place')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('dead_place') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Parent Field -->
                    <div class="col-md-6 mb-3">
                        <label for="parent_name" class="form-label">Parent</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="parent_name" readonly
                                   placeholder="Select a parent..." value="<?= old('parent_name', $parent_name) ?>">
                            <input type="hidden" name="parent_id" id="parent_id"
                                   value="<?= old('parent_id', $member['parent_id']) ?>">
                            <button class="btn btn-outline-secondary" type="button"
                                    data-bs-toggle="modal" data-bs-target="#parentSelectModal">
                                <i class="bi bi-diagram-2"></i> Select Parent
                            </button>
                            <button class="btn btn-outline-danger" type="button" id="clearParent">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Mother Field -->
                    <div class="col-md-6 mb-3">
                        <label for="mother" class="form-label">Mother</label>
                        <input type="text" class="form-control <?= validation_show_error('mother') ? 'is-invalid' : '' ?>"
                               id="mother" name="mother" value="<?= old('mother', $member['mother']) ?>">
                        <?php if(validation_show_error('mother')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('mother') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Spouse Field -->
                    <div class="col-md-6 mb-3">
                        <label for="spouse" class="form-label">Spouse</label>
                        <input type="text" class="form-control <?= validation_show_error('spouse') ? 'is-invalid' : '' ?>"
                               id="spouse" name="spouse" value="<?= old('spouse', $member['spouse']) ?>">
                        <?php if(validation_show_error('spouse')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('spouse') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Occupation Field -->
                    <div class="col-md-6 mb-3">
                        <label for="occupation" class="form-label">Occupation</label>
                        <input type="text" class="form-control <?= validation_show_error('occupation') ? 'is-invalid' : '' ?>"
                               id="occupation" name="occupation" value="<?= old('occupation', $member['occupation']) ?>">
                        <?php if(validation_show_error('occupation')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('occupation') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Order Field -->
                    <div class="col-md-6 mb-3">
                        <label for="m_order" class="form-label">Order</label>
                        <input type="number" class="form-control <?= validation_show_error('m_order') ? 'is-invalid' : '' ?>"
                               id="m_order" name="m_order" value="<?= old('m_order', $member['m_order']) ?>">
                        <?php if(validation_show_error('m_order')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('m_order') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Comments Field -->
                    <div class="col-12 mb-3">
                        <label for="comments" class="form-label">Comments</label>
                        <textarea class="form-control <?= validation_show_error('comments') ? 'is-invalid' : '' ?>"
                                  id="comments" name="comments" rows="3"><?= old('comments', $member['comments']) ?></textarea>
                        <?php if(validation_show_error('comments')): ?>
                            <div class="invalid-feedback"><?= validation_show_error('comments') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <!-- Parent Selection Modal -->
    <div class="modal fade" id="parentSelectModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
<!--                <div class="modal-header">-->
<!--                    <h5 class="modal-title">Select Parent</h5>-->
<!--                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>-->
<!--                </div>-->
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="treeSearch" placeholder="Search Parent..." autofocus>
                    </div>
                    <div id="familyTreeView" class="overflow-auto" style="max-height: 400px;">
                        <!-- Tree will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
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

        .tree-item.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .tree-item.disabled .select-parent {
            pointer-events: none;
            color: #6c757d;
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

<?= $this->section('scripts') ?>
    <script>
        $(document).ready(function() {
            const currentMemberId = $('#current_member_id').val();

            $('#parentSelectModal').on('show.bs.modal', function () {
                loadFamilyTree(currentMemberId);
            });

            $('#clearParent').click(function() {
                $('#parent_id').val('');
                $('#parent_name').val('');
            });

            $('#treeSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('#familyTreeView .tree-item').each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(searchTerm));
                });
            });

            $('#parentSelectModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                document.body.style.overflow = 'auto';
                document.body.style.paddingRight = '';
            });
        });

        function loadFamilyTree(currentMemberId) {
            $('#familyTreeView').html('<div class="text-center"><i class="bi bi-hourglass-split"></i> Loading...</div>');

            $.ajax({
                url: '<?= base_url('family-tree/get-tree') ?>',
                method: 'GET',
                data: { exclude_id: currentMemberId },
                dataType: 'html',
                success: function(response) {
                    if (response) {
                        $('#familyTreeView').html(response);
                        initializeTreeBehavior(currentMemberId);
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

        function initializeTreeBehavior(currentMemberId) {
            // Disable current member in tree
            $(`[data-id="${currentMemberId}"]`).closest('.tree-item').addClass('disabled');

            $('.tree-toggle').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const $parentLi = $(this).closest('li');
                $parentLi.children('ul').toggle(300);
                $(this).find('.bi').toggleClass('bi-chevron-right bi-chevron-down');
            });

            $('.select-parent').off('click').on('click', function(e) {
                e.preventDefault();

                // Prevent selecting self
                const id = $(this).data('id');
                if (id === currentMemberId) {
                    alert('A member cannot be their own parent.');
                    return;
                }

                const name = $(this).data('name');
                $('#parent_id').val(id);
                $('#parent_name').val(name);

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
    </script>
<?= $this->endSection() ?>