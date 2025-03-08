<?php $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1); ?>



<nav class="navbar navbar-expand-lg bg-white fixed-top border-bottom shadow-sm">
    <div class="container-fluid">
      <div class="d-flex align-items-center gap-2">
        <!-- <a class="btn btn-white fs-4 fw-bold" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample"><i class="bi bi-filter-left"></i></a> -->
        <!-- <div class="rounded-circle bg-success" style="height: 30px; width: 30px;"></div> -->
        <img src="B.png" class="img-fluid" alt="" style="width: 30px; height: 30px;">
        <a class="navbar-brand text-success fw-bold" href="#">BartGain</a>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav d-flex align-items-md-center">
          <li class="nav-item">
            <a class="nav-link <?= $page == 'index.php' ? 'active' : ''?>" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $page == 'itemplace.php' ? 'active' : ''?>" href="itemplace.php">Item Feed</a>
          </li>
          <?php
            if(isset($_SESSION["user_details"])) {
              if($_SESSION["user_details"]["verified"] == "Y") {
                ?>
                  <li class="nav-item">
                    <a class="nav-link <?= $page == 'items.php' ? 'active' : ''?>" href="items.php">Item Place</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?= $page == 'messages-offers.php' || $page == 'messages-proposals.php' ? 'active' : ''?>" href="messages-offers.php">Messages</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?= $page == 'dashboard.php' ? 'active' : ''?>" href="dashboard.php">Dashboard</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?= $page == 'add-item.php' ? 'active' : ''?>" aria-current="page" href="add-item.php">Add Item</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">Notification</a>
                  </li>
                <?php
              } 
            }
          ?>
          <li class="nav-item">
            <?php
              if(empty($_SESSION["user_details"])) {
                ?>
                  <div class="ms-md-3">
                    <a href="sign-in.php" class="text-success text-decoration-none me-2">Sign in</a>
                    <a href="sign-up.php" class="btn btn-success rounded-pill">Join Now</a>
                  </div>
                <?php
              } else {
                ?>
                  <div class="dropdown-center">
                    <button class="btn btn-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <img src="profile-picture/<?= (isset($_SESSION["user_details"]["profile_picture"])) ? $_SESSION["user_details"]["profile_picture"]:"default.jpg" ?>" alt="Profile Picture" class="rounded-circle img-thumbnail" style="width: 40px; height: 40px;">
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                      <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                      <li class="<?= $_SESSION["user_details"]["verified"] === "Y" ? "d-none" : "" ?>"><a class="dropdown-item" href="verification.php">Verifiy</a></li>
                      <li class="<?= $_SESSION["user_details"]["role_id"] === 2 ? "" : "d-none" ?>"><a class="dropdown-item" href="admin/dashboard.php">Admin</a></li>
                    </ul>
                  </div>
                <?php
              }
            ?>
            
          </li>
        </ul>
      </div>
    </div>

    <!-- for Items -->
    <div class="container-fluid d-md-none <?= $page == 'items.php' ? '' : 'd-none'?>">
      <button class="btn btn-sm btn-light rounded forFilterToggle"><i class="bi bi-filter"></i></button>
    </div>

    <!-- for messages -->
    <div class="container-fluid d-flex pt-2 justify-content-between d-md-none <?= ($page == 'messages-offers.php' || $page == 'messages-proposals.php') ? '' : 'd-none'?>">
      <button class="btn btn-sm btn-light rounded forSide-left"><i class="bi bi-chat-left"></i></button>
      <button class="btn btn-sm btn-light rounded forSide-right"><i class="bi bi-calendar-check"></i></button>
    </div>

    <!-- for dashboard side -->
    <div class="container-fluid d-flex pt-2 justify-content-between d-md-none <?= ($page == 'dashboard.php' || $page == 'meet-up.php' || $page == 'activity-log.php' || $page == 'meet-up-history.php' || $page == 'profile.php' || $page == 'profile-edit.php' || $page == 'profile-change-password.php' || $page == 'offers.php' || $page == 'offers-history.php' || $page == 'proposals.php' || $page == 'item-listing.php' || $page == 'ratings.php' || $page == 'rating-history.php' || $page == 'your-rating.php') ? '' : 'd-none'?>">
      <button class="btn btn-sm btn-light rounded forFilterToggle"><i class="bi bi-list"></i></button>
    </div>

  </nav>

  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasExampleLabel">Filter</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="p-3">
      <div class="card-body">
        <div>
          <form id="searchForm" action="itemplace.php" method="POST">
            <label class="form-label" for="">Category: </label>
            <select name="category" class="form-control" id="myCategory">
              <option value="" disabled selected >Select category</option>
              <option value="Electronics">Electronics</option>
              <option value="Furniture">Furniture</option>
            </select>
          </form>
        </div>
      </div>
    </div>
  </div>