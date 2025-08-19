<div class="container-fluid px-2">

 <!-- Navbar Top Right -->
<nav class="navbar navbar-expand-lg">
  
    
      <!-- XU Logo -->

  <a href="index.php" style="width:300px;"> 
    <img src="assets/images/XULOGO.png" alt="Logo" style="width: 90%;">
    
  </a>
  
   <!-- Search Bar -->
   <form class="flex justify-center" action="index_search.php" method="GET"> <!-- Update action to index.php -->
    <input class="form-control me-2 custom-search" type="search" name="search_query" placeholder="Search" aria-label="Search"> <!-- Add name attribute -->
    <button class="btn btn-outline-primary" type="submit">Search</button>
</form>
      <!-- Navbar Links -->
      <ul class="navbar-nav ml-auto mb-2 mb-lg-0">

        <li class="nav-item text-center customfont">
          <a class="nav-link customfont text-nowrap" href="#">About Us</a>
        </li>
        

        <?php if(isset($_SESSION['auth_user'])) : ?>

        <li class="nav-item dropdown customfont">
          <a class="nav-link dropdown-toggle customfont" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $_SESSION['auth_user']['user_name']; ?>
          </a>
          <ul class="dropdown-menu customfont" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item customfont" href="#">My Profile</a></li>
            <li>
              <!-- If logged in show shortcut to admin panel -->
              <?php if($_SESSION['auth_role'] == '1' || $_SESSION['auth_role'] == '2' || $_SESSION['auth_role'] == '3' || $_SESSION['auth_role'] == '4'){
                ?>
              <a class="dropdown-item customfont" href="admin/index.php">Admin Panel</a>
              <?php
               }?>
            </li>
              <form action="allcode.php" method="post">
                <button type="submit" name="logout_btn" class="dropdown-item">Logout</button>
              </form>
            </li>
          </ul>
        </li>

        <?php else : ?>

        <li class="nav-item">
          <a class="nav-link customfont" href="login.php">Login</a>
        </li>
        

        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

   



<!-- Nav Center -->
<!-- <ul class="nav justify-center">
  <li class="nav-item">
    <a class="nav-link active customfont" href="index.php">HOME</a>
  </li>
  <li class="nav-item">
    <a class="nav-link customfont" href="form.php">REQUEST FORM</a>
  </li>
  <li class="nav-item">
    <a class="nav-link customfont" href="myrequests.php"> MY REQUESTS</a>

  </li>
  
</ul> -->
