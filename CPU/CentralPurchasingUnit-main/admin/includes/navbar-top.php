<style>
    .navbarcolor {
        background-color: #283971;
        font-family: Arial, Helvetica, sans-serif;
    }

    .navbar-brand img {
        height: 40px; /* Adjust as needed */
        width: auto;
    }
</style>
<nav class="sb-topnav navbar navbar-expand navbar-dark navbarcolor">
    <!-- Navbar Brand with Logo -->
    <a class="navbar-brand ps-3" href="index.php">
        <img src="includes/assets/images/XU_Logotype_logo.png" alt="CPU Admin Logo">
    </a>
    
    <!-- Sidebar Toggle -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search">
            <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    
    <!-- Navbar -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle bg-light" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                <li><hr class="dropdown-divider"></li>
                <form action="code.php" method="post">
                    <button type="submit" name="logout_btn" class="dropdown-item">Logout</button>
                </form>
            </ul>
        </li>
    </ul>
</nav>
