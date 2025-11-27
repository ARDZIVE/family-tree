<!doctype html>
<html lang="<?= session('lang');?>">
<?= view('includes/header'); ?>
<body class="bg-light">
<?= $this->include('includes/fonts') ?>
<?php //= $this->include('includes/navbar_no_menu') ?>
<div class="container">
    <div class="mb-3"></div>
    <?= view('includes/contents'); ?>
</div>
<br>
<?= view('includes/footer'); ?>
</body>
</html>