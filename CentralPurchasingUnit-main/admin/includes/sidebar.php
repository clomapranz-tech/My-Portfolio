<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav" id="sidenavAccordion">
        <div class="sb-sidenav-menu text-white" style="background-color: #233263;">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <?php if (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] == "2") : ?>
                    <a class="nav-link" href="view-register.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Registered Users
                    </a>
                <?php endif; ?>

                <!-- Purchase Request -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePurchaseRequest" aria-expanded="false" aria-controls="collapsePurchaseRequest">
                    <div class="sb-nav-link-icon"><i class="fas fa-sheet-plastic"></i></div>
                    Purchase Request
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePurchaseRequest" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="purchase_request-view.php">View Purchase Request</a>
                    </nav>
                </div>

                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseColleges" aria-expanded="false" aria-controls="collapseColleges">
                    <div class="sb-nav-link-icon"><i class="fas fa-school"></i></div>
                    School
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseColleges" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseColleges" aria-expanded="false" aria-controls="pagesCollapseColleges">
                            Colleges
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseColleges" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php if (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] == "2") : ?>
                                    <a class="nav-link" href="college-add.php">Add Colleges</a>
                                <?php endif; ?>
                                <a class="nav-link" href="college-view.php">View Colleges</a>
                            </nav>
                        </div>
                        <?php if (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] != "2") : ?>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseDepartments" aria-expanded="false" aria-controls="pagesCollapseDepartments">
                                Departments
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseDepartments" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <?php if (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] == "2") : ?>
                                        <a class="nav-link" href="department-add.php">Add Departments</a>
                                    <?php endif; ?>
                                    <a class="nav-link" href="department-view.php">View Departments</a>
                                </nav>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] == "2") : ?>
                            <a class="nav-link" href="school_year-view.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-calendar"></i></div>
                                School Years
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>

            </div>
        </div>

        <div class="sb-sidenav-footer text-white" style="background-color:#A19158;">
            <?php if (isset($_SESSION['auth_user'])) : ?>
                <div class="small">Logged in as:
                    <a class="small" style="color:white; letter-spacing:3px;">
                        <?= htmlspecialchars($_SESSION['auth_user']['user_name']); ?>
                    </a>
                </div>
                <a class="small" href="../index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-home"></i> Return to Front Page</div>
                </a>
            <?php endif; ?>
        </div>
    </nav>
</div>