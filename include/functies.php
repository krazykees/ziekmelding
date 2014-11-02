<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 30-10-2014
 * Time: 21:16
 */

function set_ziek($personell_nr) {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    //$query = "SELECT beterdatum FROM ziekmeldingen WHERE personell_nr = $personell_nr AND beterdatum is NULL";

    //mysqli_query($dbc, $query);

    $query = "SELECT ziek FROM care_users WHERE personell_nr = $personell_nr";

    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    $_SESSION['ziek'] = $row['ziek'];

    mysqli_close($dbc);

}

function user_role() {
    if (isset($_SESSION['zm_role'])) {
        if ($_SESSION['zm_role'] == 1) {
            echo "Gebruiker";
        } elseif ($_SESSION['zm_role'] == 2) {
            echo "Manager";
        } elseif ($_SESSION['zm_role'] == 3) {
            echo "Beheerder";
        }
    } else {
        echo "Geen functie";
    }
}