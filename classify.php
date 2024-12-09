<?php
session_name('Khant'); 
session_start();
// Check if the user is logged in, otherwise redirect to the login page (index.php)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("location: login.php");
  exit;
}
?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>

<?php include 'head.php'; ?>

<body id="classificationbody" class="d-flex flex-column min-vh-100">
  <?php include 'header.php'; ?>

  <article class="flex-grow-1 container classify_container mt-5 mb-3"> <!-- Use inline style for immediate testing -->
    <h1 class="text-center calssify_text">Plant Classification</h1>
    <!-- Plant Family Section -->
    <section class="plant-section my-3">
      <h2 class="section-title">Plant Family</h2>
      <p class="section-text">
        Each member of a plant family shares many botanical features. This is the highest classification group normally referred to. Modern classification assigns a type of plant to each family as an example of that family’s characteristics as distinguishable from other families. The names of families end in "aceae." </p>
    </section>

    <!-- Genus Section -->
    <section class="plant-section my-3">
      <h2 class="section-title">Plant Genus</h2>
      <p class="section-text">
        This is the part of plant nomenclature that is the most familiar. For example, Papaver is the genus for Poppy. Plants in a genus are easily recognizable as belonging to the same group. The name of the genus should always be capitalized. Example: Red Poppy is Papaver rhoeas. </p>
    </section>

    <!-- Species Section -->
    <section class="plant-section my-3">
      <h2 class="section-title">Plant Species</h2>
      <p class="section-text">
        This is the level of classification that defines the individual plant. Here some aspects of the plant are more specifically defined — color, leaf shape, or place where or by whom it was discovered. The use of the genus and species names together always refer to only one plant. The species name is written after the genus and is never capitalized. Example: Rudbeckia hirta </p>
    </section>
  </article>

  <div class="image-container d-flex justify-content-center">
    <img src="img/banana.png" alt="Banana Classification" class="img-fluid rounded mx-2 styled-img">
    <img src="img/plantClassification.jpg" alt="Plant Image 2" class="img-fluid rounded mx-2 styled-img">
  </div>


  <article class="flex-grow-1 container mt-0 mb-3"> <!-- Use inline style for immediate testing -->
    <h1 class="text-center calssify_text">Example plant: Burseraceae</h1>

    <!-- Plant Family Section -->
    <section class="plant-section my-5">
      <div class="container">
        <div class="row">
          <!-- Left Side: Text -->
          <div class="col-md-7">
            <h2 class="section-title">Plant Family - Burseraceae</h2>
            <p class="section-text">
              The Burseraceae are a moderate-sized family of 17-19 genera and about 540 species of woody flowering plants. The Burseraceae are also known as the torchwood family, the frankincense and myrrh family, or simply the incense tree family.
            </p>
          </div>
          <!-- Right Side: Images -->
          <div class="col-md-5 d-flex justify-content-center align-items-center">
            <img src="img/contributeImg/Burseraceae_family1.JPG" alt="Plant Image 1" class="img-fluid rounded mx-2 plant-family-img">
            <img src="img/contributeImg/Burseraceae_family2.jpeg" alt="Plant Image 2" class="img-fluid rounded mx-2 plant-family-img">
            <img src="img/contributeImg/Burseraceae_family3.jpg" alt="Plant Image 3" class="img-fluid rounded mx-2 plant-family-img">
          </div>
        </div>
      </div>
    </section>

    <!-- Genus Section -->
    <section class="plant-section my-5">
      <div class="container">
        <div class="row">
          <!-- Left Side: Text -->
          <div class="col-md-7">
            <h2 class="section-title">Plant Genus - Dacryodes</h2>
            <p class="section-text">
              acryodes is a genus of about 60 species of trees in the family Burseraceae. The generic name is from the Greek dakruon meaning "tear(drop)", referring to how resin droplets form on the bark surface.
            </p>
          </div>
          <!-- Right Side: Images -->
          <div class="col-md-5 d-flex justify-content-center align-items-center">
            <img src="img/contributeImg/Dacryodes_genus1.jpeg" alt="Plant Image 1" class="img-fluid rounded mx-2 plant-family-img">
            <img src="img/contributeImg/Dacryodes_genus2.jpg" alt="Plant Image 2" class="img-fluid rounded mx-2 plant-family-img">
            <img src="img/contributeImg/Dacryodes_genus3.jpg" alt="Plant Image 3" class="img-fluid rounded mx-2 plant-family-img">
          </div>
        </div>
      </div>
    </section>

    <!-- Species Section -->
    <section class="plant-section my-5">
      <div class="container">
        <div class="row">
          <!-- Left Side: Text -->
          <div class="col-md-7">
            <h2 class="section-title">Plant Species - Dacryodes vahl</h2>
            <p class="section-text">
              Dacryodes Vahl. species, belonging to the Burseraceae family, are widely used in traditional medicine in tropical regions to treat a range of ailments including malaria, wounds, tonsillitis, and ringworms.
            </p>
          </div>
          <!-- Right Side: Images -->
          <div class="col-md-5 d-flex justify-content-center align-items-center">
            <img src="img/contributeImg/Dacryodes_Vahl_Species1.jpg" alt="Plant Image 1" class="img-fluid rounded mx-2 plant-family-img">
            <img src="img/contributeImg/Dacryodes_Vahl_Species2.jpg" alt="Plant Image 2" class="img-fluid rounded mx-2 plant-family-img">
            <img src="img/contributeImg/Dacryodes_Vahl_Species3.jpg" alt="Plant Image 3" class="img-fluid rounded mx-2 plant-family-img">
          </div>
        </div>
      </div>
    </section>
</article>
<?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>


</html>