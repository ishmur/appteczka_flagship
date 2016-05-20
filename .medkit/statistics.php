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
	$statistics = 'class="active"'; // set "active" class for current page
	$header = "Statystyki"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>



</body>

</html>
