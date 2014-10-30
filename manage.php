<?php
session_start();

// If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['personell_nr'])) {
    if (isset($_COOKIE['personell_nr']) && isset($_COOKIE['login_id'])) {
        $_SESSION['personell_nr'] = $_COOKIE['personell_nr'];
        $_SESSION['login_id'] = $_COOKIE['login_id'];
        $_SESSION['zm_role'] = $_COOKIE['zm_role'];
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">

    <title>MiddenPolder - Ziekmelden</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sidebar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<?php
require_once('appvars.php');
require_once('connectvars.php');

if (isset($_SESSION['login_id']) && ($_SESSION['zm_role']) >= 2) {
?>

<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <p>MiddenPolder</p>
            </li>
            <li>
                <a href="ziekmeld.php"><span class="glyphicon glyphicon-user"></span> Ziekmelden</a>
            </li>
            <?php if ($_SESSION['personell_nr'] >= 2) { ?>
                <li>
                    <a href="report.php"><span class="glyphicon glyphicon-list-alt"></span> Rapportage</a>
                </li>
            <?php
            }
            if ($_SESSION['personell_nr'] >= 3) { ?>
                <li>
                    <a href="manage.php"><span class="glyphicon glyphicon-cog"></span> Beheer</a>
                </li>
            <?php
            }
            ?>
            <li>
                <a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Afmelden</a>
            </li>

        </ul>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">

                <a class="navbar-brand" id="menu-toggle" href="#menu-toggle"><span class="glyphicon glyphicon-th-list"></span></a>
            </div>
        </div><!-- /.container-fluid -->
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Ziekmelden</h1>
                    <p>User Role ziekmelding: <?php echo $_SESSION['zm_role'];?></p>
                    <p>Personeel nummer: <?php echo $_SESSION['personell_nr'];?></p>
                    <p><?php echo print_r($_SESSION); ?></p>

                    <!--<a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

</div>

<?php
}
else {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
    header('Location: ' . $home_url);
    echo '<a href="login.php">Log In</a><br />';
    echo '<a href="signup.php">Sign Up</a>';
}

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

mysqli_close($dbc);
?>
<!-- Content -->

<!-- jQuery Version 1.11.0 -->
<script src="js/jquery-1.11.1.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>

</body>
</html>
