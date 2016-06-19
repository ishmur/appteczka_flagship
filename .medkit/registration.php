<?php
    session_start();

    require_once("include/functions.php");

    if(isset($_POST['reg-submit'])) {

        $username = validate_trim_input($_POST['username']);
        $password = validate_trim_input($_POST['password']);
        $password_check = validate_trim_input($_POST['password_check']);

        $is_email_valid = validate_new_email($username, $error_name_text, $error_name_flag);
        $are_passwords_valid = validate_password_fields($password, $password_check, $error_psw_text, $error_psw_flag);

        if($is_email_valid && $are_passwords_valid) {

            $password = md5($password);

            if (register($username, $password, 'user')) {

                $_SESSION['new_user'] = $username;
                header("Location: index.php");
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
    <title>Rejestracja użytkownika</title>
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
                            <div class="form-group <? echo $error_name_flag; ?>">
                                <label for="email"><span class="glyphicon glyphicon-user"></span> E-mail </label>
                                <p style="color:red"><?php echo $error_name_text ?></p>
                                <input type="email" class="form-control" name="username" required placeholder="Wpisz adres email" value=<?php echo "$username" ?>>
                            </div>
                            <div class="form-group <? echo $error_psw_flag; ?>">
                                <label for="password"><span class="glyphicon glyphicon-lock"></span> Hasło</label>
                                <p style="color:red"><?php echo $error_psw_text ?></p>
                                <input type="password" class="form-control" name="password" required placeholder="Wpisz hasło">
                            </div>
                            <div class="form-group <? echo $error_psw_flag; ?>">
                                <label for="password_check"><i class="fa fa-get-pocket" aria-hidden="true"></i> Powtórz hasło</label>
                                <input type="password" class="form-control" name="password_check" required placeholder="Powtórz hasło">
                            </div>
                            <br />
                        </form>

                        <div class="row">
                            <div class="col-sm-6">
                                <a href="index.php">
                                    <button type="button" class="btn btn-danger col-xs-12">Powrót</button>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" form="RegistrationForm" name='reg-submit' class="btn btn-col btn-block col-xs-12">Zarejestruj się!</button>
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
