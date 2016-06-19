<?php

	session_start();
	require_once("include/functions.php");

	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

	if(isset($_POST['change-psw-submit'])){

		$username = $_SESSION['username'];
		$oldpassword = md5(validate_trim_input($_POST['pswOld']));
		$password = validate_trim_input($_POST['pswNew1']);
		$password_check = validate_trim_input($_POST['pswNew2']);

		$correct_old_password = users_is_password_correct($username, $oldpassword, $error_old_psw_text, $error_old_psw_flag);
		$are_passwords_valid = validate_password_fields($password, $password_check, $error_new_psw_text, $error_new_psw_flag);

		if($correct_old_password && $are_passwords_valid) {

            $password = md5($password);

			if (!users_change_password($username, $password)) {

                $_SESSION['new_psw'] = true;
				header("Location: settings.php");
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
  <title>Ustawienia użytkownika</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php
    $settingsUser = 'class="active"'; // set "active" class for current page
    $showDropdownSettings = "show"; // set drugs side-menu item to be permanently visible
	$showSettings = 'style="color:white"'; // change color of settings top-navbar icon
	$header = "Ustawienia użytkownika"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">

            <?php if(isset($_SESSION['new_psw'] )){ ?>
                <div class="alert alert-success">
                    Hasło do konta zostało zmienione.
                </div>
            <?php } $_SESSION['new_psw'] = null; ?>
		
			<div class="col-sm-3 inline-element-center">
				<h2>Zmiana hasła</h2><br />
			</div>
		
			<div class="col-sm-9 col-sm-offset-3">
				<div class="container-fluid">
					<div class="col-sm-8">
						<form action = "" method = "POST">
							<div class="form-group <? echo $error_old_psw_flag; ?>">
								<label for="pswOld"><i class="fa fa-lock"></i> Stare hasło</label>
								<p style="color:red"><?php echo $error_old_psw_text ?></p>
								<input type="password" class="form-control" name="pswOld" id="pswOld" required placeholder="Wpisz swoje stare hasło">
							</div>
							<div class="form-group <? echo $error_new_psw_flag; ?>">
								<label for="pswNew1"><i class="fa fa-plus"></i> Nowe hasło</label>
								<p style="color:red"><?php echo $error_new_psw_text ?></p>
								<input type="password" class="form-control" name="pswNew1" id="pswNew1" required placeholder="Wpisz nowe hasło">
							</div>
							<div class="form-group <? echo $error_new_psw_flag; ?>">
								<label for="pswNew2"><i class="fa fa-plus-circle"></i> Potwierdź nowe hasło</label>
								<input type="password" class="form-control" name="pswNew2" id="pswNew2" required placeholder="Wpisz nowe hasło jeszcze raz">
							</div>
							<br />
							<button type="submit" name='change-psw-submit' class="btn btn-col btn-block">Zatwierdź zmiany</button>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

</body>

</html>
