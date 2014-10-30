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
  <title>Mismatch - View Profile</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
</body> 
</html>
