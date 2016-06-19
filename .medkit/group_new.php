<?php
    session_start();

    require_once("include/functions.php");

    if(!isset($_SESSION['username'])){
        header("Location: index.php?logout=1");
        exit();
    }


    if(isset($_POST['new-grp-submit'])) {

        $group_name = validate_trim_input($_POST['group_name']);
        $password = validate_trim_input($_POST['password']);
        $password_check = validate_trim_input($_POST['password_check']);

        $name_valid = validate_group_name($group_name, $error_name_text, $error_name_flag);
        $passwords_valid = validate_password_fields($password, $password_check, $error_psw_text, $error_psw_flag);

        if($name_valid && $passwords_valid){

            $password = md5($password);

            if(register($group_name, $password, 'group')){

                groups_add_user_to_group($group_name, $_SESSION['username'], 1);
                $_SESSION['new_group'] = $group_name;

                header("Location: group_choose.php");
                exit();

            } else {

                ?>
                <div class="alert alert-danger">
                    Wystąpił błąd połączenia z serwerem, prosimy spróbować ponownie później.
                </div>
                <?

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
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php
    $settingsGroupNew = 'class="active"'; // set "active" class for current page
    $showDropdownSettings = "show"; // set drugs side-menu item to be permanently visible
    $showSettings = 'style="color:white"'; // change color of settings top-navbar icon
    $header = "Utwórz grupę"; // set header string for current page
    include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br/><br/><br/><br/>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3">

            <div class="col-sm-3 inline-element-center">
                <h2>Członkostwo w grupie</h2>
                <h4>sprawdza się w sytuacji, gdy wiele osób ma dostęp do wspólnej apteczki <br/>(np. w mieszkaniu). <br/><br/>
                    Dzięki członkostwu możliwe jest dzielenie się zasobami - system sam poinformuje pozostałych użytkowników o zaistniałych zmianach!</h4><br/>
            </div>

            <div class="col-sm-9">
                <div class="container-fluid">
                    <div class="col-sm-8">
                        <form action = "" method = "POST">
                            <div class="form-group <? echo $error_name_flag; ?>">
                                <label for="email"><i class="fa"></i>Nazwa apteczki</label>
                                <p style="color:red"><?php echo $error_name_text ?></p>
                                <input type="text" class="form-control" name="group_name" required placeholder="Wprowadź nazwę nowej grupy" value=<?php echo "$group_name" ?>>
                            </div>
                            <div class="form-group <? echo $error_psw_flag; ?>">
                                <label for="password"><i class="fa"></i>Hasło</label>
                                <p style="color:red"><?php echo $error_psw_text ?></p>
                                <input type="password" class="form-control" name="password" required placeholder="Wprowadź hasło grupy">
                            </div>
                            <div class="form-group <? echo $error_psw_flag; ?>">
                                <label for="password_check"><i class="fa"></i>Powtórz hasło</label>
                                <input type="password" class="form-control" name="password_check" required placeholder="Powtórz hasło grupy">
                            </div>
                            <br />
                            <button type="submit" name='new-grp-submit' class="btn btn-col btn-block">Utwórz grupę!</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>

</html>
