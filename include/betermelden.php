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
$personell_nr = $_SESSION['personell_nr'];
$query = "UPDATE ziekmeldingen SET beterdatum = NOW() WHERE personell_nr = $personell_nr AND beterdatum IS NULL";

mysqli_query($dbc, $query);

$query = "UPDATE care_users SET ziek = 0 WHERE personell_nr = $personell_nr";

mysqli_query($dbc, $query);

mysqli_close($dbc);

$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../ziekmeld.php';
echo $home_url;
header('Location: ' . $home_url);
?>