<?php
session_start();

require_once("include/functions.php");

$group_name_error;
$password_error;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_name = trim_input($_POST['group_name']);
    $password = trim_input($_POST['password']);
    $password_check = trim_input($_POST['password_check']);

    $is_group_name_valid = is_group_name_valid($group_name, $group_name_error);
    $are_passwords_valid = password_valid($password, $password_check, $password_error);
    if($is_group_name_valid && $are_passwords_valid){
        $password = md5($password);
        if(register($group_name, $password, 'group')){
            header("Location: home.php?reg=1");
            $_SESSION['new_group'] = $group_name;
            exit();
        } else {
            die("Database error");
        }
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
                                <label for="email"><i class="fa"></i> Nazwa apteczki <? echo $group_name_error; ?></label>
                                <input type="text" class="form-control" name="group_name" placeholder="Nazwa Twojej/Waszej apteczki">
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="fa"></i> Hasło <? echo $password_error; ?></label>
                                <input type="password" class="form-control" name="password" placeholder="Twoje hasło">
                            </div>
                            <div class="form-group">
                                <label for="password_check"><i class="fa"></i> Powtórz hasło</label>
                                <input type="password" class="form-control" name="password_check" placeholder="Podaj ponownie Twoje hasło">
                            </div>
                            <br />
                            <button type="submit" class="btn btn-col btn-block">Zarejestruj się!</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


</body>

</html>
