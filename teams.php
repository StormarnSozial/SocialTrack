<?php
  include_once "header.php";
  if (empty($_SESSION["username"])) {
    header("location: ./");
    exit();
  }
  if (!isTeamLeader($con, $_SESSION["username"]) && getUserPower($con, $_SESSION["username"]) < 40) {
    header("location: ./?error=noperm");
    exit();
  }
  if (isset($_GET["team"])) {
      $team = $_GET["team"];
  } elseif (isset($_POST["team"])) {
      $team = $_POST["team"];
  }
  if (isset($team) && !isTeamLeaderOfTeam($con, $_SESSION["username"], $team) && getUserPower($con, $_SESSION["username"]) < 80 && (!isset($_GET["page"]) || $_GET["page"] != "requests")) {
      header("location: ./teams.php");
      exit();
  }
?>
    <script type="text/javascript">
    document.getElementById("teams").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
    </script>

    
    <div class="main">  
      <form action="includes/teams.inc.php" method='post'>
        <button type='submit' name='dash'>Deine Teams</button>
        <button type='submit' name='requests'>Deine Team Anfragen</button>
      </form>
    </div>

<?php
if (!isset($_GET["page"]) || $_GET["page"] == "dash") {
?>
<?php 
if (isset($_GET["error"])) {
    if ($_GET["error"] == "modstatus") {
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Moderator-Status wurde für '".$_GET["user"]."' geändert!</p>";
    } elseif ($_GET["error"] == "emptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wähle bitte einen Benutzer!</p>";
    } elseif ($_GET["error"] == "memberstatus") {
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Mitglied-Status wurde für '".$_GET["user"]."' geändert!</p>";
    }
}
?>
    <div class="main">
    <h1>Teams</h1>
    <form action="teams.php" method="post">
        <button type="submit" name="submit">Filter</button>
        <?php 
            if (getUserPower($con, $_SESSION["username"]) < 80) {
                teamsListLeader($con); 
            } else {
                teamsList($con);
            }
        ?>
    </form>
    <?php 
        if (isset($team) && $team != "null") {
            $teamName = teamData($con, $team)["name"];
            echo("<p>Gefiltert für team: '".$teamName."'</p>");
        }
    ?>
        <?php
            if (!isset($team) || $team == "null") {
                echo("<p>Wähle bitte ein Team!</p>");
            } else {
                if (teamTable($con, $team) === false) {
                    echo "<br><p style='color: red;'>In diesem Team gibt es keine Benutzer!</p>";
                }
            }
        ?>
    </div>
    <?php
    if (isset($team) && $team != "null") {?>
    <div class="main">
        <form action="includes/teammanager.inc.php" method="post">
            <input name="team" value=<?php echo($team); ?> hidden="1"></input>
            <?php userList($con);?>
            <button type="submit" name="member">Ändere Mitgliedschaft</button>
            <button type="submit" name="mod">Ändere Moderator-Status</button>
        </form>
    </div>
    
    <?php
    }
} elseif ($_GET["page"] == "requests") {?>

    <div class="main">
        <h1>Team Anfragen</h1>
        <form action="includes/teammanager.inc.php" method="post">
            <input type="text" name="teamname" placeholder="Team Name..." style="width: 500px;"><br>
            <button type="submit" name="requestteam">Einsenden</button>
        </form>
        <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "c") {
                    echo "<br><p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Erstellung eingereicht für Team '".$_GET['teamr']."'!</p>";
                } elseif ($_GET["error"] == "emptyf") {
                    echo "<br><p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Gib bitte einen Teamnamen an!</p>";
                } elseif ($_GET["error"] == "exists") {
                    echo "<br><p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es gibt bereits ein Team mit dem Namen '".$_GET['teamr']."'!</p>";
                } elseif ($_GET["error"] == "toolong") {
                    echo "<br><p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Die maximale Länge eines Teamnamen ist 32!</p>";
                }
            }
        ?>
    </div>

    <div class="main">
        <h1>Deine offenen Team Anfragen</h1>
        <?php
            userTeamRequests($con, $_SESSION["username"]);
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "del") {
                    echo "<br><p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Anfrage für Team '".$_GET['teamr']."' zurückgezogen!</p>";
                }
            }
        ?>
    </div>

<?php
}
    ?>
