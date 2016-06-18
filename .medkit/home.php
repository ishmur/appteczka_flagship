<?php
	session_start();
	
	$show_modal;

	require_once("include/functions.php");



//	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])){
//		if (!correct_password($_COOKIE['username'], $_COOKIE['password'])) {
//			header("Location: index.php?logout=1");
//			exit();
//		}
//	}
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}
	
	if($_SESSION['drugsOverdueModal'] == "show"){
		$show_modal = "style='display:block'";
		$_SESSION['drugsOverdueModal'] = "hide";
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
	$activity = 'class="active"'; // set "active" class for current page
	$header = "Ostatnia aktywność"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">

			<? parse_feed($_SESSION['groupID']); ?>
		</div>
	</div>

	
</div>


<!-- Modal -->
<div class="modal" role="dialog" <?php echo $show_modal ?> >
	<div class="modal-dialog">
	<!-- Modal content-->
		
		<div class="modal-content" >

			<div class="modal-header">
                <h4 style="color:white;"><i class="fa fa-hourglass-end"></i> Alert: termin ważności</h4>
            </div>
		
			<div class="modal-body">
				<h5><b>W apteczce znajdują się przeterminowane leki.</b></h5>
				<p>Proszę przejść do zakładki "Lista leków przeterminowanych" i sprawdzić, których leków nie należy stosować</p>
			</div>
			
			<div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" id="modalCancelBtn">
					<i class="fa fa-ban"></i> Ok, rozumiem.
				</button>
				<a href="drugs_overdue.php"><button type="button" class="btn btn-success pull-right">
					<i class="fa fa-share"></i> Przejdź do listy leków.
				</button></a>
			</div>

		</div>
    </div>
</div>

<script src="js/home.js"></script>

</body>

</html>
