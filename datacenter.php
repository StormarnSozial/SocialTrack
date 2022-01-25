<?php
  include_once "header.php";
  if (empty($_SESSION["username"])) {
    header("location: ./");
    exit();
  }
  if (getUserPower($con, $_SESSION["username"]) < 40) {
    header("location: ./?error=noperm");
    exit();
  }
?>
    <script type="text/javascript">
        document.getElementById("datas").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
    </script>
  <?php
  if (!isset($_GET["data"]) || dataData($con, $_GET["data"]) === false) {
  ?>
    <div class="main">
    <form action="datacenter.php" method="post">
      <?php 
          if (getUserPower($con, $_SESSION["username"]) < 50) {
              teamsListLeader($con); 
          } else {
              teamsList($con);
              userList($con);
          }
      ?>
      <button type="submit" name="submit">Filtern</button>
    </form><br>
    <?php 
        if (isset($_POST["user"]) && $_POST["user"] != "null") {
            $userName = userData($con, $_POST["user"])["fullname"];
            echo("<p>Gefiltert für Benutzer: '".$userName."'</p>");
        }
        if (isset($_POST["team"]) && $_POST["team"] != "null") {
            $teamName = teamData($con, $_POST["team"])["name"];
            echo("<p>Gefiltert für team: '".$teamName."'</p>");
        }
    ?>
    <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
        <thead>
            <tr>
            <th style="padding-left: 10px; padding-right: 10px;">Benutzer</th>
            <th style="padding-left: 10px; padding-right: 10px;">Name</th>
            <th style="padding-left: 10px; padding-right: 10px;">Team</th>
            <th style="padding-left: 10px; padding-right: 10px;">Dauer</th>
            <th style="padding-left: 10px; padding-right: 10px;">Datum</th>
            <th style="padding-left: 10px; padding-right: 10px;">Signiert</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!isset($_POST["user"]) || $_POST["user"] != "null") {
          $team = "null";
          if (!empty($_POST["team"])) {
          $team = $_POST["team"];
          }
          teamDatas($con, $team);
        } else {
          datas($con, $_POST["user"], $_POST["team"]);
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
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Daten bearbeitet!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Event '".$_GET["name"]."' wurde bearbeitet!</p>";
            } elseif ($_GET["error"] == "eerror") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
            } elseif ($_GET["error"] == "eemptyf") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtiges Feld war leer!";
            } elseif ($_GET["error"] == "deldata") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Daten vernichtet!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Event '".$_GET["name"]."' wurde aus der Datenbank gelöscht!</p>";
            } elseif ($_GET["error"] == "invalid") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Name darf folgende Zeichen nicht enthalten: <br>'<' and '>'!</p>";
            }
          }
    ?>
    </div>
  <?php
  } elseif (isset($_GET["data"])) {?>

  <div class="main">
    <a href="datacenter.php" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
    <form action="includes/datamanager.inc.php" method="post">
      <input type="number" name="id" placeholder="ID..." value="<?php echo($_GET["data"]); ?>" hidden="1"><br>
      <?php
        $data = dataData($con, $_GET["data"]);
        echo("<h1 style='font-size: 3rem;'>".$data["name"]."</h1>");
        echo '<h2>von '.userData($con, $data["account"])["fullname"].'</h2><br>';
        echo '<h2>Aktuelle Werte:</h2>';
        echo '<p>Team: '.teamData($con, $data["team"])["name"].'</p>';
        echo '<p>Stunden: '.$data["lessons"].'</p>';
        echo '<p>Datum: '.$data["edate"].'</p>';
      ?>
      <input type="text" name="name" placeholder="Name..." value="<?php echo(dataData($con, $_GET["data"])["name"]); ?>"><br>
      <?php teamsListMember($con, $data["account"]); ?>
      <input type="number" name="lessons" placeholder="Stunden..." value="<?php echo(dataData($con, $_GET["data"])["lessons"]); ?>"><br>
      <input type="datetime-local" name="date" placeholder="Datum..." style="width: 250px;" value="<?php echo(dataData($con, $_GET["data"])["edate"]); ?>"><br>
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
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Daten bearbeitet!</p>";
            } elseif ($_GET["error"] == "eerror") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
            } elseif ($_GET["error"] == "eemptyf") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtiges Feld war leer!";
            } elseif ($_GET["error"] == "deldata") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Deleted data!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Event '".$_GET["name"]."' wurde aus der Datenbank gelöscht!</p>";
            } elseif ($_GET["error"] == "invalid") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Name darf folgende Zeichen nicht enthalten: <br>'<' and '>'!</p>";
            }
          }
    ?>
  </div>
    <?php
  }?>