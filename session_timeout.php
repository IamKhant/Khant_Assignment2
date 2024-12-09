<?php

$timeout_duration = 1800;

// Check if the session has timed out
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {

    session_unset(); 
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();
?>