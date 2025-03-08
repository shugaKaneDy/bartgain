<?php $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1); ?>
<aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <img src="../assets/logo.png" alt="AdminLTE Logo" class="brand-image rounded elevation-1" style="opacity: .8">
      <span class="brand-text font-weight-bold">BartGain</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../profile-uploads/<?= empty($_SESSION['user_details']['profile_picture']) ? "default.jpg" : $_SESSION['user_details']['profile_picture']?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= $_SESSION['user_details']['fullname'] ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="index.php" class="nav-link
            <?= $page == 'index.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../itemplace.php" class="nav-link">
              <i class="nav-icon fas fa-store"></i>
              <p>
                User-side
              </p>
            </a>
          </li>
          <li class="nav-header">USER MANAGEMENT</li>
          <li class="nav-item">
            <a href="users.php" class="nav-link
            <?= $page == 'users.php' || $page == 'user-edit.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="verification.php" class="nav-link
            <?= $page == 'verification.php' || $page == 'verification-view.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-user-check"></i>
              <p>
                Verification
              </p>
            </a>
          </li>
          <li class="nav-header">REPORT MANAGEMENT</li>
          <li class="nav-item">
            <a href="report-user.php" class="nav-link
            <?= $page == 'report-user.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-user-times"></i>
              <p>
                 User Reported
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report-item.php" class="nav-link
            <?= $page == 'report-item.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-shopping-bag"></i>
              <p>
                Item Reported
              </p>
            </a>
          </li>
          <li class="nav-header">FINANCE</li>
          <li class="nav-item">
            <a href="revenue.php" class="nav-link
            <?= $page == 'revenue.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Revenue
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="expense.php" class="nav-link
            <?= $page == 'expense.php' || $page == 'expense-edit.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Expense
              </p>
            </a>
          </li>
          <li class="nav-header">SALES</li>
          <li class="nav-item">
            <a href="sale-boost.php" class="nav-link
            <?= $page == 'sale-boost.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-rocket"></i>
              <p>
                Boost
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="sale-premium.php" class="nav-link
            <?= $page == 'sale-premium.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-gem"></i>
              <p>
                Premium
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php" class="nav-link
            <?= $page == 'sale-ads.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-ad"></i>
              <p>
                Advertisement
              </p>
            </a>
          </li>
          <li class="nav-header">SUPPORT CENTER</li>
          <li class="nav-item">
            <a href="faqs.php" class="nav-link
            <?= $page == 'faqs.php' || $page == 'faq-edit.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>
                FAQs
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="tickets.php" class="nav-link
            <?= $page == 'tickets.php' || $page == 'ticket-view.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-ticket-alt"></i>
              <p>
                Tickets
              </p>
            </a>
          </li>
          <li class="nav-header">VIEW</li>
          <li class="nav-item">
            <a href="items.php" class="nav-link
            <?= $page == 'items.php' || $page == 'item-view.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-shopping-bag"></i>
              <p>
                Items
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="offers.php" class="nav-link
            <?= $page == 'offers.php' || $page == 'offer-view.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-tag"></i>
              <p>
                Offers
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="activity-logs.php" class="nav-link
            <?= $page == 'activity-logs.php' ? "active bg-success" : "" ?>
            ">
              <i class="nav-icon fas fa-clock"></i>
              <p>
                Activity Logs
              </p>
            </a>
          </li>
          <!-- <li class="nav-header">BACK UPS</li>
          <li class="nav-item">
            <a href="backup-database.php" class="nav-link
            ">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Database
              </p>
            </a>
          </li> -->

          

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <div class="sidebar-custom">
    </div>
  </aside>