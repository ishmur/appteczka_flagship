<?php
	session_start();

	require_once("include/functions.php");

	if($_GET['logout']==1){
		setcookie('username', "", 1);
		setcookie('password', "", 1);
		session_destroy();
		session_start();
		header("Location: index.php");
		exit();
	}

	else if (isset($_COOKIE['username']) && isset($_COOKIE['password'])){
		if (correct_password($_COOKIE['username'], $_COOKIE['password'])) {
			$_SESSION['username'] = $_COOKIE['username'];
			header("Location: home.php");
			exit();
		}
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//General validation of inputs
		$username = trim_input($_POST['email']);
		$password = trim_input($_POST['password']);
		$remember_me = isset($_POST['remember']);

		$user_check = login_basic_check($username, $user_error_message);
		$password_check = password_basic_check($password, $password_error_message);

		// Proceed if no errors
		if($user_check && $password_check){
			$password = md5($password);
			if(correct_password($username, $password)) {
				$_SESSION['username'] = $username;
				$_SESSION['drugsOverdueModal'] = "show";
				header("Location: home.php");
				if(!$remember_me) {
					exit();
				} else {
					setcookie('username', $username, time()+60*60*24*365);
					setcookie('password', $password, time()+60*60*24*365);
					exit();
				}
			} else {
				$user_error_message = "Nieprawidłowe dane logowania";
				$password_error_message = "Nieprawidłowe dane logowania";
				$show_modal = "style='display:block'";
				$form_style = "has-error";
			}
		} else {
			$show_modal = "style='display:block'";
			$form_style = "has-error";
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Index</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/modal.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">

</head>

<body id="bodyIndex">

<?php
	include("include/index_header.php");

	if($_GET['reg']==1){
		?>
		<div class="alert alert-success overlay">
			Welcome aboard the <strong>HMS App.teczka</strong>! Let's talk drugs, shall we?
		</div>
		<?
	}
?>

<div class="container" >

	<div class="row" id="contentContainer">

		<div class="col-sm-5">
			<div class="container-fluid inline-element-center">
				<i class="fa fa-user-md" style="font-size:300px"></i>
			</div>
		</div>

		<div class="col-sm-6">

			<div class="container-fluid">
				<div class="row">
					<div class="container-fluid jumbotron inline-element-center">
						<h1 style="font-size:25px">
							<i class="fa fa-medkit" ></i> App.teczka<br /><br />
							System zarządzania <br />domową apteczką</h1>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="container-fluid">

					<button type="button" class="btn btn-lg btn-block btn-col" id="btnLogin">Logowanie</button>

					<!-- Modal -->
					<div class="modal" role="dialog" <?php echo $show_modal ?> >
						<div class="modal-dialog">

							<!-- Modal content-->
							<div class="modal-content">

								<div class="modal-header">
									<button type="button" class="close">&times;</button>
									<h4 style="color:white;"><span class="glyphicon glyphicon-home"></span> Logowanie</h4>
								</div>

								<div class="modal-body">
									<form action="" method="POST">
										<div class="form-group <? echo $form_style; ?>">
											<label for="usrname"><span class="glyphicon glyphicon-user"></span> Nazwa użytkownika</label>
											<p style="color:red"><?php echo $user_error_message ?></p>
											<input type="email" name="email" class="form-control" id="usrname" placeholder="Nazwa użytkownika" value=<?php echo "$username" ?>>
										</div>
										<div class="form-group <? echo $form_style; ?>">
											<label for="psw"><span class="glyphicon glyphicon-lock"></span> Hasło</label>
											<p style="color:red"><?php echo $password_error_message ?></p>
											<input type="password" name="password" class="form-control" id="psw" placeholder="Hasło">
										</div>
										<div class="checkbox">
											<label><input type="checkbox" name="remember" value="" checked>Zapamiętaj mnie</label>
										</div>
										<button type="submit" class="btn btn-col btn-block"><span class="glyphicon glyphicon-off"></span> Zaloguj</button>
									</form>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-default btn-default pull-left" id="modalCancelBtn">
										<span class="glyphicon glyphicon-remove"></span> Cofnij
									</button>
									<p><a href="#">Zapomniałem hasła</a></p>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="container-fluid">
					<a href="registration.php">
						<button type="button" class="btn btn-lg btn-block btn-col index-btn">Zarejestruj się</button>
					</a>
				</div>
			</div>

			<div class="row">
				<div class="container-fluid">
					<!-- This button is temporary until login functionality has been implemented -->
					<form action="" method="POST">
						<input type="hidden" name="email" value="admin@a.pl">
						<input type="hidden" name="password" value="admin">
						<button type="submit" class="btn btn-lg btn-block btn-col index-btn">Zaloguj jako admin</button>
					</form>
				</div>
			</div>

		</div>
	</div>

</div>

<script src="js/index_contentCentering.js"></script>

<?php
	include("include/index_footer.php");
?>

<script src="js/index.js"></script>

</body>
</html>
