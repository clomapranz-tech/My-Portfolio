<!-- Sidebar -->
<nav id="sidebarMenu" class="d-lg-block sidebar collapse">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <!-- Main Dashboard -->
            <a href="#" class="list-group-item list-group-item-action py-2 ripple" aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i>
                <span>Sidebar</span>
            </a>
            
            <!-- School Years Dropdown -->
            <div class="accordion mt-3" id="accordionSchoolYears">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="schoolYearsHeading">
                        <button class="accordion-button" type="button" aria-expanded="false" aria-controls="collapseSchoolYears">
                            <i class="fas fa-calendar-alt fa-fw me-3"></i>
                            School Year
                        </button>
                    </h2>
                    <div id="collapseSchoolYears" class="accordion-collapse collapse" aria-labelledby="schoolYearsHeading" data-bs-parent="#accordionSchoolYears">
                        <div class="accordion-body">
                            <!-- Query School Years -->
                            <ul class="list-group">
                                <?php
                                 $query = "SELECT * FROM school_year";
                                 $query_run = mysqli_query($con, $query);
                                 if(mysqli_num_rows($query_run) > 0){

                                        foreach($query_run as $item)
                                        {
                                        ?>
                                        <ul><a class="list-group-item" href="#"><?= $item['school_year']; ?></a></ul>
                                        <?php
                                        }
                                 }
                                 
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- College Dropdown -->
            <div class="accordion mt-3" id="accordionCollege">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="collegeHeading">
                        <button class="accordion-button" type="button" aria-expanded="false" aria-controls="collapseCollege">
                            <i class="fas fa-graduation-cap fa-fw me-3"></i>
                            College
                        </button>
                    </h2>
                    <div id="collapseCollege" class="accordion-collapse collapse" aria-labelledby="collegeHeading" data-bs-parent="#accordionCollege">
                        <div class="accordion-body">
                        <!-- Query Colleges -->    
                        <ul class="list-group">
                                <?php
                                 $query = "SELECT * FROM college";
                                 $query_run = mysqli_query($con, $query);
                                 if(mysqli_num_rows($query_run) > 0){

                                        foreach($query_run as $item)
                                        {
                                        ?>
                                        <ul><a class="list-group-item" href="#" style="white-space: nowrap;"><?= $item['name']; ?></a></ul>
                                        <?php
                                        }
                                 }
                                 
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Department Dropdown -->
            <div class="accordion mt-3" id="accordionDepartment">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="departmentHeading">
                        <button class="accordion-button" type="button" aria-expanded="false" aria-controls="collapseDepartment">
                            <i class="fas fa-building fa-fw me-3"></i>
                            Department
                        </button>
                    </h2>
                    <div id="collapseDepartment" class="accordion-collapse collapse" aria-labelledby="departmentHeading" data-bs-parent="#accordionDepartment">
                        <div class="accordion-body">
                            <!-- Query School Departments -->
                            <ul class="list-group">
                                <?php
                                 $query = "SELECT * FROM department";
                                 $query_run = mysqli_query($con, $query);
                                 if(mysqli_num_rows($query_run) > 0){

                                        foreach($query_run as $item)
                                        {
                                        ?>
                                        <ul><a class="list-group-item" href="#" style="white-space: nowrap;"><?= $item['name']; ?></a></ul>
                                        <?php
                                        }
                                 }
                                 
                                ?>
                            </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- Sidebar -->

<!-- Bootstrap JavaScript -->


<script>
    // JavaScript to handle accordion collapse and expand
    const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(item => {
        const button = item.querySelector('.accordion-button');
        const collapse = item.querySelector('.accordion-collapse');

        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true' || false;
            button.setAttribute('aria-expanded', !expanded);
            collapse.classList.toggle('show');
        });
    });
</script>
