<?php
	session_start();

	require_once("include/functions.php");
	
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

  <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php 
	$statistics = 'class="active"'; // set "active" class for current page
	$header = "Statystyki"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<div class="fluid-container">
	<?php if(empty($_SESSION["groupID"])) { ?>
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">
			<div class="col-md-8 col-md-offset-2">
				<div class="container-fluid">
					<div class="col-md-12 inline-element-center">
						<h1 style="color:red">Nie należysz do żadnej grupy - proszę wybrać grupę.</h1><br>
						<a href="group_choose.php"><button type="button" class="btn btn-danger col-xs-12">Wybierz grupę</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php } else { ?>
<div class="row" style="background-color: #cce;">
	<div class="col-sm-3 col-sm-offset-3"><h3>Koszt leków, które zutylizowano</h3></div>
	<div class="col-sm-3">
		<form action='' method='POST' id='utilized' class="form-inline">
			<button type='submit' name='time' value = 'week' class='btn btn-col btn-block btn-sm'>W ostatnim tygodniu</button>
			<button type='submit' name='time' value = 'month' class='btn btn-col btn-block btn-sm'>W ostatnim miesiącu</button>
		</form><form action='' method='POST' id='utilized' class="form-inline sm-top-buffer">
			<button type='submit' name='time' value = 'specific' class='btn btn-col btn-block btn-sm'>W określonym poniżej zakresie</button>
			<div align="center" class="sm-top-buffer">
				<label for="utd-f"> OD: </label> <input type="date" class="form-control" name="utilized_from" id="utd-f" required><br>
				<label for="utd-t"> DO: </label> <input type="date" class="form-control" name="utilized_to" id="utd-t" required>
			</div>
		</form>
	</div>
	<div class="col-md-3">
		<?php
		if(!isset($_POST['time'])) $_POST['time'] = 'month';
		print_utilized_stats($_SESSION['groupID'], $_POST['time'], $_POST['utilized_from'], $_POST['utilized_to']);
		?>
	</div>
</div>
<div class="row top-buffer" style="background-color: #cce">
	<div class="col-sm-3 col-sm-offset-3"><h3>Koszt leków, które ulegną przeterminowaniu</h3></div>
	<div class="col-sm-3">
		<form action='' method='POST' id='to_utilize' class="form-inline">
			<button type='submit' name='time_to' value = 'week' class='btn btn-col btn-block btn-sm'>W następnym tygodniu</button>
			<button type='submit' name='time_to' value = 'month' class='btn btn-col btn-block btn-sm'>W następnym miesiącu</button>
		</form><form action='' method='POST' id='utilized' class="form-inline sm-top-buffer">
			<button type='submit' name='time_to' value = 'specific' class='btn btn-col btn-block btn-sm'>W określonej przyszłości</button>
			<div align="center" class="sm-top-buffer">
				<label for="tout-f"> OD: </label> <input type="date" class="form-control" name="to_utilize_from" id="tout-f" required><br>
				<label for="tout-t"> DO: </label> <input type="date" class="form-control" name="to_utilize_to" id="tout-t" required>
			</div>
		</form>
	</div>
	<div class="col-md-3">
		<?php
		if(!isset($_POST['time_to'])) $_POST['time_to'] = 'month';
			print_to_utilize_stats($_SESSION['groupID'], $_POST['time_to'], $_POST['to_utilize_from'], $_POST['to_utilize_to']);
		?>
	</div>
</div>
</div>
<div class="row top-buffer" style="background-color: #cce">
	<div class="col-sm-3 col-sm-offset-3"><h3>Podsumowanie zawartości apteczki</h3></div>
	<div class="col-sm-6">
		<?
			if(!isset($_GET['p'])) $_GET['p'] = 1;
			print_drugs_by_packages($_SESSION['groupID'], $_GET['p']);
		?>
	</div>
</div>
	<?php } ?>
<script src="js/statistics.js"></script>
</body>

</html>
