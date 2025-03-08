<!-- Offcanvas -->
<div class="offcanvas offcanvas-start shadow shadow-sm my-offcanvas" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
  <div class="offcanvas-header py-1">
    <a class="navbar-brand d-flex align-items-center gap-1" href="#">
      <img class="rounded my-logo" src="assets/logo.png" alt="">
      <span class="fw-bold my-text-logo text-success p-0 m-0">BartGain</span>
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <aside class="side-search bg-white">
      <nav class="nav flex-column">

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'profile.php' ? "bg-success-subtle text-success" : "" ?>"
        href="profile.php">
          <div class="side-icon-width">
            <img src="profile-uploads/<?= $_SESSION['user_details']['profile_picture'] ? $_SESSION['user_details']['profile_picture'] : "default.jpg" ?>" class="rounded-circle border-0 my-profile" style="width: 32px; height: 32px">
          </div>
          <div class="m-0">
            <?= $_SESSION['user_details']['fullname'] ?>
          </div>
        </a>

        <?php if($_SESSION['user_details']['role_id'] == 2): ?>
          <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center" href="admin/index.php">
            <div class="side-icon-width side-icon-width-size">
              <i class="bi bi-person-lock"></i>
            </div>
            <div class="m-0">
              Admin
            </div>
          </a>
        <?php endif ?>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'premium-view.php' || $page == 'premium.php' ? "bg-success-subtle text-success" : "" ?>" 
        href="premium-view.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-gem"></i>
          </div>
          <div class="m-0">
            Get Premium
          </div>
        </a>

        <?php if($_SESSION['user_details']['verified'] == "N"): ?>
          <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center" href="verification.php">
            <div class="side-icon-width side-icon-width-size">
              <i class="bi bi-person-check"></i>
            </div>
            <div class="m-0">
              Verification
            </div>
          </a>
        <?php endif ?>


        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'item-listing.php' ? "bg-success-subtle text-success" : "" ?>"
        href="item-listing.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-bag"></i>
          </div>
          <div class="m-0">
            Item Listing
          </div>
        </a>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'offers.php' ? "bg-success-subtle text-success" : "" ?>"
        href="offers.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-tag"></i>
          </div>
          <div class="m-0">
            Offers
          </div>
        </a>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'proposals.php' ? "bg-success-subtle text-success" : "" ?>"
        href="proposals.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-gift"></i>
          </div>
          <div class="m-0">
            Proposals
          </div>
        </a>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'activity-logs.php' ? "bg-success-subtle text-success" : "" ?>"
        href="activity-logs.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-clock"></i>
          </div>
          <div class="m-0">
            Activity Logs
          </div>
        </a>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'ratings.php' ? "bg-success-subtle text-success" : "" ?>"
        href="ratings.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-star"></i>
          </div>
          <div class="m-0">
            Ratings
          </div>
        </a>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'favorites.php' ? "bg-success-subtle text-success" : "" ?>"
        href="favorites.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-heart"></i>
          </div>
          <div class="m-0">
            Favorites
          </div>
        </a>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'transactions.php' ? "bg-success-subtle text-success" : "" ?>"
        href="transactions.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-cash"></i>
          </div>
          <div class="m-0">
            Transactions
          </div>
        </a>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'faq.php' ? "bg-success-subtle text-success" : "" ?>"
        href="faq.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-question-circle"></i>
          </div>
          <div class="m-0">
            FAQs
          </div>
        </a>

        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center
        <?= $page == 'tickets.php' || $page == 'ticket-view.php' ? "bg-success-subtle text-success" : "" ?>"
        href="tickets.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-ticket"></i>
          </div>
          <div class="m-0">
            Tickets
          </div>
        </a>


        <a class="nav-link rounded text-secondary link-nav-hover d-flex align-items-center" href="logout.php">
          <div class="side-icon-width side-icon-width-size">
            <i class="bi bi-box-arrow-left"></i>
          </div>
          <div class="m-0">
            Log Out
          </div>
        </a>

      </nav>
    </aside>
  </div>
</div>