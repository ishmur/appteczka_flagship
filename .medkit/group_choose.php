<?php
    session_start();

    require_once("include/functions.php");

    if(!isset($_SESSION['username'])){
        header("Location: index.php?logout=1");
        exit();
    }

    $username = $_SESSION['username'];

    if(isset($_POST['group_change'])) {
        $groupID = $_POST['group_change'][0];
        $result = groups_change($groupID, $username);
        if ($result){
            $_SESSION["groupID"] = $groupID;
            $_SESSION["groupName"] = groups_get_selected_name($groupID);
            $_SESSION['changed_group'] =  $_SESSION["groupName"];

            if (drugs_overdue_check_date($_SESSION["groupID"])){
                $_SESSION['drugsOverdueModal'] = "show";
            }

            header("Location: home.php");
            exit();
        }
    }

    if(isset($_POST['groups'])) {
        foreach ($_POST['groups'] as $groupID) {
            if ($groupID == $_SESSION["groupID"]){
                $result = groups_change($groupID, $username, true); // true = setNULL
                $_SESSION["groupID"] = null;
                $_SESSION["groupName"] = null;
            }
            groups_leave($groupID, $username);
            $_SESSION['left_group'] = true;
            header("Location: group_choose.php");
            exit();
        }
    }

?>


<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <title>Wybierz grupę</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php
    $settingsGroupChoose = 'class="active"'; // set "active" class for current page
    $showDropdownSettings = "show"; // set drugs side-menu item to be permanently visible
    $showSettings = 'style="color:white"'; // change color of settings top-navbar icon
    $header = "Wybierz grupę"; // set header string for current page
    include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br/><br/>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3">

            <?php if(isset($_SESSION['joined_group'])){ ?>
                <div class="alert alert-success">
                    Dołączono do apteczki: <strong><? echo $_SESSION['joined_group']?></strong>!
                </div>
            <?php $_SESSION['joined_group'] = null; } ?>

            <?php if(isset($_SESSION['new_group'])){ ?>
                <div class="alert alert-success">
                    Utworzono nową apteczkę: <strong><? echo $_SESSION['new_group']?></strong>!
                </div>
            <?php $_SESSION['new_group'] = null; } ?>

            <?php if(isset($_SESSION['left_group'])){ ?>
                <div class="alert alert-success">
                    Opuszczono zaznaczone grupy!
                </div>
            <?php $_SESSION['left_group'] = null; } ?>

            <div class="col-md-8 col-md-offset-2">
                <div class="container-fluid">
                    <div class="col-md-12">
                        <div class="container-fluid">
                           

                                    <?php
                                        if(!isset($_GET['p'])) $_GET['p'] = 1;
                                        groups_print_table($username, $_GET['p']);
                                    ?>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="js/group_choose.js"></script>

</body>

</html>
