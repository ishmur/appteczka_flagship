<?php
	session_start();

	require_once("include/functions.php");
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

	$groupID = $_SESSION["groupID"];
	$username = $_SESSION["username"];

	if(isset($_POST['drugs'])) {
		foreach ($_POST['drugs'] as $drugID) {
			drugs_delete_record($username, $drugID, $groupID);
		}
	}

    if(isset($_POST["drugs_edit"])){
        //tba
    }

    if(isset($_POST["drugs_take_amount"])){
        $amount = $_POST["drugs_take_amount"];
        $id = $_POST["drugs_take_id"];
        $amount_present = $_POST["drugs_take_present"];
        drugs_take_drug($amount, $id, $amount_present);
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
  <link rel="stylesheet" type="text/css" href="css/modal.css">
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

<!-- Modal -->
<div class="modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->

		<div class="modal-content" >

			<div class="modal-header">
				<h4 style="color:white;"><i class="fa fa-hourglass-end"></i> Weź lek</h4>
			</div>

			<div class="modal-body" id="ajaxCall">
                <!-- table from AJAX call -->
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" id="modalCancelBtn">
					<i class="fa fa-ban"></i> Anuluj
				</button>
                <form action='' method='POST' id='take_drugs'>
                    <button type='submit' name='take-submit' class='btn btn-success pull-right' id="take_drugs_submit">
                        <i class="fa fa-share"></i> Akceptuj
                    </button>
                </form>
			</div>

		</div>
	</div>
</div>

<script src="js/drugs_overview.js"></script>

</body>

</html>