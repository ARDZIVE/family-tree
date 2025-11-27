
<p>&nbsp</p>
<div class="container d-flex align-items-center justify-content-center">
    <div class="card logout-container">
        <h1 class="logout-title">You've Been Logged Out</h1>
        <p class="logout-message">
            Thank you for using our application. You have been successfully logged out of your account.
            All your information is secure.
        </p>
        <a href="<?=base_url('auth/login')?>" class="btn btn-primary login-button mb-3 home-link" style="color:white" role="button">
            <i class="bi bi-box-arrow-in-right"></i>Log In Again
        </a>
        <div>
            <a href="<?=base_url('family-tree')?>" class="home-link">
                Return to Homepage
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>