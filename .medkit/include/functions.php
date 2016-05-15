<?php
	
	function trim_input($input){
		$input = trim($input);
		$input = stripcslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}
	
	function check_login($login, $password){
		if ($password == 'admin'){
			return true;
		}
		else 
			return false;
	}
	
	function db_drugs_new_record($name, $price, $overdue, $username){
		
		require("config/sql_connect.php");

		$sql = "INSERT INTO DrugsDB (name, price, overdue, username)
		VALUES ('$name', '$price', '$overdue', '$username')";

		if (mysqli_query($dbConnection, $sql)) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($dbConnection);
		}
		
		mysqli_close($dbConnection);
	}
?>