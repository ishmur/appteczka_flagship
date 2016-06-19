<?php
    session_start();
    require_once("include/functions.php");
    if(!isset($_SESSION['username'])){
        header("Location: index.php?logout=1");
        exit();
    }

    if(!users_is_admin(users_get_id_from_name($_SESSION['username']), $_SESSION['groupID'])){
        header("Location: home.php");
    }

    if (isset($_POST['kickUsers'])){
        foreach ($_POST['kickUsers'] as $user_id) {
            groups_leave($_SESSION['groupID'], users_get_name_from_id($user_id));
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php
$panelAdmin = 'class="active"'; // set "active" class for current page
$header = "Zarządzanie użytkownikami w apteczce"; // set header string for current page
include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-7 col-sm-offset-4">
            <? group_print_members($_SESSION['groupID']); ?>
        </div>
    </div>
</div>

<script src="js/group_manage.js"></script>
</body>
</html>