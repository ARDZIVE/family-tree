<!DOCTYPE html>
<html lang="en">

<?= $this->include('includes/header') ?>

<body class="bg-light">
<?= $this->include('includes/fonts') ?>

<?= $this->include('includes/navbar') ?>

<?= $this->include('family_tree/partials/tree-modal-styles') ?>
<?= $this->renderSection('content') ?>
<?= $this->renderSection('scripts') ?>
</body>
</html>