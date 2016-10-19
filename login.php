<?php
  require_once('connectvars.php');

  // Start the session
  session_start();

  // Clear the error message
  $error_msg = "";

  // If the user isn't logged in, try to log them in
  if (!isset($_SESSION['login_id'])) {
    if (isset($_POST['submit'])) {
      // Connect to the database
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      // Grab the user-entered log-in data
      $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_username) && !empty($user_password)) {
        // Look up the username and password in the database
        $query = "SELECT personell_nr, login_id, zm_role FROM care_users WHERE login_id = '$user_username' AND password = MD5('$user_password')";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
          $row = mysqli_fetch_array($data);
          $_SESSION['personell_nr'] = $row['personell_nr'];
          $_SESSION['login_id'] = $row['login_id'];
          $_SESSION['zm_role'] = $row['zm_role'];
          setcookie('personell_nr', $row['personell_nr'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
          setcookie('login_id', $row['login_id'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
          setcookie('zm_role', $row['zm_role'], time() + (60 * 60 * 24 * 30)); // expires in 30 days
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/ziekmeld.php';
          header('Location: ' . $home_url);
        }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = 'Sorry, you must enter a valid username and password to log in.';
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = 'Sorry, you must enter your username and password to log in.';
      }
    }
  } else {
      $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/ziekmeld.php';
      header('Location: ' . $home_url);
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Ziekmelding Middenpolder | login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php
  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if (empty($_SESSION['personell_nr'])) {
    if ($error_msg) {echo '<div class="alert alert-danger">' . $error_msg . '</div>';}
?>
      <div class="container">
          <h3></h3>
          <form class="form-signin" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <img src="http://i1.kym-cdn.com/entries/icons/original/000/013/564/aP2dv.gif" alt="Doge meme" />
              <h2 class="form-signin-heading">Please sign in</h2>
              <input type="text" name="username" class="form-control" placeholder="Personeel nummer" value="<?php if (!empty($user_username)) echo $user_username; ?>" pattern="[0-9]+ ">
              <input type="password" name="password" class="form-control" placeholder="Wachtwoord" required>
              <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Sign in</button>
          </form>

      </div> <!-- /container -->

<?php
  }
  else {
    // Confirm the successful log-in
    echo('<p class="login">You are logged in as ' . $_SESSION['login_id'] . '.</p>');
  }
?>




  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="js/ie10-viewport-bug-workaround.js"></script>


</body>
</html>
