<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= esc($member['name']) ?>'s Details</h5>
                        <div>
                            <a href="<?= base_url('family-tree') ?>" class="btn btn-light btn-sm ms-2">
                                <i class="bi bi-arrow-left-square"></i> Back to List
                            </a>
                            <a href="<?= site_url('family-tree/edit/' . $member['id']) ?>" class="btn btn-light btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Personal Information Column -->
                            <div class="col-md-6">
                                <h6 class="text-muted">Personal Information</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Name</th>
                                        <td><?= esc($member['name']) ?></td>
                                    </tr>
                                    <?php if ($member['alias']): ?>
                                        <tr>
                                            <th>Alias</th>
                                            <td><?= esc($member['alias']) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Gender</th>
                                        <td><?= $member['gender'] == 1 ? 'Male' : ($member['gender'] == 2 ? 'Female' : 'Not specified') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Birth Information</th>
                                        <td>
                                            Year: <?= $member['birth_year'] ?? 'Not specified' ?><br>
                                            Place: <?= esc($member['birth_place']) ?? 'Not specified' ?>
                                        </td>
                                    </tr>
                                    <?php if ($member['dead_year'] || $member['dead_place']): ?>
                                        <tr>
                                            <th>Death Information</th>
                                            <td>
                                                Year: <?= $member['dead_year'] ?? 'Not specified' ?><br>
                                                Place: <?= esc($member['dead_place']) ?? 'Not specified' ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($member['occupation']): ?>
                                        <tr>
                                            <th>Occupation</th>
                                            <td><?= esc($member['occupation']) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($member['m_order']): ?>
                                        <tr>
                                            <th>Marriage Order</th>
                                            <td><?= esc($member['m_order']) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>

                            <!-- Family Relationships Column -->
                            <div class="col-md-6">
                                <h6 class="text-muted">Family Relationships</h6>
                                <table class="table table-bordered">
                                    <?php if ($parent): ?>
                                        <tr>
                                            <th width="30%">Father</th>
                                            <td>
                                                <a href="<?= site_url('family-tree/view/' . $parent['id']) ?>">
                                                    <?= esc($parent['name']) ?>
                                                    <?php if ($parent['alias']): ?>
                                                        (<?= esc($parent['alias']) ?>)
                                                    <?php endif; ?>
                                                </a>
                                                <?php if ($parent['birth_year']): ?>
                                                    <br><small class="text-muted">Born: <?= esc($parent['birth_year']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($member['mother']): ?>
                                        <tr>
                                            <th>Mother</th>
                                            <td><?= esc($member['mother']) ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if ($member['spouse']): ?>
                                        <tr>
                                            <th>Spouse</th>
                                            <td><?= esc($member['spouse']) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>

                                <!-- Brothers and Sisters Section -->
                                <?php if ($siblings): ?>
                                    <h6 class="text-muted mt-4">Brothers and Sisters</h6>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Birth Year</th>
                                            <th>Gender</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($siblings as $sibling): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= site_url('family-tree/view/' . $sibling['id']) ?>">
                                                        <?= esc($sibling['name']) ?>
                                                        <?php if ($sibling['alias']): ?>
                                                            (<?= esc($sibling['alias']) ?>)
                                                        <?php endif; ?>
                                                    </a>
                                                </td>
                                                <td><?= $sibling['birth_year'] ?? 'Not specified' ?></td>
                                                <td>
                                                    <?php
                                                    echo $sibling['gender'] == 1 ? 'Brother' :
                                                        ($sibling['gender'] == 2 ? 'Sister' : 'Not specified');
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>

                                <!-- Children Section -->
                                <?php if ($children): ?>
                                    <h6 class="text-muted mt-4">Children</h6>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Birth Year</th>
                                            <th>Gender</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($children as $child): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= site_url('family-tree/view/' . $child['id']) ?>">
                                                        <?= esc($child['name']) ?>
                                                        <?php if ($child['alias']): ?>
                                                            (<?= esc($child['alias']) ?>)
                                                        <?php endif; ?>
                                                    </a>
                                                </td>
                                                <td><?= $child['birth_year'] ?? 'Not specified' ?></td>
                                                <td><?= $child['gender'] == 1 ? 'Son' : ($child['gender'] == 2 ? 'Daughter' : 'Not specified') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <?php if ($member['comments']): ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-muted">Additional Comments</h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <?= nl2br(esc($member['comments'])) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>