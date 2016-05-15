<?php
	session_start();
	
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

<?php 
	$settings = 'class="active"'; // set "active" class for current page
	$showSettings = 'style="color:white"'; // change color of settings top-navbar icon
	$header = "Ustawienia użytkownika"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">
		
			<div class="col-sm-2 inline-element-center">
				<h2>Zmiana hasła</h2><br />
			</div>
		
			<div class="col-sm-10 col-sm-offset-2">
				<div class="container-fluid">
					<div class="col-sm-10">
						<form class="action="#">
							<div class="form-group">
								<label for="pswOld"><i class="fa fa-lock"></i> Stare hasło</label>
								<input type="password" class="form-control" id="pswOld" placeholder="Wpisz swoje stare hasło">
							</div>
							<div class="form-group">
								<label for="pswNew1"><i class="fa fa-plus"></i> Nowe hasło</label>
								<input type="password" class="form-control" id="pswNew1" placeholder="Wpisz nowe hasło">
							</div>
							<div class="form-group">
								<label for="pswNew2"><i class="fa fa-plus-circle"></i> Potwierdź nowe hasło</label>
								<input type="password" class="form-control" id="pswNew2" placeholder="Wpisz nowe hasło jeszcze raz">
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

<script src="js/home.js"></script>

</body>

</html>
