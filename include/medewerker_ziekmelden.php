<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 5-11-2014
 * Time: 18:31
 */
require_once('../connectvars.php');
require_once('functies.php');

session_start();
sessie_verlopen();


if (isset($_SESSION['personell_nr']) && ($_SESSION['zm_role'] == 2 || $_SESSION['zm_role'] >= 4)) {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $user = mysqli_real_escape_string($dbc, $_GET['id']);
    $query = "SELECT ziek FROM care_users WHERE personell_nr = $user";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);

    if ($row['ziek'] == 1) {
        $query = "UPDATE care_users SET ziek = 0 WHERE personell_nr = $user";
        mysqli_query($dbc, $query);

        $query = "UPDATE ziekmeldingen SET beterdatum = NOW() WHERE personell_nr = $user AND beterdatum IS NULL";
        mysqli_query($dbc, $query);

    } else {
        $query = "UPDATE care_users SET ziek = 1 WHERE personell_nr = $user";
        mysqli_query($dbc, $query);

        $query = "INSERT INTO ziekmeldingen (personell_nr, ziekdatum) VALUES ($user, NOW())";
        mysqli_query($dbc, $query);
    }

    mysqli_close($dbc);
}
$ref = $_GET['ref'];
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../' . $ref . '.php';
header('Location: ' . $home_url);