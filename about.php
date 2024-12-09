<?php
session_name('Khant');
session_start();
?>
<?php include_once 'session_timeout.php';?>
<?php include_once 'head.php' ?>

<body>
    <?php include_once 'header.php' ?>
    <div class="container aboutContainer">
        <h1 class="text-center mb-4">About This Assignment</h1>

        <!-- PHP Version -->
        <h3>Libraries used in this Assignment</h3>
        <ol>
            <li><?php echo 'PHP Version: ' . phpversion(); ?></li>
            <li>FPDF - for generating pdf</li>
            <li>Plantnet API - for Identify page</li>
        </ol>


        <!-- Completed Tasks in List Format -->
        <h3>Tasks Completed</h3>
        <ul>
            <li>Task 1: Database and Table Creation with Dummy Data</li>
            <li>Task 2.1: Registration Page with Email Validation and Password Hashing</li>
            <li>Task 2.2: Login Page with Session Setup</li>
            <li>Task 3.1: Main Menu Page with Access Restrictions for User Type</li>
            <li>Task 3.2: View Plant Detail and Profile Pages with MySQL Integration</li>
            <li>Task 3.3: Update Profile Page with Image and Resume Uploads</li>
            <li>Task 3.4: Contribution Page for Adding Plants</li>
            <li>Task 4.1: Admin Main Menu with darker Navigation</li>
            <li>Task 4.2: Manage Users’ Account Page with CRUD Operations</li>
            <li>Task 4.3: Manage Plants Page with Approval Feature</li>
            <li>Task 5.1: Identify Page with Plant Information Retrieval</li>
            <li>Task 4.4: About Page</li>
        </ul>

        <h3>Tasks Not Attempted or Not Completed</h3>
        <ul>
            <li>None</li>
        </ul>

        <h3>Challenges Faced</h3>
        <ul>
            <li>Creating the Plant Identify page as a student have any knowledge about machine learning it is quite challenging to handle this.</li>
            <li>Managing database</li>
        </ul>

        <h3>Improvements for Next Time</h3>
        <ul>
            <li>Streamline validation across forms</li>
            <li>Enhance the code structure for scalability</li>
        </ul>

        <h3>Extension Features and Extra Challenges Attempted</h3>
        <ul>
            <li>Creating Identify page by using plantnet API </li>
            <li>Customized responsive design using Bootstrap classes</li>
        </ul>

        <!-- Video Presentation -->
        <h3>Video Presentation</h3>
        <p>Watch the video presentation showcasing all functionalities</p>
        <p><a href="https://youtu.be/oV3MjrTQ0fQ" target="_blank">Link to youtube video</a></p>


        <p><a href="index.php" class="btn btn-primary mt-3 mb-3">Return to Home Page</a></p>
    </div>
    <?php include_once 'footer.php' ?>
</body>

</html>