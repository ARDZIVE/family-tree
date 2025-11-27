<!doctype html>
<html lang="<?= session('lang');?>">
<?= view('includes/header'); ?>
<body>
<?= $this->include('includes/fonts') ?>
<?= $this->include('includes/navbar') ?>
<div class="container">
    <div class="mb-3"></div>
    <?= view('includes/contents'); ?>
</div>
<br>
<?= view('includes/footer'); ?>
</body>
</html>