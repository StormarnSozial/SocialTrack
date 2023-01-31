<?php
require_once "includes/require.php";
include_once 'header.php';
 ?>
    <style media='screen'>
      p {
        color: red;
      }
    </style>
<?php
if (!isset($_GET["user"]) || userData(con(), $_GET["user"])) {
?>
    <div class='log-in' <?php #style="background: url(img/back.jpg)no-repeat; background-size: cover; border: solid none;" ?>>
      <h2>Login</h2>
      <form action='includes/log-in.inc.php' method='post'>
        <input type='text' name='name' placeholder='Username...' <?php if (isset($_GET["user"]) && userData(con(), $_GET['user']) !== false) {echo "value='".$_GET['user']."' style='display:none'";}?><br>
        <input type='password' name='pw' placeholder='Password...'><br>
        <button type='submit' name='submit'>Log In</button><br>
      </form>
      <?php
      if (isset($_GET['error'])) {
        if ($_GET['error'] == 'wrongid' || $_GET['error'] == 'wrongpw') {
          echo '<p style="margin-top: 10px;">Ungültige Anmeldedaten</p>';
        }
        else if ($_GET['error'] == 'emptyinput') {
          echo '<p style="margin-top: 10px;">Fülle bitte alle Felder!</p>';
        }
        else if ($_GET['error'] == 'disabled') {
          echo '<p style="margin-top: 10px;">Dieser Account wurde deaktiviert!</p>';
        }
      }
       ?>
    </div>
<?php
} else {?>
    <div class='log-in' <?php #style="background: url(img/back.jpg)no-repeat; background-size: cover; border: solid none;" ?>>
        <h2>Kein Account?</h2><br>
        <p style="color: var(--font)">
        Es sieht so aus, als hättest du noch keinen SocialTrack Account. Schreibe <a href="mailto:<?php echo(getSupportEmail()); ?>?subject=Account"><?php echo(getSupportEmail()); ?></a>
        eine Mail über deinen ISurf Account um dir einen erstellen zu lassen.
        </p><br>
        <h3><a href="log-in.php">Zurück zum Login</a></h3>
    </div>
<?php
}
