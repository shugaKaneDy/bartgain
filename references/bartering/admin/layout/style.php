<!-- style -->
<style>
  .main-content {
    padding-left: 280px;
  }
  .main-title {
    margin-top: 60px !important;
  }
  .sidebar {
    position: fixed;
    width: 280px;
    top: 0;
    bottom: 0;
    padding-top: 70px;
    z-index: 1;
    overflow-y: auto;
  }
  .navbar {
    z-index: 2;
    position: fixed;
    left: 0;
    right: 0;
  }
  
  .list-group, .list-group-item {
    border: 0;
    background-color: #f8f9fa;
  }
  .active-list {
    background-color: #198754;
    color: white;
  }

  @media (max-width: 768px) {
    .main-content {
      padding-left: 0;
    }
    .sidebar {
      display: none;
    }
  }
  </style>