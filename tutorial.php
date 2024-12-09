<?php
session_name('Khant'); 
session_start();
if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}


include_once 'session_timeout.php'; // Include session timeout logic
include_once 'head.php'; ?>

<body id="tutorialBody">
    <?php include_once 'header.php'; ?>

    <main class="py-5" id="TutorialBody">
        <div class="container">
            <h1 class="text-center mb-4 display-4">How to Make Herbarium Specimens</h1>

            <!-- Introduction Section -->
            <section class="mb-5">
                <p class="lead text-justify">
                    Herbarium specimens are preserved plant samples representing various species, used for research, education, and historical records. Follow the guide below to collect, prepare, and preserve these specimens effectively.
                </p>
                <div class="text-center">
                    <img src="img/tutoIntro.jpg" alt="Herbarium specimen example" class="img-fluid mb-3" id="tutoDemoImg">
                </div>
                <h2 class="h4">Warning!</h2>
                <p class="lead">Ensure you have permission to collect plants from any location.</p>
            </section>

            <!-- Tools Section -->
            <section class="mb-5">
                <h2 class="h3">Tools You Might Need</h2>
                <p class="lead">Before starting, gather these tools to help in preparing herbarium specimens:</p>
                <div class="container">
                    <div class="row">
                        <!-- Left -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <img src="img/bag.jpg" alt="Bags" class="tool-img mr-3">
                                <span>Bags to store specimens</span>
                            </div>
                        </div>
                        <!-- Right -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <span>Clippers for cutting woody stems</span>
                                <img src="img/clipper.jpg" alt="Clippers" class="tool-img ml-3">
                            </div>
                        </div>
                        <!-- Left -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <img src="img/shovel.jpg" alt="Trowel" class="tool-img mr-3">
                                <span>Trowel for digging up roots</span>
                            </div>
                        </div>
                        <!-- Right -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <span>Straps and cupboards for pressing and drying</span>
                                <img src="img/straps.jpg" alt="Straps" class="tool-img ml-3">
                            </div>
                        </div>
                        <!-- Left -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <img src="img/tutoIntro1.jpg" alt="Mounting Paper" class="tool-img mr-3">
                                <span>Mounting paper for displaying specimens</span>
                            </div>
                        </div>
                        <!-- Right -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <span>Wax paper for mounting plants</span>
                                <img src="img/waxPaper.webp" alt="Wax Paper" class="tool-img ml-3">
                            </div>
                        </div>
                        <!-- Left -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <img src="img/glue.jpeg" alt="Glue" class="tool-img mr-3">
                                <span>Glue or paper tape for attaching specimens</span>
                            </div>
                        </div>
                        <!-- Right -->
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <span>HairDryer to dry the specimen faster(optional)</span>
                                <img src="img/hairdryer.jpg" alt="Wax Paper" class="tool-img ml-3">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <h1 class="titleh1">Steps to transfer a leaf into a herbarium specimen</h1>
            <!-- Collecting Section -->
            <section class="mb-5 spetsImg">
                <h2 class="h3">Step 1: Collecting Plant Samples</h2>
                <p class="lead">Choose healthy plant specimens, including leaves, flowers, and stems. Record the location, date, and other details in your notebook for proper labeling later.</p>
                <div class="text-center">
                    <img src="img/recording.jpg" alt="Collecting plants" class="img-fluid">
                </div>
            </section>

            <!-- Pressing and Drying Section -->
            <section class="mb-5 spetsImg">
                <h2 class="h3">Step 2: Pressing and Drying</h2>
                <p class="lead">Place the plant specimen between paper towels and cardboard. Press the specimen under a heavy weight or tighten it with screws. Let the plant dry for two weeks.</p>
                <div class="text-center">
                    <img src="img/pressinganddrying.jpg" alt="Pressing and drying plants" class="img-fluid">
                </div>
            </section>

            <!-- Mounting Section -->
            <section class="mb-5 spetsImg">
                <h2 class="h3">Step 3: Mounting</h2>
                <p class="lead">Once dried, mount the specimen onto acid-free paper using glue or paper tape. Be careful not to damage the fragile plant material during the mounting process.</p>
                <div class="text-center">
                    <img src="img/gluing.JPG" alt="Mounting herbarium specimens" class="img-fluid">
                </div>
            </section>

            <!-- Labeling Section -->
            <section class="mb-5 spetsImg">
                <h2 class="h3">Step 4: Labeling</h2>
                <p class="lead">Label the specimen with important information like plant species, collection date, and location. Proper labeling is essential for research and reference purposes.</p>
                <div class="text-center">
                    <img src="img/labeling.jpeg" alt="Labeling herbarium specimens" class="img-fluid">
                </div>
            </section>

            <!-- Preservation Section -->
            <section class="mb-5 spetsImg">
                <h1 class="titleh1">How to Preserve Herbarium Specimens</h1>
                <p class="lead">After pressing and drying, follow these steps for long-term preservation:</p>
                <ul class="list-unstyled lead">
                    <li>Store specimens in acid-free paper or envelopes.</li>
                    <li>Keep specimens in a cool, dry, dark place to avoid degradation.</li>
                    <li>Regularly check for pests or mold, and treat if necessary.</li>
                </ul>
                <div class="text-center">
                    <img src="img/preserve.webp" alt="Preserving herbarium specimens" class="img-fluid">
                </div>
            </section>
        </div>
    </main>
    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop">
        &#8679; <!-- Up arrow -->
    </a>
    <?php include_once 'footer.php'; ?>
</body>

</html>