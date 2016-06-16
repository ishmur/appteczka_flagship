<?php

	function trim_input($input){
		$input = trim($input);
		$input = stripcslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}

	function db_statement(){
		$args = func_get_args();
		if(count($args) < 4){
			trigger_error("Not enough input arguments");
			return false;
		} else {
			$dbConnection = $args[0];
			$sql = $args[1];
			$types = $args[2];
			$params = $args[3];

			$stmt = mysqli_prepare($dbConnection,$sql);
			if ($stmt === false) {
				trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
				return false;
			}

			$bind = call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $params));
			if ($bind === false) {
				trigger_error('Bind param failed!', E_USER_ERROR);
				return false;
			}

			$exec = mysqli_stmt_execute($stmt);
			if ($exec === false) {
				trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
				return false;
			}

			mysqli_stmt_close($stmt);
			mysqli_close($dbConnection);
			return true;

		}
	}

	function login_valid($login, &$error) {
		if (filter_var($login, FILTER_VALIDATE_EMAIL)) {

			if (is_in_database($login, 'users')) {
				$error = "Podany adres email jest już przypisany do konta";
				return false;
			} else {
				return true;
			}
		} else {
			if (empty($login)){
				$error = "Pole nie może być puste";
				return false;
			} else {
				$error = "Nieprawidłowy adres email";
				return false;
			}
		}
	}

	function password_valid($password, $password_check, &$error) {
		if ($password != $password_check){
			$error = "Wartości obu pól haseł muszą być identyczne";
			return false;
		} else {
			if (empty($password)){
				$error = "Pole nie może być puste";
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

		$processed = db_statement($dbConnection, $sql, "ss", array(&$username, &$password));
		return $processed;
	}

	function login_basic_check($login, &$error){
		if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			if (empty($login)){
				$error = "Pole nie może być puste";
				return false;
			} else {
				$error = "Nieprawidłowa nazwa użytkownika";
				return false;
			}
		}
	}

	function password_basic_check($password, &$error){
		//Funkcje mogą być w przyszłości rozbudowane
		if (empty($password)){
			$error = "Pole nie może być puste";
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
			$error = "Grupa musi mieć nazwę";
			return false;
		} else {
			if(is_in_database($group_name, 'groups')){
				$error = "Grupa o podanej nazwie już istnieje";
				return false;
			}
			return true;
		}
	}

	function does_group_exist($group_name, &$error){
		if (empty($group_name)){
			$error = "Grupa musi mieć nazwę";
			return false;
		} else {
			if(!is_in_database($group_name, 'groups')){
				$error = "Grupa o podanej nazwie nie istnieje";
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
			$error = "Nieprawdiłowe dane logowania do grupy";
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

		/*
		$sql = "INSERT INTO DrugsDB (name, price, overdue, username)
		VALUES (?,?,?,?)";
		*/

		$sql = "INSERT INTO DrugsDB (group_id, name, price, amount, overdue, user_added)
		VALUES (23,?,?,10,?,?)";

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

		$sql = "SELECT group_name, id FROM groups WHERE id IN (SELECT group_id FROM `connections` WHERE user_id IN (SELECT id FROM `users` WHERE email = '$user'))";
		$result = mysqli_query($dbConnection, $sql);
		return $result;
	}

	function users_get_group_id($username){

		require("config/sql_connect.php");

		$sql = "SELECT show_group_id
					FROM users
					WHERE email = ?";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "s", $username);
		if ($bind === false) {
			trigger_error('Bind param failed!', E_USER_ERROR);
		}

		$exec = mysqli_stmt_execute($stmt);
		if ($exec === false) {
			trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
		}
		else {
			$result = mysqli_stmt_get_result($stmt);
			if (mysqli_num_rows($result) == 1) {
				$row = mysqli_fetch_assoc($result);
				$groupID = $row["show_group_id"];
			}
		}

		mysqli_stmt_close($stmt);
		mysqli_close($dbConnection);

		return $groupID;

	}

	function drugs_new_record($drugName, $drugPrice, $drugDate, $username, $groupID){

		require("config/sql_connect.php");

		$sql = "INSERT INTO DrugsDB (name, price, overdue, user_added, group_id)
				VALUES (?,?,?,?,?)";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "sissi", $drugName, $drugPrice, $drugDate, $username, $groupID);
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

	function drugs_print_table($groupID){

		require("config/sql_connect.php");

		$sql = "SELECT id, name, price, amount, overdue, user_added 
				FROM DrugsDB 
				WHERE group_id = $groupID";

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
						"<td class=''>" . "<input type='checkbox' name='drugs[]' value='".$row["id"]."'></td>" .
						"<td>" . $row["name"] . "</td>" .
						"<td>" . $row["price"] . "</td>" .
						"<td>" . $row["amount"] . "</td>" .
						"<td>" . date("d-m-Y", strtotime($row["overdue"])). "</td>" .
						"<td>" . $row["user_added"] . "</td>" .
					"</tr>";
			}
		}

		echo
			"</tbody>";

		mysqli_close($dbConnection);
	}

	function drugs_delete_record($drugID, $groupID){

		require("config/sql_connect.php");

		$sql = "DELETE FROM DrugsDB 
				WHERE id = ?
				AND group_id = ?";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "ii", $drugID, $groupID);
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

	function drugs_overdue_check_date($groupID){

		require("config/sql_connect.php");

		$dateNow = date_create(date('d-m-Y'));

		$sql = "SELECT overdue
				FROM DrugsDB 
				WHERE group_id = $groupID";

		$result = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result) > 0){

			while ($row = mysqli_fetch_assoc($result)){

				$dateOverdue = date_create(date("d-m-Y", strtotime($row["overdue"])));
				$dateDiffInterval = date_diff($dateNow,$dateOverdue);
				$dateDiffInt = (int)($dateDiffInterval->format("%R%a")); //format "%R%a" = %R: +/- sign, %a: days

				if ( $dateDiffInt < 0) {

					return true;

				}


			}
		}

		mysqli_close($dbConnection);

		return false;

	}

	function drugs_overdue_print_table($groupID, $soonBool=false){

		require("config/sql_connect.php");

		$dateNow = date_create(date('d-m-Y'));
		$soonInt = 14;

		$sql = "SELECT id, name, overdue, amount
				FROM DrugsDB 
				WHERE group_id = $groupID";

		$result = mysqli_query($dbConnection, $sql);

		if (mysqli_num_rows($result) > 0) {

			if ($soonBool){

				echo
				"<thead>
				  <tr>
					<th></th>
					<th>Nazwa leku</th>
					<th>Ilość</th>
					<th>Data ważności</th>
					<th>Pozostało dni</th>
				  </tr>
				</thead>
				<tbody>";

				while ($row = mysqli_fetch_assoc($result)) {

					$dateOverdue = date_create(date("d-m-Y", strtotime($row["overdue"])));
					$dateDiffInterval = date_diff($dateNow,$dateOverdue);
					$dateDiffInt = (int)($dateDiffInterval->format("%R%a")); //format "%R%a" = %R: +/- sign, %a: days

					if ( $dateDiffInt > 0 && $dateDiffInt < $soonInt){
						echo
							"<tr>".
								"<td class=''>" . "<input type='checkbox' name='overdue[]' value='".$row["id"]."'></td>" .
								"<td>" . $row["name"] . "</td>" .
								"<td>" . $row["amount"] . "</td>" .
								"<td>" . date_format($dateOverdue, "d-m-Y") . "</td>" .
								"<td>" . $dateDiffInterval->format("%a"); "</td>" .
							"</tr>";
					}

				}

			} else {

				echo
				"<thead>
				  <tr>
					<th></th>
					<th>Nazwa leku</th>
					<th>Ilość</th>
				  </tr>
				</thead>
				<tbody>";

				while ($row = mysqli_fetch_assoc($result)) {

					$dateOverdue = date_create(date("d-m-Y", strtotime($row["overdue"])));
					$dateDiffInterval = date_diff($dateNow,$dateOverdue);
					$dateDiffInt = (int)($dateDiffInterval->format("%R%a")); //format "%R%a" = %R: +/- sign, %a: days

					if ( $dateDiffInt < 0){
						echo
							"<tr>".
							"<td class=''>" . "<input type='checkbox' name='overdue[]' value='".$row["id"]."'></td>" .
							"<td>" . $row["name"] . "</td>" .
							"<td>" . $row["amount"] . "</td>" .
						"</tr>";
					}
				}

			}

		}

		echo
		"</tbody>";

		mysqli_close($dbConnection);
	}

	function specif_new_record($drugName, $drugEAN, $drugUnit, $drugSize, $drugActive){

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

	function specif_print_table(){
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
						"<td class=''>" . "<input type='checkbox' name='specif[]' value='".$row["id_drugs_specification"]."'></td>" .
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

	function specif_delete_record($specifID){

		require("config/sql_connect.php");

		$sql = "DELETE FROM drugs_specification 
		WHERE id_drugs_specification = ?";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "i", $specifID);
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

	function groups_print_table($username){
		require("config/sql_connect.php");

		$result = get_users_groups($username);

		echo
			"<thead>
		 		 <tr>
					<th></th>
					<th>Nazwa grupy</th>
					<th></th>
				  </tr>
			</thead>
			<tbody>";

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while ($row = mysqli_fetch_assoc($result)) {
				$redirectUrl = "'group_choose.php?change=" . $row["id"] . "'";
				echo
					"<tr>".
					"<td class=''>" . "<input type='checkbox' name='groups[]' value='".$row["id"]."'></td>" .
					"<td>" . $row["group_name"] . "</td>" .
					"<td>" . "<a href=$redirectUrl>Wybierz</a>" . "</td>" .
					"</tr>";
			}
		}

		echo
		"</tbody>";

		mysqli_close($dbConnection);
	}

	function groups_leave($groupID, $username){

		require("config/sql_connect.php");

		$sql = "DELETE FROM connections 
			WHERE group_id = ?
			AND user_id = 
				(SELECT id 
				FROM users 
				WHERE email = ?)";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "is", $groupID, $username);
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

	function groups_change($groupID, $username, $setNULL=false){

		require("config/sql_connect.php");

		$result = get_users_groups($username);
		$changed = false;

		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if ($row["id"] == $groupID) {

					$sql = "UPDATE users 
							SET show_group_id = ?
							WHERE email = ?";

					$stmt = mysqli_prepare($dbConnection,$sql);
					if ($stmt === false) {
						trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
					}

					$bind = mysqli_stmt_bind_param($stmt, "is", $groupID, $username);
					if ($bind === false) {
						trigger_error('Bind param failed!', E_USER_ERROR);
					}

					if ($setNULL == true) {
						$groupID = null;
					}

					$exec = mysqli_stmt_execute($stmt);
					if ($exec === false) {
						trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
					}

					mysqli_stmt_close($stmt);
					$changed = true;

					break;
				}
			}
		}

		mysqli_close($dbConnection);

		return $changed;
	}

	function groups_get_name($groupID){

		require("config/sql_connect.php");

		$sql = "SELECT group_name
 				FROM groups
				WHERE id = ?";

		$stmt = mysqli_prepare($dbConnection,$sql);
		if ($stmt === false) {
			trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
		}

		$bind = mysqli_stmt_bind_param($stmt, "i", $groupID);
		if ($bind === false) {
			trigger_error('Bind param failed!', E_USER_ERROR);
		}

		$exec = mysqli_stmt_execute($stmt);
		if ($exec === false) {
			trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
		}
		else {
			$result = mysqli_stmt_get_result($stmt);
			if (mysqli_num_rows($result) == 1) {
				$row = mysqli_fetch_assoc($result);
				$groupName = $row["group_name"];
			}
		}

		mysqli_stmt_close($stmt);
		mysqli_close($dbConnection);

		return $groupName;

	}

?>