<?php
require_once "includes/require.php";
include_once "header.php";
if (empty($_SESSION["username"])) {
    header("location: ./");
    exit();
}
?>
<h1 style="font-size: 3rem; margin: 30px;">Profil</h1>
<!--<div class="main">
    <h1>Stunden Verteilung</h1><br>
    <?php
/*    hourOverview(con(), $_SESSION["username"]);
    */ ?>
</div>-->
<div class="main">
    <div class="sub" style="width: fit-content;">
        <h2>Rolle: <span <?php echo("style='color: ".rand_color()."' >".roleData(con(), userData(con(), $_SESSION["username"])["role"])["name"]); ?></span></h2>
    </div>
    <?php
    /*
  <div class="sub">
    <h2>Gruppen:</h2>
      groupUL(con(), $_SESSION["username"]);
  </div>
      */
    ?>
    <div class="sub">
        <h2>Teams:</h2><br>
        <?php
        teamUL(con(), $_SESSION["username"]);
        ?>
    </div>
    <div class="sub">
        <h2>Rechte:</h2><br>
        <?php
        permissionUL(con(), $_SESSION["username"]);
        ?>
    </div>
</div>
<h1 style="font-size: 3rem; margin: 30px;">Einstellungen</h1>
<div class="main">
    <form action="includes/usermanager.inc.php" method="post">
        <input type="text" name="nick" placeholder="Nickname..."
               value="<?php echo(userData(con(), $_SESSION["username"])["nick"]); ?>"><br>
        <button type="submit" name="setnick" style="margin-bottom: 7px;">Set Nickname</button>
        <br>
    </form>

    <?php
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "setnick") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Spitzname geändert!</p>";
        } elseif ($_GET["error"] == "inboundoutofcharacter") {
            echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Spitzname zu lang!</p>";
            echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Max. 22 Zeichen!</p>";
        } elseif ($_GET["error"] == "invalid") {
            echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Spitznamen dürfen 
      folgende Zeichen nicht enthalten <br>'<' and '>'!</p>";
        }
    }
    ?>

    <table style="width: 75%; margin: 0 auto">
        <thead style="font-size: 1.2rem">
        <tr>
            <th style="width: 70%">Name</th>
            <th style="width: 30%">Einstellung</th>
        </tr>
        </thead>
        <form action="includes/settings.post.php" method="post">
            <tbody>

            <?php
            $count = 1;
            foreach (getSettings() as $set => $desc) {
                echo '
            <tr>
            <td style="border-radius: 5px 0 0 5px;">' . $desc . '</td>
            <td style="border-radius: 0 5px 5px 0;">
                <button type="submit" name="setting" value="' . $set . '" style="width: 200px">
                    ' . boolToYN(userData(con(), $_SESSION["username"])[$set]) . '
                </button>
            </td>
            </tr>
                        ';
                $count++;
            }
            ?>

            </tbody>
        </form>
    </table>

</div>
<div class="main">
    <form action="includes/usermanager.inc.php" method="post">
        <input type="password" name="oldpw" placeholder="Altes Passwort..."><br>
        <input type="password" name="pw" placeholder="Neues Passwort..."><br>
        <input type="password" name="rpw" placeholder="Passwort wiederholen..."><br>
        <button type="submit" name="setpw" style="margin-bottom: 7px;">Passwort setzen</button>
        <br>
    </form>

    <?php
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "pwset") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Passwort geändert!</p>";
        } elseif ($_GET["error"] == "repeatpw") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Du musst dein neues Passwort bestätigen!";
        } elseif ($_GET["error"] == "invalidrpw") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Passwörter stimmen nicht überein!";
        } elseif ($_GET["error"] == "wrongpw") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dieses alte Passwort ist inkorrekt!";
        } elseif ($_GET["error"] == "tooshort") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Passwörter müssen aus Sicherheitsgründen 
          mindestens 8 Zeichen haben!";
        }
    }
    ?>
</div>
