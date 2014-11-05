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
require_once('connectvars.php');
require_once('include/functies.php');

if (isset($_SESSION['login_id'])) {
    set_ziek($_SESSION['personell_nr']);
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

    <?php if ($_SESSION['zm_role'] >= 2) { ?>
    <!-- Custom styles for this template -->
    <link href="css/sidebar.css" rel="stylesheet">
    <?php } ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<?php
if (isset($_SESSION['login_id'])) {
?>

<div id="wrapper">
    <?php if ($_SESSION['zm_role'] >= 2) { ?>
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <p>MiddenPolder</p>
            </li>
            <li>
                <a href="ziekmeld.php"><span class="glyphicon glyphicon-user"></span> Ziek melden</a>
                <?php if ($_SESSION['zm_role'] ==2 || $_SESSION['zm_role'] >= 4) { ?>
                    <ul>
                        <li>
                            <a href="medewerkerziek.php">Medewerkers Ziek melden</a>
                        </li>
                    </ul>
            </li>
                <li>
                    <a href="report.php"><span class="glyphicon glyphicon-list-alt"></span> Rapportage</a>
                </li>
            <?php
            }
            if ($_SESSION['zm_role'] >= 3) { ?>
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
    <?php } ?>
    <?php if ($_SESSION['zm_role'] == 1) { echo "<br><br>"; } ?>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <p class="text-center"><strong>Hallo, <?php echo $_SESSION['name']; ?></strong></p>
                    <?php if ($_SESSION['ziek'] == '0') { ?>
                    <form class="form-ziekmelden" role="form" method="post" action="include/ziekmelden.php">
                        <button class="btn btn-primary btn-lg btn-block">Ziek melden</button>
                    </form>
                    <?php
                    } else { ?>
                        <form class="form-ziekmelden" role="form" method="post" action="include/betermelden.php">
                            <button class="btn btn-warning btn-lg btn-block">Beter melden</button>
                        </form>
                    <?php } ?>
                    <br>
                    <form class="form-ziekmelden" role="form" method="post" action="logout.php">
                        <button class="btn btn-danger btn-lg btn-block">Afmelden</button>
                    </form>
                    <br>
                    <?php if ($_SESSION['ziek'] == 1) { ?>
                        <div class="panel panel-collapse">
                            <p class="text-primary text-center">U bent ziek sinds: <?php ziek_sinds(); ?></p>
                        </div>
                    <?php } else {?>
                        <div class="panel panel-collapse">
                            <p class="text-primary text-center">U was voor het laatst ziek in de periode: <?php laatste_x_ziek(); ?></p>
                        </div>
                    <?php }?>
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
}
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
