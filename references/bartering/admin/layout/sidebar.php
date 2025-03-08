<!-- sidebar -->
<?php $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1); ?>
<div class="px-1 sidebar bg-light border-end">
  <ul class="list-group flex-column">
    <li class="list-group-item rounded <?= $page == 'dashboard.php' ? 'active-list' : '' ?>">
        <a class="nav-link" href="dashboard.php"><i class="bi bi-view-stacked me-2"></i>Dashboard</a>
    </li >
    <li class="list-group-item rounded <?= $page == 'users.php' || $page == 'admin-accounts.php' || $page == 'add-user.php' || $page == 'view-user-account.php' ? 'active-list' : '' ?>">
        <a class="nav-link" href="users.php"><i class="bi bi-person me-2"></i>User Accounts</a>
    </li>
    <li class="list-group-item rounded">
        <a class="nav-link" href="#"><i class="bi bi-flag me-2"></i>Reports</a>
    </li>
    <li class="list-group-item rounded  <?= $page == 'verification.php' || $page == 'verification-history.php' ? 'active-list' : '' ?>">
        <a class="nav-link" href="verification.php"><i class="bi bi-person-check me-2"></i>Verification</a>
    </li>
    <li class="list-group-item rounded <?= $page == 'items.php' || $page == 'view-item-information.php' ? 'active-list' : '' ?>">
        <a class="nav-link" href="items.php"><i class="bi bi-bag me-2"></i>Items</a>
    </li>
    <li class="list-group-item rounded <?= $page == 'offers.php' || $page == 'view-offer-information.php' ? 'active-list' : '' ?>">
        <a class="nav-link" href="offers.php"><i class="bi bi-gift me-2"></i>Offers</a>
    </li>
    <li class="list-group-item rounded <?= $page == 'meet-ups.php' ? 'active-list' : '' ?>">
        <a class="nav-link" href="meet-ups.php"><i class="bi bi-geo-alt me-2"></i>Meet Ups</a>
    </li>
  </ul>
</div>