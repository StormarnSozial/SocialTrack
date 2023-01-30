<?php
require_once "includes/require.php";
include_once "header.php";
if (empty($_SESSION["username"])) {
    header("location: ./");
    exit();
}
$team = "null";
if (!empty($_POST["team"])) {
    $team = $_POST["team"];
} elseif (!empty($_GET["team"])) {
    $team = $_GET["team"];
}
?>
<div class="main" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-auto-rows: minmax(auto, auto); justify-content: space-evenly; justify-items: center;">
    <a style="font-size: 1.3rem; width: fit-content;" href="profile.php?team=<?php echo $team?>" id="ov-events">Übersicht</a>
    <a style="font-size: 1.3rem; width: fit-content;" href="profile.php?team=<?php echo $team?>&stats" id="ov-stats">Statistiken</a>
    <a style="font-size: 1.3rem; width: fit-content;" href="profile.php?team=<?php echo $team?>&create" id="ov-create">Neue Stunden</a>
</div>
<?php
/**
 * @return void
 */
function errors(): void
{
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "dataadded") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten hinzugefügt!</p>";
        } elseif ($_GET["error"] == "error") {
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
            echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; border-radius: 7px; margin: 10px auto;'>Aktivität '" . $_GET["name"] . "' wurde aus der Datenbank gelöscht!</p>";
        } elseif ($_GET["error"] == "invalid") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Name darf folgende Zeichen nicht enthalten <br>'<' und '>'!</p>";
        }
    }
}

