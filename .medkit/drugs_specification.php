<?php
	session_start();

	require_once("include/functions.php");

	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$drugName = $_POST['drugName'];
		$drugEAN = $_POST['drugEAN'];
		$drugUnit = $_POST['drugUnit'];
		$drugSize = $_POST['drugSize'];
		$drugActive = $_POST['drugActive'];

		//Validate inputs - TBA

		db_drugs_new_specification($drugName, $drugEAN, $drugUnit, $drugSize, $drugActive);

		header("Location: drugs_specification.php");
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
  <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php 
	$drugsSpecification = 'class="active"'; // set "active" class for current page
	$showDropdownDrugs = "show"; // set drugs side-menu item to be permanently visible
	$header = "Dodaj specyfikację leku"; // set header string for current page
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
								<label for="drugName"><i class="fa fa-tags"></i> Nazwa leku</label>
								<input type="text" class="form-control" name="drugName" placeholder="Wpisz nazwę nowego leku" required="required">
							</div>
							<div class="form-group">
								<label for="drugName"><i class="fa fa-hashtag"></i> Kod EAN</label>
								<input type="text" class="form-control" name="drugEAN" placeholder="Wpisz kod EAN" required="required">
							</div>
							<div class="form-group">
								<label>
									<i class="fa fa-pencil-square-o"></i> Rodzaj leku w opakowaniu</label>
								</label><br>
								<div class="radio">
									<label>
										<input type="radio" name="drugUnit" value="ml" required="required">płyn (wyrażony w ml)
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="drugUnit" value="tabletki">tabletki
									</label>
								</div>
							</div>
							<div class="form-group">
								<label for="drugPrice"><i class="fa fa-database"></i> Ilość leku w opakowaniu</label>
								<input type="number" min="1" class="form-control" name="drugSize" placeholder="Wpisz ilość leku w opakowaniu" required="required">
							</div>
							<div class="form-group">
								<label for="drugPrice"><i class="fa fa-flask"></i> Substancja czynna</label>
								<input type="text" class="form-control" name="drugActive" placeholder="Wpisz substancję czynną" required="required">
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

</body>

</html>
