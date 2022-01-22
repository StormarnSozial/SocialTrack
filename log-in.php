<?php
  include_once 'header.php';
 ?>
    <style media='screen'>
      p {
        color: red;
      }
    </style>
    <script type="text/javascript">
      document.getElementById("login").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
    </script>

    <div class='log-in' <?php #style="background: url(img/back.jpg)no-repeat; background-size: cover; border: solid none;" ?>>
      <h2>Log In</h2>
      <form action='includes/log-in.inc.php' method='post'>
        <input type='text' name='name' placeholder='Username...'><br>
        <input type='password' name='pw' placeholder='Password...'><br>
        <button type='submit' name='submit'>Log In</button><br>
      </form>
      <?php
      if (isset($_GET['error'])) {
        if ($_GET['error'] == 'wrongid') {
          echo '<p style="margin-top: 10px;">Invalid Username</p>';
          echo '<p style="margin-top: 10px;">Visit the <a href="support.php" style="color: lime">support page</a></p>';
        }
        else if ($_GET['error'] == 'wrongpw') {
          echo '<p style="margin-top: 10px;">Invalid Password</p>';
        }
        else if ($_GET['error'] == 'emptyinput') {
          echo '<p style="margin-top: 10px;">Fill in all fields!</p>';
        }
        else if ($_GET['error'] == 'disabled') {
          echo '<p style="margin-top: 10px;">This account is disabled!</p>';
        }
      }
       ?>
    </div>
  </body>
</html>
