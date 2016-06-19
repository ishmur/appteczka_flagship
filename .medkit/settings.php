<?php

	session_start();
	require_once("include/functions.php");

	$newpass_error;
	$oldpass_error;
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_SESSION['username'];
		$oldpassword = md5(validate_trim_input($_POST['pswOld']));
		$password = md5(validate_trim_input($_POST['pswNew1']));
		$password_check = md5(validate_trim_input($_POST['pswNew2']));

		$correct_old_password = correct_password($username, $oldpassword);
		$are_passwords_valid = validate_password_fields($password, $password_check, $newpass_error);

		if(!$correct_old_password) $oldpass_error = "Podałeś złe hasło";

		if($correct_old_password && $are_passwords_valid) {
			if (!change_password($username, $password)) {
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
		
			<div class="col-sm-3 inline-element-center">
				<h2>Zmiana hasła</h2><br />
			</div>
		
			<div class="col-sm-9 col-sm-offset-3">
				<div class="container-fluid">
					<div class="col-sm-8">
						<form action = "" method = "POST">
							<div class="form-group <? echo $form_style; ?>">
								<label for="pswOld"><i class="fa fa-lock"></i> Stare hasło</label>
								<p style="color:red"><?php echo $oldpass_error ?></p>
								<input type="password" class="form-control" name="pswOld" id="pswOld" placeholder="Wpisz swoje stare hasło">
							</div>
							<div class="form-group <? echo $form_style; ?>">
								<label for="pswNew1"><i class="fa fa-plus"></i> Nowe hasło</label>
								<p style="color:red"><?php echo $newpass_error ?></p>
								<input type="password" class="form-control" name="pswNew1" id="pswNew1" placeholder="Wpisz nowe hasło">
							</div>
							<div class="form-group <? echo $form_style; ?>">
								<label for="pswNew2"><i class="fa fa-plus-circle"></i> Potwierdź nowe hasło</label>
								<input type="password" class="form-control" name="pswNew2" id="pswNew2" placeholder="Wpisz nowe hasło jeszcze raz">
							</div>
							<br />
							<button type="submit" class="btn btn-col btn-block">Zatwierdź zmiany</button>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

</body>

</html>
