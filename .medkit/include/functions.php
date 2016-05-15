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
		VALUES (?,?,?,?)";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "siss", $name, $price, $overdue, $username);
		if ($bind === false) {
			trigger_error('Bind param failed!', E_USER_ERROR);
		}

		$exec = mysqli_stmt_execute($stmt);
		if ($exec === false) {
			trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
		}

		mysqli_stmt_close($stmt);
		mysqli_close($dbConnection);
	}

	function db_drugs_print_table(){
		require("config/sql_connect.php");

		$sql = "SELECT name, price, overdue, username FROM DrugsDB";
		$result = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while ($row = mysqli_fetch_assoc($result)) {
				echo
					"<tr>".
						"<td>" . $row["name"] . "</td>" .
						"<td>" . $row["price"] . "</td>" .
						"<td>" . $row["overdue"] . "</td>" .
						"<td>" . $row["username"] . "</td>" .
					"</tr>";
			}
		}
		mysqli_close($dbConnection);
	}
?>