<?php
  include_once "header.php";
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
    header("refresh: 0");
    exit();
  }
  if (isset($_POST["user"])) {
    $fuser = $_POST["user"];
    $_SESSION["fuser"] = $_POST["user"];
  } elseif (isset($_GET["user"])) {
    $fuser = $_GET["user"];
    $_SESSION["fuser"] = $_GET["user"];
  } elseif (isset($_SESSION["fuser"])) {
    $fuser = $_SESSION["fuser"];
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
?>
    <script type="text/javascript">
        document.getElementById("datas").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
    </script>
  <?php
    if (getUserPower(con(), $_SESSION["username"])) {?>
        <div class="main">
            <form action="datacenter.php">
                <button type='submit' name='page' value="events">Events</button>
                <button type='submit' name='page' value="summary">Zusammenfassung</button>
            </form>
        </div>
    <?php
    }
    if (!isset($_GET["page"]) || $_GET["page"] == "events") {
        if (!isset($_GET["data"]) || dataData(con(), $_GET["data"]) === false) {
  ?>
    <div class="main">
        <h1>Alle Events</h1>
    <form action="datacenter.php">
      <?php
          if (getUserPower(con(), $_SESSION["username"]) < 50) {
              teamsListLeader(con());
          } else {
              teamsList(con());
              userList(con());
          }
      ?>
      <button type="submit" name="submit">Filtern</button><br>
      <?php
        if (isset($fteam)) {
          echo '
          <button type="submit" name="resetfilter" style="margin-top: 5px;">Zurücksetzen</button>';
        }
      ?>
    </form><br>
    <?php
        if (isset($fuser) && $fuser != "null") {
            $userName = userData(con(), $fuser)["fullname"];
            echo("<p>Gefiltert für Benutzer: '".$userName."'</p>");
        }
        if (isset($fteam) && $fteam != "null") {
            $teamName = teamData(con(), $fteam)["name"];
            echo("<p>Gefiltert für team: '".$teamName."'</p>");
        }
    ?>
    <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
        <thead>
            <tr>
              <?php
              if (!(isset($fuser) && $fuser != "null")) {?>
                <th style="padding-left: 10px; padding-right: 10px;">Benutzer</th>
                <?php
              }?>
            <th style="padding-left: 10px; padding-right: 10px;">Name</th>
            <th style="padding-left: 10px; padding-right: 10px;">Team</th>
            <th style="padding-left: 10px; padding-right: 10px;">Dauer</th>
            <th style="padding-left: 10px; padding-right: 10px;">Datum</th>
            <th style="padding-left: 10px; padding-right: 10px;">Signiert</th>
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
              echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; border-radius: 7px; margin: 10px auto;'>Event '" .$_GET["name"]."' wurde bearbeitet!</p>";
            } elseif ($_GET["error"] == "eerror") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
            } elseif ($_GET["error"] == "eemptyf") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtiges Feld war leer!";
            } elseif ($_GET["error"] == "deldata") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten vernichtet!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; border-radius: 7px; margin: 10px auto;'>Event '" .$_GET["name"]."' wurde aus der Datenbank gelöscht!</p>";
            } elseif ($_GET["error"] == "invalid") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Name darf folgende Zeichen nicht enthalten: <br>'<' and '>'!</p>";
            }
          }
    ?>
    </div>
  <?php
        } else {?>

  <div class="main">
    <?php
      $filters = "";
      if (!empty($_SESSION["fteam"])) {
          $filters = "?fteam=".$_SESSION["fteam"];
      }
      if (!empty($_SESSION["fuser"])) {
          $filters = $filters."&fuser=".$_SESSION["fuser"];
      }
    ?>
    <a href="datacenter.php<?php echo($filters); ?>" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
    <form action="includes/datamanager.inc.php" method="post">
      <input type="hidden" name="id" placeholder="ID..." value="<?php echo($_GET["data"]); ?>"><br>
      <?php
        $data = dataData(con(), $_GET["data"]);
        echo("<h1 style='font-size: 3rem;'>".$data["name"]."</h1>");
        echo '<h2>von '.userData(con(), $data["account"])["fullname"].'</h2><br>';
        echo '<h2>Aktuelle Werte:</h2>';
        echo '<p>Team: '.teamData(con(), $data["team"])["name"].'</p>';
        echo '<p>Stunden: '.$data["lessons"].'</p>';
        echo '<p>Datum: '.$data["edate"].'</p>';
      ?>
      <input type="text" name="name" placeholder="Name..." value="<?php echo(dataData(con(), $_GET["data"])["name"]); ?>"><br>
      <?php teamsListMember(con(), $data["account"]); ?>
      <input type="number" name="lessons" placeholder="Stunden..." value="<?php echo(dataData(con(), $_GET["data"])["lessons"]); ?>"><br>
      <input type="datetime-local" name="date" placeholder="Datum..." style="width: 250px;" value="<?php echo(dataData(con(), $_GET["data"])["edate"]); ?>"><br>
      <?php #<button type="submit" name="add">Hinzufügen</button><br><br>?>
      <button type="submit" name="edit" value="datac">Bearbeiten</button><br><br>
      <button type="submit" name="del" value="datac">Löschen</button>
    </form>
    <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "error") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
            } elseif ($_GET["error"] == "emptyf") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in every field!";
            } elseif ($_GET["error"] == "dataedited") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten bearbeitet!</p>";
            } elseif ($_GET["error"] == "eerror") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
            } elseif ($_GET["error"] == "eemptyf") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtiges Feld war leer!";
            } elseif ($_GET["error"] == "deldata") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Deleted data!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px;'>Event '" .$_GET["name"]."' wurde aus der Datenbank gelöscht!</p>";
            } elseif ($_GET["error"] == "invalid") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Name darf folgende Zeichen nicht enthalten: <br>'<' and '>'!</p>";
            }
          }
    ?>
  </div>
    <?php
        }
    } else {?>

        <div class="main">
            <h1>Komplette Zusammenfassung</h1>
            <?php
                usersOverview(con());
            ?>
        </div>

    <?php
    }?>