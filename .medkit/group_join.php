<?php
session_start();

require_once("include/functions.php");

$group_name_error;
$password_error;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_name = trim_input($_POST['group_name']);
    $password = trim_input($_POST['password']);

    $group_exists = does_group_exist($group_name, $group_name_error);
    $is_password_correct = correct_password_group($group_name, $password, $password_error);
    if($group_exists && $is_password_correct){
        $password = md5($password);
        if(add_to_group($group_name, $_SESSION['username'])){
            header("Location: home.php?reg=2");
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
                            <div class="form-group">
                                <label for="email"><i class="fa"></i> Wybierz apteczkę <? echo $group_name_error; ?></label><br/>
                                <input name="group_name" id="choose_group" list="group_list" placeholder="Wprowadź nazwę grupy" class="form-control">
                                <datalist id="group_list">
                                    <?php
                                        $result = get_all_groups();
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value=" .$row['group_name']. "></option>";
                                    }
                                    ?>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="fa"></i> Hasło <? echo $password_error; ?></label>
                                <input type="password" class="form-control" name="password" placeholder="Hasło grupy">
                            </div>
                            <br />
                            <button type="submit" class="btn btn-col btn-block">Dołącz do grupy!</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>

</html>