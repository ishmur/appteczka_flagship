<?php
	
	function trim_input($input){
		$input = trim($input);
		$input = stripcslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}

	function login_valid($login, &$error) {
		if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
			if (is_in_database($login, 'users')) {
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

	function is_in_database($entity, $db){

		require("config/sql_connect.php");
		if ($db == 'users') {
			$sql = "SELECT id FROM users WHERE email = '$entity'";
		}
		else if ($db == 'groups'){
			$sql = "SELECT id FROM groups WHERE group_name = '$entity'";
		}
		$result = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		else {
			return false;
		}

	}

	function register($username, $password, $option){

		require("config/sql_connect.php");

		if ($option == 'user') {
			$sql = "INSERT INTO users (email, password)
				VALUES (?,?)";
		}

		else if($option == 'group'){
			$sql = "INSERT INTO groups (group_name, password)
				VALUES (?,?)";
		}

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

	function login_basic_check($login, &$error){
		if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			if (empty($login)){
				$error = "Login cannot be empty";
				return false;
			} else {
				$error = "Login is invalid";
				return false;
			}
		}
	}

	function password_basic_check($password, &$error){
		//Funkcje mogą być w przyszłości rozbudowane
		if (empty($password)){
			$error = "Password cannot be empty";
			return false;
		} else {
			return true;
		}
	}

	function give_admin_rights($group, $login){

		require("config/sql_connect.php");
		$sql = "SELECT id FROM groups WHERE group_name = '$group'";
		$result1 = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result1) == 1) {
			$sql = "SELECT id FROM users WHERE email = '$login'";
			$result2 = mysqli_query($dbConnection, $sql);

			if (mysqli_num_rows($result2) == 1) {
				$sql = "INSERT INTO connections (group_id, user_id, admin_rights) VALUES (?,?,?)";/////TU TRZEBA ZMIENIC!!
				$stmt = mysqli_prepare($dbConnection, $sql);
				if ($stmt === false) {
					trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
				}

				$result1 = mysqli_fetch_assoc($result1);
				$result2 = mysqli_fetch_assoc($result2);
				$result1 = $result1["id"];
				$result2 = $result2["id"];
				$admin = 1;

				$bind = mysqli_stmt_bind_param($stmt, "iii", $result1, $result2, $admin);
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
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function add_to_group($group, $login){/////POLACZYC Z FUNKCJA give_admin_rights

		require("config/sql_connect.php");
		$sql = "SELECT id FROM groups WHERE group_name = '$group'";
		$result1 = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result1) == 1) {
			$sql = "SELECT id FROM users WHERE email = '$login'";
			$result2 = mysqli_query($dbConnection, $sql);

			if (mysqli_num_rows($result2) == 1) {
				$sql = "INSERT INTO connections (group_id, user_id, admin_rights) VALUES (?,?,?)";/////TU TRZEBA ZMIENIC!!
				$stmt = mysqli_prepare($dbConnection, $sql);
				if ($stmt === false) {
					trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
				}

				$result1 = mysqli_fetch_assoc($result1);
				$result2 = mysqli_fetch_assoc($result2);
				$result1 = $result1["id"];
				$result2 = $result2["id"];
				$admin = 0;

				$bind = mysqli_stmt_bind_param($stmt, "iii", $result1, $result2, $admin);
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
			} else {
				return false;
			}
		} else {
			return false;
		}
	}



	function is_group_name_valid($group_name, &$error){
		//Funkcje mogą być w przyszłości rozbudowane
		if (empty($group_name)){
			$error = "Group name cannot be empty";
			return false;
		} else {
			if(is_in_database($group_name, 'groups')){
				$error = "This group name is already used. Please choose different one";
				return false;
			}
			return true;
		}
	}

	function does_group_exist($group_name, &$error){
		if (empty($group_name)){
			$error = "Group name cannot be empty";
			return false;
		} else {
			if(!is_in_database($group_name, 'groups')){
				$error = "This group does not exist";
				return false;
			}
			return true;
		}
	}

	function correct_password_group($group_name, $password, &$error){
		require("config/sql_connect.php");
		$password = md5($password);
		$sql = "SELECT id FROM groups WHERE group_name = '$group_name' and password = '$password'";
		$result = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result) == 1) {
			return true;
		}
		else {
			$error = "Group name or password incorrect";
			return false;
		}
	}
		
	function correct_password($username, $password){
		require("config/sql_connect.php");
		$sql = "SELECT id FROM users WHERE email = '$username' and password = '$password'";
		$result = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result) == 1) {
			return true;
		}
		else {
			return false;
		}
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

	function get_all_groups(){
		require("config/sql_connect.php");

		$sql = "SELECT group_name FROM groups";
		$result = mysqli_query($dbConnection, $sql);
		return $result;
	}

	function get_users_groups($user){
		require("config/sql_connect.php");

		$sql = "SELECT group_name FROM groups WHERE id IN (SELECT group_id FROM `connections` WHERE user_id IN (SELECT id FROM `users` WHERE email = '$user'))";
		$result = mysqli_query($dbConnection, $sql);
		return $result;
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

	function db_drugs_print_table($group_name){
		require("config/sql_connect.php");

		$sql = "SELECT name, price, amount, overdue, user_added FROM DrugsDB WHERE group_id = (SELECT id FROM groups WHERE group_name = '$group_name')";
		$result = mysqli_query($dbConnection, $sql);
		
		if (mysqli_num_rows($result) > 0) {

			echo
			"<thead>
			  <tr>
			  	<th></th>
				<th>Nazwa leku</th>
				<th>Cena w złotówkach</th>
				<th>Ilość</th>
				<th>Data ważności</th>
				<th>Kto dodał</th>
			  </tr>
			</thead>
			<tbody>";

			// output data of each row
			while ($row = mysqli_fetch_assoc($result)) {
				echo
					"<tr>".
						"<td>" .$row["id"]. "</td>" .
						"<td>" . $row["name"] . "</td>" .
						"<td>" . $row["price"] . "</td>" .
						"<td>" . $row["amount"] . "</td>" .
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