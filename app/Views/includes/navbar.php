<style>
    :root {
        --primary-color: #6070D6;
    }

    .user-dropdown {
        position: relative;
    }

    .user-circle {
        width: 40px;
        height: 40px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(96, 112, 214, 0.2);
    }

    .user-circle:hover {
        background-color: #4F5EC0;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(96, 112, 214, 0.3);
    }

    .dropdown-menu {
        margin-top: 10px;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(96, 112, 214, 0.15);
        padding: 8px 0;
        min-width: 200px;
        display: none;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-menu:before {
        content: '';
        position: absolute;
        top: -6px;
        right: 20px;
        width: 12px;
        height: 12px;
        background: white;
        transform: rotate(45deg);
        border-radius: 2px;
        box-shadow: -2px -2px 4px rgba(96, 112, 214, 0.05);
    }

    .dropdown-item {
        padding: 10px 24px;
        color: #444;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: rgba(96, 112, 214, 0.08);
        color: var(--primary-color);
        padding-left: 28px;
    }


</style>

<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <button class="navbar-toggler three-dots" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas">
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <a class="navbar-brand site-name" href="<?= base_url('family-tree') ?>">FamilyTree</a>

        <div class="desktop-menu mx-auto">
            <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item">
                    <a href="<?= base_url('family-tree') ?>" class="btn btn-light btn-sm ms-2">
                        <i class="bi bi-house"></i>
                        <span class="d-none d-md-inline">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('family-tree') ?>" class="btn btn-light btn-sm ms-2">
                        <i class="bi bi-card-list"></i>
                        <span class="d-none d-md-inline">List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('family-tree/chart') ?>" class="btn btn-light btn-sm ms-2">
                        <i class="bi bi-diagram-3"></i>
                        <span class="d-none d-md-inline">Chart</span>
                    </a>
                </li>

            </ul>
        </div>

        <div class="dropdown user-dropdown ms-2">
            <div class="user-circle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                HT
            </div>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person-gear"></i> My Account</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-sliders"></i> Settings</a></li>
<!--                <li><hr class="dropdown-divider"></li>-->
                <li><a class="dropdown-item" href="<?=base_url('auth/logout')?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="navbarOffcanvas">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">FamilyTree</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('family-tree') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('family-tree') ?>">List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('family-tree/chart') ?>">Chart</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.querySelector('.user-dropdown');
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
        let timeoutId;

        // Show dropdown on hover
        dropdown.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
            dropdownMenu.classList.add('show');
        });

        // Add delay before hiding
        dropdown.addEventListener('mouseleave', function() {
            timeoutId = setTimeout(() => {
                dropdownMenu.classList.remove('show');
            }, 200); // 200ms delay before hiding
        });

        // Keep menu open while hovering over it
        dropdownMenu.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
        });

        // Hide menu when mouse leaves the dropdown menu
        dropdownMenu.addEventListener('mouseleave', function() {
            timeoutId = setTimeout(() => {
                dropdownMenu.classList.remove('show');
            }, 200);
        });
    });
</script>