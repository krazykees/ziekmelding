<?php
session_start();

// If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['personell_nr'])) {
    if (isset($_COOKIE['personell_nr']) && isset($_COOKIE['login_id'])) {
        $_SESSION['personell_nr'] = $_COOKIE['personell_nr'];
        $_SESSION['login_id'] = $_COOKIE['login_id'];
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mismatch - Where opposites attract!</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<h3>Mismatch - Where opposites attract!</h3>

<?php
require_once('appvars.php');
require_once('connectvars.php');

// Generate the navigation menu
if (isset($_SESSION['login_id'])) {
    echo '&#10084; <a href="viewprofile.php">View Profile</a><br />';
    echo '&#10084; <a href="editprofile.php">Edit Profile</a><br />';
    echo '&#10084; <a href="logout.php">Log Out (' . $_SESSION['login_id'] . ')</a>';
}
else {
    echo '&#10084; <a href="login.php">Log In</a><br />';
    echo '&#10084; <a href="signup.php">Sign Up</a>';
}

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

mysqli_close($dbc);
?>

</body>
</html>
