<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 30-10-2014
 * Time: 21:16
 */

function set_ziek($personell_nr) {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $query = "SELECT ziek FROM care_users WHERE personell_nr = $personell_nr";

    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    $_SESSION['ziek'] = $row['ziek'];

    mysqli_close($dbc);

}

function user_role($zm_role) {
    if ($zm_role == 1) {
        echo "Gebruiker";
        return;
    } elseif ($zm_role == 2) {
        echo "Manager";
        return;
    } elseif ($zm_role == 3) {
        echo "Beheerder";
        return;
    } else {
        echo "Geen functie";
        return;
    }
}

function aantal_zieken() {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $query = "SELECT * FROM ziekmeldingen WHERE beterdatum IS NULL";
    $result = mysqli_query($dbc, $query);

    echo mysqli_num_rows($result);
    mysqli_close($dbc);
}

function laatste_x_ziek() {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $personell_nr = mysqli_real_escape_string($dbc, $_SESSION['personell_nr']);

    $query = "SELECT beterdatum, ziekdatum FROM ziekmeldingen WHERE personell_nr = $personell_nr ORDER BY ziekmelding_id DESC LIMIT 1";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);

    $ziekdatum = strtotime($row['ziekdatum']);
    $beterdatum = strtotime($row['beterdatum']);

    if (mysqli_num_rows($result) == 1) {
        echo date('d F Y', $ziekdatum) . ' tot ' . date('d F Y', $beterdatum) . '.';
    } else {
        echo 'nooit!';
    }
    
    mysqli_close($dbc);
}

function zieken() {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $query = "SELECT ziekmeldingen.personell_nr, ziekmeldingen.ziekdatum, care_users.name FROM ziekmeldingen INNER JOIN care_users ON ziekmeldingen.personell_nr = care_users.personell_nr WHERE ziekmeldingen.beterdatum IS NULL";
    $result = mysqli_query($dbc, $query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            //echo "<tr><td>" . $row["personell_nr"] . "</td><td>" . $row["name"] . "</td><td>" . substr($row["ziekdatum"], 0, 10) . "</td></tr>\n ";
            echo "<tr>";
            echo "<td>", $row['personell_nr'], "</td>";
            echo "<td>", $row['name'], "</td>";
            echo "<td>", $row['ziekdatum'], "</td>";
            echo '<td>';
            echo '<a class="btn btn-success" href="include/medewerker_ziekmelden.php?id='.$row['personell_nr'].'&ref=report">Beter melden</a>';
            echo '</td>';
            echo '</tr>';
        }
    }
}

function history() {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $query = "SELECT ziekmeldingen.ziekmelding_id, ziekmeldingen.ziekdatum, ziekmeldingen.beterdatum, ziekmeldingen.personell_nr, care_users.name FROM ziekmeldingen INNER JOIN care_users ON ziekmeldingen.personell_nr = care_users.personell_nr";
    $result = mysqli_query($dbc, $query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            //echo "<tr><td>" . $row["ziekmelding_id"] . "</td><td>" . $row["personell_nr"] . "</td><td>" . $row["name"] . "</td><td>" . $row["ziekdatum"] . "</td><td>" . $row["beterdatum"] . "</td></tr>\n ";
            echo '<tr>';
            echo '<td>', $row['ziekmelding_id'], '</td>';
            echo '<td>', $row['personell_nr'], '</td>';
            echo '<td>', $row['name'], '</td>';
            echo '<td>', $row['ziekdatum'], '</td>';
            echo '<td>', $row['beterdatum'], '</td>';
            echo '</tr>';
        }
    }

}

function ziek_sinds() {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $personell_nr = mysqli_real_escape_string($dbc, $_SESSION['personell_nr']);

    $query = "SELECT ziekdatum FROM ziekmeldingen WHERE personell_nr = $personell_nr";
    $result = mysqli_query($dbc, $query);

    $row = mysqli_fetch_array($result);
    echo substr($row['ziekdatum'], 0, 10);
}

function afdeling_nr2txt($nr) {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $query = "SELECT name_formal FROM care_department WHERE nr = $nr LIMIT 1";
    $result = mysqli_query($dbc, $query);

    $row = mysqli_fetch_array($result);
    return $row['name_formal'];
}

function personeel() {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $regex = '/"([^"]+)"/';

    // $query = "SELECT care_users.personell_nr, care_users.name, care_users.ziek, care_users.dept_nr FROM care_users INNER JOIN care_department ON care_department.nr = care_users.dept_nr REGEXP \'/\"([^\"]+)\"/\'";";
    $query = "SELECT care_users.personell_nr, care_users.name, care_users.ziek, care_users.dept_nr FROM care_users";
    $result = mysqli_query($dbc, $query);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            preg_match('/"([^"]+)"/', $row['dept_nr'], $afdeling);
            $afdeling_naam = afdeling_nr2txt($afdeling[1]);
            //echo "<tr><td>" . $row["personell_nr"] . "</td><td>" . $row["name"] . "</td><td>" . $row["ziek"] . "</td><td>" . $afdeling_naam . "</td></tr>\n ";
            echo '<tr>';
            echo "<td>", $row['personell_nr'], '</td>';
            echo "<td>", $row['name'], '</td>';
            echo "<td>", $row['ziek'], '</td>';
            echo "<td>", $afdeling_naam, '</td>';
            echo '<td>';
            //echo '<a class="btn btn-primary btn-lg" href="read.php?id='.$row['personell_nr'].'">Bekijk</a>';
            //echo ' ';
            if ($row['ziek'] == 1) {
                echo '<a class="btn btn-success btn-block" href="include/medewerker_ziekmelden.php?id='.$row['personell_nr'].'&ref=medewerkerziek">Beter melden</a>';
            } else {
                echo '<a class="btn btn-danger btn-block" href="include/medewerker_ziekmelden.php?id=' . $row['personell_nr'] . '&ref=medewerkerziek">Ziek melden</a>';
            }
            echo '</td>';
            echo '</tr>', "\n";
        }
    }
}

function ziek_veranderen() {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $personell_nr = mysqli_real_escape_string($dbc, $_SESSION['personell_nr']);

    $query = "SELECT ziekdatum FROM ziekmeldingen WHERE personell_nr = $personell_nr";
    $result = mysqli_query($dbc, $query);

    $row = mysqli_fetch_array($result);
    echo substr($row['ziekdatum'], 0, 10);
}