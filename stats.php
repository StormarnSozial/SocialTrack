<?php
require_once "includes/require.php";
?>
<html lang="de">
<head>
    <link rel="stylesheet" href="css/dark.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>SocialTrack</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/logic.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap"
          rel="stylesheet">
    <link rel="icon" href="img/icon-title.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
          integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<table style="width: 65%; ">
    <thead>
    <tr>
        <th>Name</th>
        <th>Stunden</th>
    </tr>
    </thead>
    <tbody>
<?php
$hours = usersHoursArray(con());
foreach ($hours as $user => $hour) {
    echo "<tr>";
    echo "<td>".getName(con(), $user)."</td>";
    echo "<td>".$hour."</td>";
    echo "</tr>";
}
?>
    </tbody>
</table>
</body>
</html>
