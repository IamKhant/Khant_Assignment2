<header>
  <nav>
    <div class="navcontainer">
      <!-- Project Title -->
      <div class="left_nav">
        <h5 id="nav-item1">
          <a class="nav-link" href="index.php">Herbarium Plants Project</a>
        </h5>
      </div>

      <!-- Logo -->
      <div>
        <a class="navbar-brand" href="index.php">
          <img src="img/leaves-eco-logo.png" alt="My Logo">
        </a>
      </div>

      <!-- Hamburger Icon for Mobile View -->
      <div class="hamburger" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>

      <!-- Navigation Links -->
      <div class="middle_nav">
        <ul class="navlist">
          <!-- Home Link -->
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>

          <!-- Main Menu Link (conditional based on user type) -->
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <?php if ($_SESSION['type'] == 'user'): ?>
              <li class="nav-item">
                <a class="nav-link" href="main_menu.php">Main Menu</a>
              </li>
            <?php elseif ($_SESSION['type'] == 'admin'): ?>
              <li class="nav-item">
                <a class="nav-link" href="main_menu_admin.php">Admin Menu</a>
              </li>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Profile Link (only for logged-in users with names) -->
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['first_name'], $_SESSION['last_name'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="profile.php">
                <?= htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?>
              </a>
            </li>
          <?php endif; ?>

          <!-- Login/Logout Links -->
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Log out</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>