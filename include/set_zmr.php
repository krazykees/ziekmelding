<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 2-11-2014
 * Time: 15:13
 */
session_start();
require_once('../connectvars.php');
require_once('functies.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$zmr = $_POST['zmr'];
$personell_nr = $_POST['personell_nr'];
$query = "UPDATE care_users SET zm_role = $zmr  WHERE personell_nr = $personell_nr";

mysqli_query($dbc, $query);

mysqli_close($dbc);

$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../manage.php';
header('Location: ' . $home_url);