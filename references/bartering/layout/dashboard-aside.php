<?php $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1); ?>
<aside class="px-3 side-search d-none d-md-block bg-white border border-right shadow shadow-sm">
    <nav class="nav flex-column">
      <a class="nav-link rounded <?= $page == 'dashboard.php' ? "bg-success-subtle" :"" ?>" href="dashboard.php"><i class="bi bi-bar-chart"></i> Dashboard</a>
      <a class="nav-link rounded <?= $page == 'item-listing.php' ? "bg-success-subtle" :"" ?>" href="item-listing.php"><i class="bi bi-bag"></i> Item Listing</a>
      <a class="nav-link rounded <?= $page == 'offers.php' || $page == 'offers-history.php' ? "bg-success-subtle" :"" ?>" href="offers.php"><i class="bi bi-tag"></i> Offers</a>
      <a class="nav-link rounded <?= $page == 'proposals.php' || $page == 'proposals-history.php' ? "bg-success-subtle" :"" ?>" href="proposals.php"><i class="bi bi-gift"></i> Proposals</a>
      <a class="nav-link rounded <?= $page == 'meet-up.php' || $page == 'meet-up-history.php' ? "bg-success-subtle" :"" ?>" href="meet-up.php"><i class="bi bi-calendar-check"></i> Meet-up</a>
      <a class="nav-link rounded <?= $page == 'profile.php' || $page == 'profile-edit.php' || $page == 'profile-change-password.php' ? "bg-success-subtle" :"" ?>" href="profile.php"><i class="bi bi-person"></i> Profile</a>
      <a class="nav-link rounded <?= $page == 'activity-log.php' ? "bg-success-subtle" :"" ?>" href="activity-log.php"><i class="bi bi-clock"></i> Activity Logs</a>
      <a class="nav-link rounded <?= $page == 'ratings.php' || $page == 'rating-history.php' || $page == 'your-rating.php' ? "bg-success-subtle" :"" ?>" href="ratings.php"><i class="bi bi-star "></i> Ratings</a>
      <a class="nav-link rounded" href="#"><i class="bi bi-heart "></i> Favorite</a>
      <a class="nav-link rounded" href="#"><i class="bi bi-cash "></i> Transaction</a>
    </nav>
  </aside>