<?php
	
	function trim_input($input){
		$input = trim($input);
		$input = stripcslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}

	function login_valid($login, &$error) {
		if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
			if (is_login_in_database($login)) {
				$error = "User with this name already exists";
				return false;
			} else {
				return true;
			}
		} else {
			if (empty($login)){
				$error = "Login cannot be empty";
				return false;
			} else {
				$error = "E-mail is invalid";
				return false;
			}
		}
	}

	function password_valid($password, $password_check, &$error) {
		if ($password != $password_check){
			$error = "Password confirmation is different from password";
			return false;
		} else {
			if (empty($password)){
				$error = "Password cannot be empty";
				return false;
			} else {
				return true;
			}
		}
	}

	function is_login_in_database($login){

		require("config/sql_connect.php");
		$sql = "SELECT id FROM users WHERE email = '$login'";
		$result = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		else {
			return false;
		}

	}

	function register_user($username, $password){

		require("config/sql_connect.php");

		$sql = "INSERT INTO users (email, password)
			VALUES (?,?)";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "ss", $username, $password);
		if ($bind === false) {
			trigger_error('Bind param failed!', E_USER_ERROR);
		}

		$exec = mysqli_stmt_execute($stmt);
		if ($exec === false) {
			trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
		}

		mysqli_stmt_close($stmt);
		mysqli_close($dbConnection);
		return true;
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
		//dlaczego "siss" a nie "sdss"
		
		$exec = mysqli_stmt_execute($stmt);
		if ($exec === false) {
			trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
		}

		mysqli_stmt_close($stmt);
		mysqli_close($dbConnection);
	}
	
	function db_drugs_new_specification($drugName, $drugEAN, $drugUnit, $drugSize, $drugActive){
		
		require("config/sql_connect.php");

		$sql = "INSERT INTO drugs_specification (name, ean, unit, package_size, active)
		VALUES (?,?,?,?,?)";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "sssis", $drugName, $drugEAN, $drugUnit, $drugSize, $drugActive);
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

		echo
			"<thead>
			  <tr>
			  	<th></th>
				<th>Nazwa leku</th>
				<th>Cena w złotówkach</th>
				<th>Data ważności</th>
				<th>Kto dodał</th>
			  </tr>
			</thead>
			<tbody>";
		
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while ($row = mysqli_fetch_assoc($result)) {
				echo
					"<tr>".
						"<td class=''>" . "<input type='checkbox' name='drugs[]' value='".$row["id"]."'></td>" .
						"<td>" . $row["name"] . "</td>" .
						"<td>" . $row["price"] . "</td>" .
						"<td>" . $row["overdue"] . "</td>" .
						"<td>" . $row["username"] . "</td>" .
					"</tr>";
			}
		}
		
		echo
			"</tbody>";
		
		mysqli_close($dbConnection);
	}
	
	function db_drugs_print_table_specif(){
		require("config/sql_connect.php");

		$sql = "SELECT name, ean, package_size, unit, active, id_drugs_specification FROM drugs_specification";
		$result = mysqli_query($dbConnection, $sql);

		echo
			"<thead>
			  <tr>
				<th></th>
				<th>Nazwa leku</th>
				<th>Kod EAN</th>
				<th>Ilość leku w opakowaniu</th>
				<th>Jednostka</th>
				<th>Substancja czynna</th>
			  </tr>
			</thead>
			<tbody>";
		
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while ($row = mysqli_fetch_assoc($result)) {
				echo
					"<tr>".
						"<td class=''>" . "<input type='checkbox' name='drugs[]' value='".$row["id_drugs_specification"]."'></td>" .
						"<td>" . $row["name"] . "</td>" .
						"<td>" . $row["ean"] . "</td>" .
						"<td>" . $row["package_size"] . "</td>" .
						"<td>" . $row["unit"] . "</td>" .
						"<td>" . $row["active"] . "</td>" .
					"</tr>";
			}
		}
		
		echo
			"</tbody>";
		
		mysqli_close($dbConnection);
	}

	function db_drugs_delete_record($drugID){

		require("config/sql_connect.php");

		$sql = "DELETE FROM drugs_specification 
		WHERE id_drugs_specification = ?";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "i", $drugID);
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

?>