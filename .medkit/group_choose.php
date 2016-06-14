<?php
    session_start();

    require_once("include/functions.php");

    if(!isset($_SESSION['username'])){
        header("Location: index.php?logout=1");
        exit();
    }

    $username = $_SESSION['username'];

    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $groupID = $_GET['change'];
        $result = groups_change($groupID, $username);
        if ($result){
            $_SESSION["groupID"] = $groupID;
            $_SESSION["groupName"] = groups_get_name($groupID);
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_POST['groups'] as $groupID) {
            if ($groupID == $_SESSION["groupID"]){
                $result = groups_change($groupID, $username, true); // true = setNULL
                $_SESSION["groupID"] = null;
                $_SESSION["groupName"] = null;
            }
            groups_leave($groupID, $username);
        }
    }

    if($_GET['reg']==1){
        ?>
        <div class="alert alert-success">
            You've just created new virtual medical kit called <strong><? echo $_SESSION['new_group']?></strong>! Good for you!
        </div>
        <?
        $_SESSION['new_group'] = "";
    }

    if($_GET['reg']==2){
        ?>
        <div class="alert alert-success">
            You've just joined a virtual medical kit called <strong><? echo $_SESSION['new_group']?></strong>! Cheers mate!
        </div>
        <?
        $_SESSION['new_group'] = "";
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

            <div class="col-md-8 col-md-offset-2">
                <div class="container-fluid">
                    <div class="col-md-12">
                        <div class="container-fluid">
                            <form action="" method='POST'>
                                <h2>Lista grup, do których należysz</h2><hr />
                                <table class="table table-hover">

                                    <?php
                                    groups_print_table($username);
                                    ?>

                                </table>
                                <button type="submit" class="btn btn-col btn-block">Opuść zaznaczone grupy</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

</body>

</html>
