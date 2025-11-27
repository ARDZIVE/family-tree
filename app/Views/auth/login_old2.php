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