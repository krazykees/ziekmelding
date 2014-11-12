<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 30-10-2014
 * Time: 20:36
 */
require_once('../connectvars.php');
require_once('functies.php');

session_start();

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$personell_nr = mysqli_real_escape_string($dbc, $_SESSION['personell_nr']);
$query = "INSERT INTO ziekmeldingen (personell_nr, ziekdatum) VALUES ($personell_nr, NOW())";

mysqli_query($dbc, $query);

$query = "UPDATE care_users SET ziek = 1 WHERE personell_nr = $personell_nr";

mysqli_query($dbc, $query);

mysqli_close($dbc);

$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../ziekmeld.php';
header('Location: ' . $home_url);
?>