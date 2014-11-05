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
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT personell_nr, name, zm_role FROM care_users";
$data = mysqli_query($dbc, $query);

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

    <!-- dataTable scripts -->
    <link href="css/dataTables.bootstrap.css" rel="stylesheet">
    <script type="text/javascript" language="javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example tfoot th').each( function () {
                var title = $('#example thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" class="form-control" placeholder="'+title+'" />' );
            } );

            // DataTable
            var table = $('#example').DataTable( {
                "language": {
                    "sProcessing": "Bezig...",
                    "sLengthMenu": "_MENU_ resultaten weergeven",
                    "sZeroRecords": "Geen data om weer te geven",
                    "sInfo": "Resulaten _START_ tot _END_ van _TOTAL_ weergegeven",
                    "sInfoEmpty": "Geen data om weer te geven",
                    "sInfoFiltered": " (gefilterd uit _MAX_ regels)",
                    "sInfoPostFix": "",
                    "sSearch": "Zoeken:",
                    "sEmptyTable": "Geen gegevens aanwezig in de tabel",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Een moment geduld aub - bezig met laden...",
                    "oPaginate": {
                        "sFirst": "Eerste pagina",
                        "sLast": "Laatste pagina",
                        "sNext": "Volgende pagina",
                        "sPrevious": "Vorige pagina"
                    }
                }
            });

            // Apply the search
            table.columns().eq( 0 ).each( function ( colIdx ) {
                $( 'input', table.column( colIdx ).footer() ).on( 'keyup change', function () {
                    table
                        .column( colIdx )
                        .search( this.value )
                        .draw();
                } );
            } );
        } );
    </script>

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
            <?php if ($_SESSION['zm_role'] >= 2) { ?>
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

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Beheer gebruikers rollen</strong>
                        </div>
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="post" action="include/set_zmr.php">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="personell_nr" placeholder="Personeel Nummer">
                                </div>
                                <div class="form-group">
                                    <select name="zmr" class="form-control">
                                        <option value="1">Gebruiker</option>
                                        <option value="2">Personeels Zaken</option>
                                        <option value="3">Beheerder</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default">Veranderen</button>
                            </form>
                        </div>
                        <table class="table table-responsive table-striped table-hover" width=100%" cellspacing="0" id="example">
                            <thead>
                            <tr>
                                <th>Personeel nummer</th>
                                <th>Naam</th>
                                <th>Ziekmelding rol</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($data->num_rows > 0) {
                                // output data of each row
                                while ($row = $data->fetch_assoc()) {
                                    echo "<tr><td>" . $row["personell_nr"] . "</td><td>" . $row["name"] . "</td><td>" . $row["zm_role"] . "</td></tr>\n ";
                                }
                            } ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Personeel nummer</th>
                                <th>Naam</th>
                                <th>Ziekmelding rol</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
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

?>
<!-- Content -->

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
