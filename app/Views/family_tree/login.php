<!DOCTYPE html>
<html lang="en">
<head>
    <?=link_tag('assets/plugins/bootstrap/css/bootstrap.css');?>

    <?=link_tag('assets/plugins/bootstrap/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css');?>

    <?=link_tag('assets/plugins/dataTables/dataTables.bootstrap5.min.css');?>

    <?=link_tag('assets/plugins/toastr/css/toastr.css');?>

    <?=link_tag('assets/css/mydatatables.css');?>
    <?=link_tag('assets/css/mynavbar.css');?>

    <?=script_tag('assets/plugins/bootstrap/js/bootstrap.bundle.js') ?>

    <?=script_tag('assets/js/jquery-3.7.1.min.js') ?>

    <?=script_tag('assets/plugins/dataTables/jquery.dataTables.min.js') ?>

    <?=script_tag('assets/plugins/dataTables/dataTables.bootstrap5.min.js') ?>

    <?=script_tag('assets/plugins/toastr/js/toastr.js') ?>

    <!--    --><?php //=script_tag('assets/js/family-tree-interactions.js') ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body class="bg-light">
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <!-- Logo/Image -->
                    <div class="text-center mb-4">
                        <img src="<?=base_url('assets/images/Talatinians_Tree.jpg')?>" alt="Logo" class="img-fluid rounded-circle" width="400px">
                    </div>

                    <!-- Login Form -->
                    <form>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">Sign In</button>
                        </div>
                    </form>

                    <!-- Footer Links -->
                    <div class="text-center mt-3">
                        <a href="#" class="text-decoration-none">Forgot password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('family_tree/partials/tree-modal-styles') ?>
<?= $this->renderSection('content') ?>
<?= $this->renderSection('scripts') ?>

</body>
</html>