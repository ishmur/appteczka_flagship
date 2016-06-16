<?php

    function groups_get_user_groups($user){

        require("config/sql_connect.php");

        $sql = "SELECT group_name, id 
                    FROM groups 
                    WHERE id IN 
                                (SELECT group_id 
                                FROM `connections` 
                                WHERE user_id IN 
                                                (SELECT id 
                                                FROM `users` 
                                                WHERE email = '$user'))";

        $result = mysqli_query($dbConnection, $sql);
        return $result;

    }

    function groups_print_table($username){

        require("config/sql_connect.php");

        $result = groups_get_user_groups($username);

        if (mysqli_num_rows($result) > 0) {

            echo
                "<form action='' method='POST'>
                <table class=\"table table-hover\">
                <thead>
                     <tr>
                        <th></th>
                        <th>Nazwa grupy</th>
                        <th></th>
                      </tr>
                </thead>
                <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                
                $redirectUrl = "'group_choose.php?change=" . $row["id"] . "'";
                echo
                    "<tr>".
                    "<td class=''>" . "<input type='checkbox' name='groups[]' value='".$row["id"]."'></td>" .
                    "<td>" . $row["group_name"] . "</td>" .
                    "<td>" . "<a href=$redirectUrl>Wybierz</a>" . "</td>" .
                    "</tr>";
            }

            echo
                "</tbody>
                </table>
                <button type=\"submit\" class=\"btn btn-col btn-block\">Opuść zaznaczone grupy</button>
                </form>";

        } else {

            echo
                "<p>Nie należysz do żadnej grupy.</p>" .
                "<p><a href='group_join.php'>Dołącz do istniejącej grupy</a> lub <a href='group_new.php'>załóż nową.</a></p>";

        }

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

        $result = groups_get_user_groups($username);
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

    function groups_get_selected_name($groupID){

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

    function groups_get_all_names(){

        require("config/sql_connect.php");

        $sql = "SELECT group_name 
                    FROM groups";

        $result = mysqli_query($dbConnection, $sql);

        return $result;

    }

    function groups_give_admin_rights($group, $login){

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

    function groups_add_user_to_group($group, $login){/////POLACZYC Z FUNKCJA give_admin_rights

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

    function groups_check_name_valid($group_name, &$error){
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

    function groups_check_if_exists($group_name, &$error){
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

    function groups_check_password_correct($group_name, $password, &$error){
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

?>