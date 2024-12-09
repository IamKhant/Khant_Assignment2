<?php
session_name('Khant');
session_start();

// Redirect if the user is already logged in
if ($_SESSION['type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$errors = [];
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include 'head.php' ?>

<body class="bg-light">
    <?php include 'header.php' ?>
    <main class="container mt-5" id="user_register_body">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Add New User</h2>
                        <form action="process_registration.php" method="POST" id="registrationForm">
                            <div class="row">
                                <!-- First Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control">
                                    <?php if (isset($errors['first_name'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['first_name']); ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control">
                                    <?php if (isset($errors['last_name'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['last_name']); ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Student ID -->
                                <div class="col-md-6 mb-3">
                                    <label for="student_id" class="form-label">Student ID</label>
                                    <input type="text" id="student_id" name="student_id" class="form-control">
                                    <?php if (isset($errors['student_id'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['student_id']); ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6 mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="date" id="dob" name="dob" class="form-control">
                                    <?php if (isset($errors['dob'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['dob']); ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="gender_male" name="gender" value="Male">
                                        <label class="form-check-label" for="gender_male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="gender_female" name="gender" value="Female" checked>
                                        <label class="form-check-label" for="gender_female">Female</label>
                                    </div>
                                    <?php if (isset($errors['gender'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['gender']); ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control">
                                    <?php if (isset($errors['email'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['email']); ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Hometown (Full Width) -->
                                <div class="col-6 mb-3">
                                    <label for="hometown" class="form-label">Hometown</label>
                                    <input type="text" id="hometown" name="hometown" class="form-control">
                                    <?php if (isset($errors['hometown'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['hometown']); ?></small>
                                    <?php endif; ?>
                                </div>
                                <!-- Contact Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" id="contact_number" name="contact_number" class="form-control">
                                    <?php if (isset($errors['contact_number'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['contact_number']); ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control">
                                    <?php if (isset($errors['password'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['password']); ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <small class="text-danger"><?php echo htmlspecialchars($errors['confirm_password']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-center mt-4">
                                <button type="submit" class="btn btn-success mx-2">Add</button>
                                <button type="button" class="btn btn-secondary" onclick="resetregisterForm()">Reset</button>
                                <button type="button" onclick="window.location.href='manage_accounts.php';" class="btn btn-primary mx-2">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php' ?>
</body>

</html>