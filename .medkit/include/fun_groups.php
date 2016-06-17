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
                                                WHERE email = ?))";

        $result = db_statement($sql, "s", array(&$user));
        return $result;

    }

    function groups_print_table($username){

        require("config/sql_connect.php"); //czy potrzebne tu?

        $result = groups_get_user_groups($username);

        if (mysqli_num_rows($result) > 0) {

            echo
                "<table class='table table-hover'>
                <thead>
                     <tr>
                        <th>Nazwa grupy</th>
                        <th></th>
                        <th></th>
                      </tr>
                </thead>
                <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                
                echo
                    "<tr>".
                        "<td>" . $row["group_name"] . "</td>" .
                        "<td class='hidden'><div class=''>".
                            "<input form='change_group' type='checkbox' name='group_change[]' value='".$row['id']."'>".
                        "</div></td>".
                        "<td class=''>
                            <button type='button' class='btn btn-info btn-change'>Wybierz</button>
                        </td>".
                        "<td class='hidden'><div class=''>".
                            "<input form='leave_groups' type='checkbox' name='groups[]' value='".$row['id']."'>".
                        "</div></td>".
                        "<td class=''>" .
                            "<button type='button' class='btn btn-danger btn-delete'>Zaznacz</button>".
                        "</td>";
                    "</tr>";
            }

            echo
                    "</tbody>
                    </table>
                    <form action='' method='POST' id='leave_groups'>
                        <button type='submit' name='delete-submit' class='btn btn-col btn-block'>Opuść zaznaczone grupy</button>
                    </form>
                    <form action='' method='POST' id='change_group'>
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

        $processed = db_statement($sql, "is", array(&$groupID, &$username));
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

                    $processed = db_statement($sql, "is", array(&$groupID, &$username));
                    $changed = true;

                    break;
                }
            }
        }
        return $changed;
    }

    function groups_get_selected_name($groupID){

        require("config/sql_connect.php");
        

        $sql = "SELECT group_name
                FROM groups
                WHERE id = ?";

        $result = db_statement($sql, "i", array(&$groupID));

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $groupName = $row["group_name"];
        }
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

        $sql = "SELECT id FROM groups WHERE group_name = ?";
        $result1 = db_statement($sql, "s", array(&$group));

        if (mysqli_num_rows($result1) == 1) {
            $sql = "SELECT id FROM users WHERE email = ?";
            $result2 = db_statement($sql, "s", array(&$login));

            if (mysqli_num_rows($result2) == 1) {
                $sql = "INSERT INTO connections (group_id, user_id, admin_rights) VALUES (?,?,?)";/////TU TRZEBA ZMIENIC!!

                $result1 = mysqli_fetch_assoc($result1);
                $result2 = mysqli_fetch_assoc($result2);
                $result1 = $result1["id"];
                $result2 = $result2["id"];
                $admin = 1;

                $processed = db_statement($sql, "iii", array(&$result1, &$result2, &$admin));
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
        

        $sql = "SELECT id FROM groups WHERE group_name = ?";
        $result1 = db_statement($sql, "s", array(&$group));

        if (mysqli_num_rows($result1) == 1) {
            $sql = "SELECT id FROM users WHERE email = ?";
            $result2 = db_statement($sql, "s", array(&$login));

            if (mysqli_num_rows($result2) == 1) {
                $sql = "INSERT INTO connections (group_id, user_id, admin_rights) VALUES (?,?,?)";/////TU TRZEBA ZMIENIC!!

                $result1 = mysqli_fetch_assoc($result1);
                $result2 = mysqli_fetch_assoc($result2);
                $result1 = $result1["id"];
                $result2 = $result2["id"];
                $admin = 0;

                $processed = db_statement($sql, "iii", array(&$result1, &$result2, &$admin));
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
        $sql = "SELECT id FROM groups WHERE group_name = ? and password = ?";
        $result = db_statement($sql, "ss", array(&$group_name, &$password));

        if (mysqli_num_rows($result) == 1) {
            return true;
        }
        else {
            $error = "Nieprawdiłowe dane logowania do grupy";
            return false;
        }
    }

?>