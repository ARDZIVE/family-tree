<style>
    :root {
        --primary-color: #6070D6;
    }
    .error-page {
        min-height: 10vh;
    }
    .error-code {
        font-size: 120px;
        color: var(--primary-color);
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    .error-message {
        color: #444;
        font-size: 24px;
    }
    .home-button {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 12px 30px;
        font-size: 18px;
        transition: all 0.3s ease;
    }
    .home-button:hover {
        background-color: #4F5EC0;
        border-color: #4F5EC0;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
<div class="error-page d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="error-code mb-4">404</div>
        <h1 class="error-message mb-4">Oops! Page Not Found</h1>
        <p class="text-muted mb-5">The page you are looking for might have been removed, <br>had its name changed, or is temporarily unavailable.</p>
        <a href="<?=base_url('family-tree')?>" class="btn btn-primary btn-lg home-button">
            Back to Home
        </a>
    </div>
</div>