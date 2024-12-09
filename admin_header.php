<header id="admin_header">
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand admin_logo" href="index.php">

        <img src="img/herbarium-plants-project-high-resolution-logo-transparent.png" alt="My Logo" class="img-fluid" style="max-width: 60px; height: auto;">

      </a>
      <div class="d-flex" id="admin_nav">
        <ul class="navbar-nav d-flex flex-row">
          <li class="nav-item2">
            <a class="nav-link text-white" href="index.php">Home</a>
          </li>
          <li class="nav-item2">
            <a class="nav-link text-white" href="main_menu_admin.php">Admin Menu</a>
          </li>
          <!-- <li class="nav-item2">
            <a class="nav-link text-white" href="profile.php">Profile</a>
          </li> -->


          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['first_name'], $_SESSION['last_name'])): ?>
            <li class="nav-item2">
              <a class="nav-link text-white" href="profile.php">
                <?= htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?>
              </a>
            </li>
          <?php endif; ?>
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <li class="nav-item2">
              <a class="nav-link text-white" href="logout.php">Log out</a>
            </li>
          <?php else: ?>
            <li class="nav-item2">
              <a class="nav-link text-white" href="login.php">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>