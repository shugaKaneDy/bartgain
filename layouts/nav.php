<!-- nav -->
<?php $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1); ?>
<nav class="container-fluid m-0 bg-white shadow-sm row align-items-center fixed-top w-100 my-nav">
    <div class="col-12 col-md-6 d-flex justify-content-between justify-content-md-start align-items-center gap-1 ">
      <button class="text-success bg-transparent border-0 fs-3 my-burger rounded-circle px-2"
      data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"
      >
        <i class="bi bi-list"></i>
      </button>
      <a class="navbar-brand d-flex align-items-center gap-1" href="#">
        <img class="rounded my-logo" src="assets/logo.png" alt="">
        <span class="fw-bold my-text-logo text-success p-0 m-0">BartGain</span>
      </a>
      <a href="search.php" class="text-success bg-transparent border-0 fs-3 my-burger rounded-circle px-2 tt"
        data-bs-placement="bottom" data-bs-title="Search"
      >
        <i class="bi bi-search"></i>
      </a>
    </div>
    <div class="col-12 col-md-6 p-0">
      <ul class="d-flex align-items-center justify-content-between justify-content-md-end list-unstyled gap-0 m-0">
        <li class="tt" data-bs-placement="bottom" data-bs-title="Itemplace">
          <a href="itemplace.php" class="text-decoration-none text-secondary 
          <?= $page == 'itemplace.php' ? 'link-text-success-active' : '' ?>
          link-nav-hover pb-1 pt-3 px-3">
            <span>
              <i class="bi bi-shop-window fs-4"></i>
            </span>
          </a>
        </li>
        <li class="tt" data-bs-placement="bottom" data-bs-title="Meet-up">
          <a href="meet-up.php" class="text-decoration-none text-secondary
          <?= $page == 'meet-up.php' || $page == 'meet-up-receiver.php' || $page == 'meet-up-sender.php' ? 'link-text-success-active' : '' ?>
          link-nav-hover pb-1 pt-3 px-3">
            <span>
              <i class="bi bi-geo-alt fs-4"></i>
            </span>
          </a>
        </li>
        <li class="tt" data-bs-placement="bottom" data-bs-title="Favorites">
          <a href="favorites.php" class="text-decoration-none text-secondary
          <?= $page == 'favorites.php' ? 'link-text-success-active' : '' ?>
          link-nav-hover pb-1 pt-3 px-3">
            <span>
              <i class="bi bi-heart fs-4"></i>
            </span>
          </a>
        </li>
        <li class="tt" data-bs-placement="bottom" data-bs-title="Messages">
          <a href="message-offers.php" class="text-decoration-none text-secondary
          <?= $page == 'message-offers.php' || $page == 'message-proposals.php' ? 'link-text-success-active' : '' ?>
          link-nav-hover pb-1 pt-3 px-3">
            <span class="position-relative chatNotification">
              <i class="bi bi-chat fs-4"></i>
              
            </span>
          </a>
        </li>
        <li class="tt" data-bs-placement="bottom" data-bs-title="Notification">
          <a href="notifications.php" class="text-decoration-none text-secondary link-nav-hover pb-1 pt-3 px-3">
            <span class="position-relative notificationNotification">
              <i class="bi bi-bell fs-4"></i>
              
            </span>
          </a>
        </li>
        <li class="tt" data-bs-placement="bottom" data-bs-title="Profile">
          <a href="profile.php">
              <img src="profile-uploads/<?= $_SESSION['user_details']['profile_picture'] ? $_SESSION['user_details']['profile_picture'] : "default.jpg" ?>" class="rounded-circle img-thumbnail border-0 my-profile">
          </a>
        </li>
        
      </ul>
    </div>
  </nav>