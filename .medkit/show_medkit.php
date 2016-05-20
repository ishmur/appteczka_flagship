<?php

session_start();

require_once("include/functions.php");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_name = trim_input($_POST['group_name']);
    $group_exists = does_group_exist($group_name, $group_name_error);
    
    if($group_exists){
        $_SESSION['medkit'] = $group_name;
    } else {
            die("$group_exists");
    }
}

if(!isset($_SESSION['username'])){
    header("Location: index.php?logout=1");
    exit();
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
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<br />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3">

            <div class="col-sm-10">
                <div class="container-fluid">
                    <div class="col-sm-10">
                        <form action = "" method = "POST">
                            <div class="form-group">
                                <?
                                if(isset($_SESSION['medkit'])) {
                                    echo "Your current medkit: <strong>".$_SESSION['medkit']."</strong>";
                                } else {
                                    echo "You did not choose any medkit.";
                                }
                                ?>
                                <br>
                                <label for="email"><i class="fa"></i> Wybierz apteczkę <? echo $group_name_error; ?></label>
                                <input name="group_name" id="choose_group" list="group_list" >
                                <datalist id="group_list">
                                    <?php
                                    $result = get_users_groups($_SESSION['username']);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value=" .$row['group_name']. "></option>";
                                    }
                                    ?>
                                </datalist>
                            </div>
                            </div>
                            <br />
                            <button type="submit" class="btn btn-col btn-block">Go there</button>
                        </form>
                    </div>

                <div class="col-sm-10">
                    <div class="container-fluid">
                            <br /><h2>Wyniki wyszukiwania</h2><hr />
                            <table class="table table-hover">
                                <?
                                if(isset($_SESSION['medkit'])){
                                    db_drugs_print_table($_SESSION['medkit']);
                                }
                                ?>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</body>

</html>
