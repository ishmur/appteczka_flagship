<?php
    session_start();

    require_once("include/functions.php");

    if(isset($_POST['join-grp-submit'])) {

        $group_name = validate_trim_input($_POST['group_name']);
        $password = validate_trim_input($_POST['password']);

        $group_exists = groups_check_if_exists($group_name, $error_name_text, $error_name_flag);
        $already_in_group = groups_is_user_in_group($_SESSION['username'], $group_name, $group_name_error, $error_name_flag);
        $is_password_correct = groups_check_password_correct($group_name, $password, $error_psw_text, $error_psw_flag);

        if($group_exists && $is_password_correct && (!$already_in_group) ){
            
            $password = md5($password);
            
            if(groups_add_user_to_group($group_name, $_SESSION['username'])){

                $_SESSION['joined_group'] = $group_name;
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
<html lang="pl-PL">
<head>
    <title>Dołącz do grupy</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php
    $settingsGroupJoin = 'class="active"'; // set "active" class for current page
    $showDropdownSettings = "show"; // set drugs side-menu item to be permanently visible
    $showSettings = 'style="color:white"'; // change color of settings top-navbar icon
    $header = "Dołącz do grupy"; // set header string for current page
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
                                <label for="email"><i class="fa"></i>Wybierz apteczkę</label><br/>
                                <p style="color:red"><?php echo $error_name_text ?></p>
                                <input name="group_name" id="choose_group" list="group_list" required placeholder="Wprowadź nazwę grupy" class="form-control" value=<?php echo "$group_name" ?>>
                                <datalist id="group_list">
                                    <?php
                                        $result = groups_get_all_names();
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value=" .$row['group_name']. "></option>";
                                    }
                                    ?>
                                </datalist>
                            </div>
                            <div class="form-group <? echo $error_psw_flag; ?>">
                                <label for="password"><i class="fa"></i>Hasło </label>
                                <p style="color:red"><?php echo $error_psw_text ?></p>
                                <input type="password" class="form-control" name="password" required placeholder="Hasło grupy">
                            </div>
                            <br />
                            <button type="submit" name='join-grp-submit' class="btn btn-col btn-block">Dołącz do grupy!</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>

</html>
