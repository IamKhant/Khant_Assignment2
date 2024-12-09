<?php
session_name('Khant');
session_start();

require_once 'main.php';
?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
  <?php include 'head.php'; ?>
<body id="indexbody" class="d-flex flex-column min-vh-100">
  <?php include 'header.php'; ?>
  <article class="flex-grow-1 position-relative">
    <div class="backgroundIMG">
      <img src="img/homeBG.JPG" class="img-fluid" alt="homeBackground" id="backgroundImage">
      <h2 class="welcome-text">Welcome to the Herbarium for Plant Biodiversity!</h2>

      <div class="button-container d-flex content-center gap-3">
        <a href="login.php" class="btn btn-lg" id="login_button">Login</a>
        <a href="registration.php" class="btn btn-lg" id="register_button">Register</a>
      </div>
    </div>
    <div id="scrollArrow" class="arrow-container">
      <i class="arrow down"></i>
    </div>
    </div>

    <div class="intro-container">
      <div class="intro-text">
        <p>Our project is dedicated to the conservation and study of plant diversity, offering a
          comprehensive database and resources for researchers, students, and nature enthusiasts alike.
          Explore the rich variety of flora, learn about the importance of biodiversity, and contribute
          to our understanding of plant species.</p>
        <p>Join us in celebrating the beauty of nature and the vital role plants play in our ecosystem.
          Together, we can foster a deeper appreciation for our planet’s greenery and promote sustainable
          practices for future generations.</p>
      </div>
      <div class="intro-image">
        <img id="image1" src="img/contributeImg/default1.jpg" alt="Random Plant Image 1" class="img-fluid">
      </div>
    </div>
    <hr class="center-divider">
    <div class="intro-container1">
      <div class="intro-text1">
        <p>The herbarium of the future, the ‘global metaherbarium’, will be a common, global, digitally
          interlinked and open-access resource that will stimulate large-scale and novel science to directly
          address our current biodiversity crisis.</p>
      </div>
      <div class="intro-image1">
        <img id="image2" src="img/contributeImg/default2.jpg" alt="Random Plant Image 2" class="img-fluid">
      </div>
    </div>

    <hr class="center-divider">
  </article>
  <a href="#" class="back-to-top" id="backToTop">
    &#8679;
  </a>
  <?php include 'footer.php'; ?>
  <script src="script.js"></script>
</body>

</html>