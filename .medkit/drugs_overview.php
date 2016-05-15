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
	$drugsOverview = 'class="active"'; // set "active" class for current page
	$showDropdown = "show"; // set drugs side-menu item to be permanently visible
	$header = "Przegląd leków"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br />

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">
		
			<div class="col-sm-10">
				<div class="container-fluid">
					<div class="col-sm-10">
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
							  <table class="table table-hover">
								<thead>
								  <tr>
									<th>Nazwa leku</th>
									<th>Ilość</th>
									<th>Cena w złotówkach</th>
									<th>Data ważności</th>
								  </tr>
								</thead>
								<tbody>
								  <tr>
									<td>Apap</td>
									<td>4</td>
									<td>40</td>
									<td>13.04.2018</td>
								  </tr>
								  <tr>
									<td>Acodin</td>
									<td>200</td>
									<td>14</td>
									<td>13.04.2018</td>
								  </tr>
								  <tr>
									<td>Zyrtec</td>
									<td>1</td>
									<td>8</td>
									<td>13.04.2018</td>
								  </tr>
								</tbody>
							  </table>
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
