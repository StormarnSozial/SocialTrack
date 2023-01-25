<?php
require_once "includes/require.php";
if (empty($_SESSION["username"])) {
    header("location: ./");
    exit();
}
if (getUserPower(con(), $_SESSION["username"]) < 40) {
    header("location: ./?error=noperm");
    exit();
}
if (isset($_GET["resetfilter"])) {
    unset($_SESSION["fuser"]);
    unset($_SESSION["fteam"]);
    header("location: ./datacenter.php");
    exit();
}
if (getUserPower(con(), $_SESSION["username"]) >= 50) {
    if (isset($_POST["user"])) {
        $fuser = $_POST["user"];
        $_SESSION["fuser"] = $_POST["user"];
    } elseif (isset($_GET["user"])) {
        $fuser = $_GET["user"];
        $_SESSION["fuser"] = $_GET["user"];
    } elseif (isset($_SESSION["fuser"])) {
        $fuser = $_SESSION["fuser"];
    }
}
if (isset($_POST["team"])) {
    $fteam = $_POST["team"];
    $_SESSION["fteam"] = $_POST["team"];
} elseif (isset($_GET["team"])) {
    $fteam = $_GET["team"];
    $_SESSION["fteam"] = $_GET["team"];
} elseif (isset($_SESSION["fteam"])) {
    $fteam = $_SESSION["fteam"];
} elseif (isset($fuser)) {
    $fteam = "null";
}
if (getUserPower(con(), $_SESSION["username"]) < 50 && !isTeamLeaderOfTeam(con(), $_SESSION["username"], $fteam)) {
    $fteam = "null";
}
include_once "header.php";
?>
<?php
if (getUserPower(con(), $_SESSION["username"]) > 50) { ?>
    <div class="main">
        <form action="datacenter.php">
            <button type='submit' name='page' value="events">Aktivitäten</button>
            <button type='submit' name='page' value="summary">Zusammenfassung</button>
        </form>
    </div>
    <?php
}
if (!isset($_GET["page"]) || $_GET["page"] == "events" || getUserPower(con(), $_SESSION["username"]) < 50) {
    if (!isset($_GET["data"]) || dataData(con(), $_GET["data"]) === false) {
        ?>
        <div class="main">
            <h1>Alle Aktivitäten</h1>
            <form action="datacenter.php">
                <?php
                if (getUserPower(con(), $_SESSION["username"]) < 50) {
                    teamsListLeader(con());
                } else {
                    teamsList(con());
                    userList(con());
                }
                ?>
                <button type="submit" name="submit" style="display: none" id="filter">Filtern</button>
                <script>
                    let teamSel = document.getElementById("teams");
                    let userSel = document.getElementById("users");
                    let filter_btn = document.getElementById("filter");

                    teamSel.onchange = function () {
                        filter_btn.click();
                    }
                    userSel.onchange = function () {
                        filter_btn.click();
                    }
                </script>
                <?php
                if (isset($fteam)) {
                    echo '
          <button type="submit" name="resetfilter" style="margin-top: 5px;">Filter Zurücksetzen</button>';
                }
                ?>
            </form>
            <br>
            <?php
            if (isset($fuser) && $fuser != "null") {
                $userName = userData(con(), $fuser)["fullname"];
                echo("<h1>" . $userName . "</h1>");
            }
            if (isset($fteam) && $fteam != "null") {
                $teamName = teamData(con(), $fteam)["name"];
                echo("<h1>" . $teamName . "</h1>");
            }
            ?>
            <table class="profile table"
                   style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
                <thead>
                <tr>
                    <?php
                    if (!(isset($fuser) && $fuser != "null")) { ?>
                        <th>Benutzer</th>
                        <?php
                    } ?>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Dauer</th>
                    <th>Datum</th>
                    <th>Signiert</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($fuser) && $fuser != "null") {
                    datas(con(), $fuser, $fteam, true);
                } elseif (isset($fteam)) {
                    $team = "null";
                    if (!empty($fteam)) {
                        $team = $fteam;
                    }
                    teamDatas(con(), $team);
                }

                ?>
                <script>
                    function httpGet(theUrl) {
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
                        xmlHttp.send( null );
                        return xmlHttp.responseText;
                    }

                    function sign(btn) {
                        httpGet("includes/datamanager.inc.php?sign="+btn.getAttribute("value"))
                        btn.setAttribute("style", "border: none; padding: 0; margin: 0; width: fit-content; height: fit-content; font-size: 16px; border-bottom: 1px solid white; border-radius: 0")
                        btn.textContent = document.getElementById("own_name").textContent;
                        btn.onclick = function () {unsign(btn)};
                        console.log("Signed "+btn.getAttribute("value"))
                    }

                    function unsign(btn) {
                        httpGet("includes/datamanager.inc.php?unsign="+btn.getAttribute("value"))
                        btn.setAttribute("style", "border: none; padding: 0; margin: 0; color: lime; width: fit-content; height: fit-content;")
                        btn.textContent = "Signieren"
                        btn.onclick = function () {sign(btn)};
                        console.log("Unsigned "+btn.getAttribute("value"))
                    }

                    let sign_buttons = document.getElementsByClassName("sign_btn");
                    let unsign_buttons = document.getElementsByClassName("unsign_btn");

                    for (let sign_btn of sign_buttons) sign_btn.onclick = function () {sign(sign_btn)};
                    for (let unsign_btn of unsign_buttons) {
                        unsign_btn.onclick = function () {unsign(unsign_btn)}
                    }
                </script>
                </tbody>
            </table>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "error") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
                } elseif ($_GET["error"] == "emptyf") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in every field!";
                } elseif ($_GET["error"] == "dataedited") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten bearbeitet!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; border-radius: 7px; margin: 10px auto;'>Event '" . $_GET["name"] . "' wurde bearbeitet!</p>";
                } elseif ($_GET["error"] == "eerror") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
                } elseif ($_GET["error"] == "eemptyf") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtiges Feld war leer!";
                } elseif ($_GET["error"] == "deldata") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten vernichtet!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; border-radius: 7px; margin: 10px auto;'>Event '" . $_GET["name"] . "' wurde aus der Datenbank gelöscht!</p>";
                } elseif ($_GET["error"] == "invalid") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Name darf folgende Zeichen nicht enthalten: <br>'<' and '>'!</p>";
                }
            }
            ?>
        </div>
        <?php
    } else { ?>

        <div class="main">
            <?php
            $filters = "";
            if (!empty($_SESSION["fteam"])) {
                $filters = "?fteam=" . $_SESSION["fteam"];
            }
            if (!empty($_SESSION["fuser"])) {
                $filters = $filters . "&fuser=" . $_SESSION["fuser"];
            }
            ?>
            <a href="datacenter.php<?php echo($filters); ?>"
               style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
            <form action="includes/datamanager.inc.php" method="post">
                <input type="hidden" name="id" placeholder="ID..." value="<?php echo($_GET["data"]); ?>"><br>
                <?php
                $data = dataData(con(), $_GET["data"]);
                echo("<h1 style='font-size: 3rem;'>" . $data["name"] . "</h1>");
                echo '<h2>von ' . userData(con(), $data["account"])["fullname"] . '</h2><br>';
                echo '<h2>Aktuelle Werte:</h2>';
                echo '<p>Team: ' . teamData(con(), $data["team"])["name"] . '</p>';
                echo '<p>Stunden: ' . $data["lessons"] . '</p>';
                echo '<p>Datum: ' . $data["edate"] . '</p>';
                ?>
                <br>

                <label for="e-name">Name: </label><br>
                <input type="text" style="margin-top: 0" name="name" placeholder="Name..."
                       value="<?php echo(dataData(con(), $_GET["data"])["name"]); ?>"><br>

                <label>Stunden:</label><br>
                <input type="number" style="margin-top: 0" name="lessons" placeholder="Stunden..."
                       value="<?php echo(dataData(con(), $_GET["data"])["lessons"]); ?>"><br>

                <label>Datum:</label><br>
                <input type="datetime-local" name="date" placeholder="Datum..." style="margin-top: 0"
                       value="<?php echo(dataData(con(), $_GET["data"])["edate"]); ?>"><br>

                <?php #<button type="submit" name="add">Hinzufügen</button><br><br>?>
                <button type="submit" name="edit" value="datac">Bearbeiten</button>
                <br><br>
                <button type="submit" name="del" value="datac">Löschen</button>
            </form>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "error") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Interner Fehler!";
                } elseif ($_GET["error"] == "emptyf") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Bitte alle Felder füllen!";
                } elseif ($_GET["error"] == "dataedited") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten bearbeitet!</p>";
                } elseif ($_GET["error"] == "eerror") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Interner Fehler!";
                } elseif ($_GET["error"] == "eemptyf") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtiges Feld war leer!";
                } elseif ($_GET["error"] == "deldata") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten gelöscht!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px;'>Aktivität '" . $_GET["name"] . "' wurde aus der Datenbank gelöscht!</p>";
                } elseif ($_GET["error"] == "invalid") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Name darf folgende Zeichen nicht enthalten: <br>'<' and '>'!</p>";
                }
            }
            ?>
        </div>
        <?php
    }
} else if ($_GET["page"] == "summary") { ?>

    <div class="main">
        <h1>Komplette Zusammenfassung</h1>
        <?php
        usersOverview(con());
        ?>
    </div>

    <?php
} else {
    echo "<h2>Huch, wie bist du denn hier hingekommen? <br> <a style='color: lime' href='datacenter.php'>Hier geht's zurück -></a></h2>";
}?>