if ((!isset($_GET["data"]) || dataData(con(), $_GET["data"]) === false) && !isset($_GET["create"]) && !isset($_GET["stats"])) {
    ?>
    <script>
        let ovTab = document.getElementById("ov-events");
        ovTab.setAttribute("style", ovTab.getAttribute("style")+"; text-decoration: underline;")
    </script>
    <div class="main">
        <h1>Deine Aktivitäten</h1><br>
        <form action="profile.php" method="post">
            <button type="submit" name="submit" style="visibility: hidden; display: none" id="sbm_btn">Filter</button>
            <?php teamsListMember(con(), $_SESSION["username"], $team); ?>
            <script>
                let select = document.getElementById("teams");
                let sbm_btn = document.getElementById("sbm_btn");

                select.onchange = function () {
                    sbm_btn.click();
                }
            </script>
        </form>
        <br>
        <?php
        if ($team != "null") {
            $teamName = teamData(con(), $team)["name"];
            echo("<h1>" . $teamName . "</h1>");
        }
        ?>
        <table class="profile table" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
            <thead>
            <tr>
                <th>Name</th>
                <th>Team</th>
                <th>Dauer</th>
                <th>Datum</th>
                <th>Signiert</th>
            </tr>
            </thead>
            <tbody>
            <?php
            datas(con(), $_SESSION["username"], $team, false);
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><p style="visibility: hidden;">moin :)</p></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    Signiert:
                </td>
                <td><?php
                    echo(getAllLessonsCount(con(), $_SESSION["username"], $team) . " Stunden"); ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Nicht
                    Signiert:
                </td>
                <td><?php
                    echo((getAllLessonsCount(con(), $_SESSION["username"], $team, false) - getAllLessonsCount(con(), $_SESSION["username"], $team)) . " Stunden"); ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Alle:</td>
                <td><?php
                    echo(getAllLessonsCount(con(), $_SESSION["username"], $team, false) . " Stunden"); ?></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <form action="includes/downloaddata.inc.php" target="_blank" method="post">
            <button style="border: none; border-bottom: solid var(--dark-grey); border-radius: 0;" type="submit" name="submit">In Excel Datei exportieren</button>
            <input name="team" value="<?php echo $team?>" type="hidden">
        </form>
        <?php
        errors();
        ?>
    </div>

    <!--  <div class="main">
    <h2>Notes:</h2><br>
    <form action="includes/usermanager.inc.php" method="post">
      <textarea style="background: none" name="note" cols="100" rows="30"><?php /*echo(userData(con(), $_SESSION["username"])["note"]); */ ?></textarea><br><br>
      <button type="submit" name="savenote">Sichern</button>
    </form>
    <?php
    /*          if (isset($_GET["error"])) {
                if ($_GET["error"] == "notesaved") {
                  echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Saved Changes!</p>";
                } elseif ($_GET["error"] == "inboundoutofcharacter") {
                  echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Note cannot be longer than 2000 character!</p>";
                }
              }
        */ ?>
  </div>-->
    <?php
} elseif (isset($_GET["data"])) { ?>

    <div class="main">
        <br>
        <a href="profile.php?team=<?php echo $team?>" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
        <form action="includes/datamanager.inc.php" method="post">
            <input type="hidden" name="id" placeholder="ID..." value="<?php echo($_GET["data"]); ?>"><br>
            <?php
            $data = dataData(con(), $_GET["data"]);
            echo("<h1 style='font-size: 3rem;'>" . $data["name"] . "</h1>");
            echo '<h2>Aktuelle Werte:</h2>';
            echo '<p>Team: ' . teamData(con(), $data["team"])["name"] . '</p>';
            echo '<p>Stunden: ' . $data["lessons"] . '</p>';
            echo '<p>Datum: ' . $data["edate"] . '</p>';
            ?>
            <br>

            <label for="teams">Team: </label><br>
            <?php teamsListMember(con(), $_SESSION["username"], dataData(con(), $_GET["data"])["team"]); ?>
            <script>document.getElementById("teams").setAttribute("style", document.getElementById("teams").getAttribute("style")+";margin-top:0;")</script>

            <label for="e-name">Name: </label><br>
            <input id="e-name" type="text" style="margin-top: 0" name="name" placeholder="Name..."
                   value="<?php echo(dataData(con(), $_GET["data"])["name"]); ?>"><br>

            <label for="e-hours">Stunden: </label><br>
            <input id="e-hours" type="number" min="1" style="margin-top: 0" name="lessons" placeholder="Stunden..."
                   value="<?php echo(dataData(con(), $_GET["data"])["lessons"]); ?>"><br>

            <label for="e-date">Datum: </label><br>
            <input id="e-date" type="datetime-local" name="date" placeholder="Datum..." style="margin-top: 0"
                   value="<?php echo(dataData(con(), $_GET["data"])["edate"]); ?>"><br>

            <?php #<button type="submit" name="add">Hinzufügen</button><br><br>?>
            <button type="submit" name="edit">Bearbeiten</button>
            <br><br>
            <button type="submit" name="del">Löschen</button>
        </form>
        <?php
        errors();
        ?>
    </div>

    <?php
} elseif (isset($_GET["create"])) { ?>

    <div class="main">
        <script>
            let cTab = document.getElementById("ov-create");
            cTab.setAttribute("style", cTab.getAttribute("style")+"; text-decoration: underline;")
        </script>
        <br>
        <a href="profile.php?team=<?php echo $team?>" style='border: solid white; padding: 2px; border-radius: 5px; margin-top: 20px'>← Zurück</a>
        <form action="includes/datamanager.inc.php" method="post">
            <?php
            echo("<h1 style='font-size: 3rem; margin-top: 20px;'>Stunden hinzufügen</h1>");
            ?>
            <br>
            <label>Team:</label><br>
            <?php teamsListMember(con(), $_SESSION["username"], $team); ?>
            <label>Name:</label><br>
            <input type="text" name="name" placeholder="Name..."><br>
            <label>Stunden:</label><br>
            <input type="number" name="lessons" placeholder="Stunden..."><br>
            <label>Datum:</label><br>
            <input type="datetime-local" name="date" placeholder="Datum..." style="width: 250px;"><br>
            <button type="submit" name="add">Hinzufügen</button>
            <br><br>
        </form>
        <?php
        errors();
        ?>
    </div>

    <?php
} elseif (isset($_GET["stats"])) {?>
    <script>
        let cTab = document.getElementById("ov-stats");
        cTab.setAttribute("style", cTab.getAttribute("style")+"; text-decoration: underline;")
    </script>
    <h1 style="font-size: 3rem; margin-top: 30px;">Statistiken</h1>
    <div class="main" style="min-width: fit-content; padding: 10px; line-height: 1.5rem">
        <h2>Signiert</h2><br>
        <?php
        hourOverview(con(), $_SESSION["username"]);
        ?>
    </div>
    <div class="main" style="min-width: fit-content; padding: 10px; line-height: 1.5rem">
        <h2>Alle</h2><br>
        <?php
        hourOverview(con(), $_SESSION["username"], false);
        ?>
    </div>
<?php
}
?>
