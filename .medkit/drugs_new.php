<?php
	session_start();

	require_once("include/functions.php");

	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$drugName = $_POST['drugName'];
		$drugPrice = $_POST['drugPrice'];
		$drugDate = $_POST['drugDate'];
		$username = $_SESSION['username'];

		//Validate inputs - TBA

		db_drugs_new_record($drugName, $drugPrice, $drugDate, $username);

		header("Location: drugs_new.php");
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
	$drugsNew = 'class="active"'; // set "active" class for current page
	$showDropdownDrugs = "show"; // set drugs side-menu item to be permanently visible
	$header = "Dodaj nowy lek"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br />

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">
		
			<div class="col-md-8 col-md-offset-2">
				<div class="container-fluid">
					<div class="col-md-12">
						<form action="" method="POST">
							<div class="form-group">
								<label for="drugName"><i class="fa fa-life-ring"></i> Nazwa leku</label>
								<input type="text" class="form-control" name="drugName" placeholder="Wpisz nazwę nowego leku">
							</div>
							<div class="form-group">
								<label for="drugPrice"><i class="fa fa-money"></i> Cena w złotówkach</label>
								<input type="number" min="0" class="form-control" name="drugPrice" placeholder="Wpisz cenę leku w chwili zakupu">
							</div>
							<div class="form-group">
								<label for="drugDate"><i class="fa fa-hourglass-end"></i> Data ważności leku</label>
								<input type="date" class="form-control" name="drugDate">
							</div>
							<br />
							<button type="submit" class="btn btn-col btn-block">Dodaj nowy lek</button>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

<script src="js/navigation_drugs.js"></script>

</body>

</html>
