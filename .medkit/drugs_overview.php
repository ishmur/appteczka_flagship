<?php
	session_start();

	require_once("include/functions.php");
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

	$groupID = $_SESSION["groupID"];

	if(isset($_POST['drugs'])) {
		foreach ($_POST['drugs'] as $drugID) {
			drugs_delete_record($drugID, $groupID);
		}
	}

    if(isset($_POST["drugs_edit"])){
        //tba
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
	$drugsOverview = 'class="active"'; // set "active" class for current page
	$showDropdownDrugs = "show"; // set drugs side-menu item to be permanently visible
	$header = "Przegląd leków"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br />

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">
		
			<div class="col-md-8 col-md-offset-2">
				<div class="container-fluid">
					<div class="col-md-12">
						<form class="action="#">
							<div class="form-group">
								<label for="drugsSearch"><i class="fa fa-question-circle"></i> Szukaj leku...</label>
								<input type="text" class="form-control" id="drugsSearch" placeholder="Wpisz nazwę poszukiwanego leku">
							<br />
							<button type="submit" class="btn btn-col btn-block">Szukaj</button>
							</div>
						</form>
						<div class="container-fluid">
							
							  <br /><h2>Wyniki wyszukiwania</h2><hr />					  
							  
								
									<?php
										drugs_print_table($groupID);
									?>

						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
</div>

<script src="js/drugs_overview.js"></script>

</body>

</html>