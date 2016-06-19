<?php
    session_start();

    require_once("include/functions.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = validate_trim_input($_POST['username']);
        $password = validate_trim_input($_POST['password']);
        $password_check = validate_trim_input($_POST['password_check']);

        $is_email_valid = login_valid($username, $login_error);
        $are_passwords_valid = validate_password_fields($password, $password_check, $password_error);

        if($is_email_valid && $are_passwords_valid) {
            $password = md5($password);
            if (register($username, $password, 'user')) {
                header("Location: index.php?reg=1");
                exit();
            } else {
                die("Database error");
            }
        } else {
            $form_style = "has-error";
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
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
    <link rel="stylesheet" type="text/css" href="css/registration.css">
</head>

<body id="bodyIndex">

<?php
    include("include/index_header.php");
?>

<div class="container">

    <div class="row" id="contentContainer">
        <div class="col-sm-6">

            <div class="col-sm-11 col-sm-offset-1">
                <div class="container-fluid">
                    <div class="col-sm-12">
                        <form action = "" method = "POST" id="RegistrationForm">
                            <div class="form-group <? echo $form_style; ?>">
                                <label for="email"><span class="glyphicon glyphicon-user"></span> E-mail </label>
                                <p style="color:red"><?php echo $login_error ?></p>
                                <input type="email" class="form-control" name="username" placeholder="Wpisz adres email" value=<?php echo "$username" ?>>
                            </div>
                            <div class="form-group <? echo $form_style; ?>">
                                <label for="password"><span class="glyphicon glyphicon-lock"></span> Hasło</label>
                                <p style="color:red"><?php echo $password_error ?></p>
                                <input type="password" class="form-control" name="password" placeholder="Wpisz hasło">
                            </div>
                            <div class="form-group <? echo $form_style; ?>">
                                <label for="password_check"><i class="fa fa-get-pocket" aria-hidden="true"></i> Powtórz hasło</label>
                                <input type="password" class="form-control" name="password_check" placeholder="Powtórz hasło">
                            </div>
                            <br />
                        </form>

                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" form="RegistrationForm" class="btn btn-col btn-block">Zarejestruj się!</button>
                            </div>
                            <div class="col-sm-6">
                                <a href="index.php">
                                    <button type="button" class="btn btn-col btn-block">Powrót</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/><br/><br/>
        </div>

        <div class="col-sm-5">

            <div class="container-fluid">
                <div class="row">
                    <div class="container-fluid jumbotron inline-element-center">
                        <h1 style="font-size:25px">
                            <i class="fa fa-medkit" ></i> App.teczka</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="container-fluid jumbo-text">
                        <h4>Korzyści płynące z korzystania z systemu:</h4>
                        <ul>
                            <li>porządek w domowej apteczce</li>
                            <li>przypomnienie o przeterminowanych lekach</li>
                            <li>darmowe przepisy na wyśmienite latte macchiato</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="js/index_contentCentering.js"></script>

<?php
    include("include/index_footer.php");
?>

</body>

</html>
