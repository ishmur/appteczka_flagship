<?php
	session_start();

	require_once("include/functions.php");
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

    $username = $_SESSION['username'];
	$groupID = $_SESSION["groupID"];

	if (isset($_POST['overdue'])){
		foreach ($_POST['overdue'] as $drugID) {
			drugs_delete_record($username, $drugID, $groupID);
		}
		$_SESSION['deleted_drugs'] = true;
		header("Location: drugs_overdue.php");
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
	$drugsOverdue = 'class="active"'; // set "active" class for current page
	$showDropdownDrugs = "show"; // set drugs side-menu item to be permanently visible
	$header = "Lista leków przeterminowanych"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br />

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">

            <?php if(isset($_SESSION['deleted_drugs'])){ ?>
                <div class="alert alert-success">
                    Usunięto zaznaczone leki!
                </div>
            <?php $_SESSION['deleted_drugs'] = null; } ?>

            <?php if(empty($groupID)) { ?>

                <div class="col-md-8 col-md-offset-2">
                    <div class="container-fluid">
                        <div class="col-md-12 inline-element-center">
                            <h1 style="color:red">Nie należysz do żadnej grupy - proszę wybrać grupę.</h1><br>
                            <a href="group_choose.php"><button type="button" class="btn btn-danger col-xs-12">Wybierz grupę</button></a>
                        </div>
                    </div>
                </div>

            <?php } else { ?>

			<div class="col-md-8 col-md-offset-2">
				<div class="container-fluid">
					<div class="col-md-12">
						<div class="container-fluid">
							  <br><h2>Wykaz przeterminowanych leków:</h2><hr>

									<?php
										if(!isset($_GET['p'])) $_GET['p'] = 1;
										drugs_overdue_print_table($groupID, $_GET['p']);
									?>

						</div>
					</div>
				</div>
			</div>

            <?php } ?>

		</div>
	</div>
	
</div>

<script src="js/drugs_overdue.js"></script>

</body>

</html>
