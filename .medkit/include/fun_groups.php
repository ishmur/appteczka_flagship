<?php

    function groups_get_user_groups($user, $page, $pag = false){

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

        if($pag != false) $sql = paginate($sql, "group_choose.php", 10, $page, array("s", array(&$user)));

        $result = db_statement($sql, "s", array(&$user));
        return $result;

    }

    function groups_is_user_in_group($username, $group_name, &$error_text, &$error_flag){

        require("config/sql_connect.php");

        $sql = "SELECT id FROM groups WHERE group_name = ?";
        $result1 = db_statement($sql, "s", array(&$group_name));
        $sql = "SELECT id FROM users WHERE email = ?";
        $result2 = db_statement($sql, "s", array(&$username));

        $sql = "SELECT * FROM connections WHERE user_id = ? AND group_id = ?";
        $result1 = mysqli_fetch_assoc($result1);
        $result2 = mysqli_fetch_assoc($result2);
        $result1 = $result1['id'];
        $result2 = $result2['id'];

        $result = db_statement($sql, "ii", array(&$result2, &$result1));
        if (mysqli_num_rows($result) > 0) {
            $error_text = "Należysz już do tej grupy!";
            $error_flag = "has-error";
            return true;
        }
        else
            return false;

    }

    function groups_print_table($username, $page){

        require("config/sql_connect.php");

        $result = groups_get_user_groups($username, $page, true);

        if (mysqli_num_rows($result) > 0) {

            echo
                "<h2>Lista grup, do których należysz:</h2><hr />
                <table class='table table-hover'>
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
                            <button type='button' class='btn btn-info btn-change'>Ustaw jako domyślną</button>
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
                    "<div class='col-md-12 inline-element-center'>
                        <h1>Nie należysz do żadnej grupy.</h1><br>
                        <div class='container-fluid'>
                            <div class='col-sm-6'>
                                <a href='group_join.php'><button type='button' class='btn btn-warning col-xs-12'>Dołącz do istniejącej grupy</button></a>
                            </div>
                            <div class='col-sm-6'>
                                <a href='group_new.php'><button type='button' class='btn btn-info col-xs-12'>Załóż nową grupę</button></a>
                            </div>
                        </div>
                    </div>";

        }

        mysqli_close($dbConnection);

    }

    function groups_leave($groupID, $username){

        $sql = "DELETE FROM connections 
                WHERE group_id = ?
                AND user_id = 
                    (SELECT id 
                    FROM users 
                    WHERE email = ?)";

        $processed = db_statement($sql, "is", array(&$groupID, &$username));
        if(!$processed){
            add_event($username, $groupID, "groups_leave");
        }
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

                    if($setNULL){
                        $groupID = null;
                    }

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

    function groups_add_user_to_group($group, $login, $admin = 0){

        require("config/sql_connect.php");

        $sql = "SELECT id FROM groups WHERE group_name = ?";
        $result1 = db_statement($sql, "s", array(&$group));

        if (mysqli_num_rows($result1) == 1) {
            $sql = "SELECT id FROM users WHERE email = ?";
            $result2 = db_statement($sql, "s", array(&$login));

            if (mysqli_num_rows($result2) == 1) {
                $sql = "INSERT INTO connections (group_id, user_id, admin_rights) VALUES (?,?,?)";

                $result1 = mysqli_fetch_assoc($result1);
                $result2 = mysqli_fetch_assoc($result2);
                $result1 = $result1["id"];
                $result2 = $result2["id"];

                $processed = db_statement($sql, "iii", array(&$result1, &$result2, &$admin));
                if(!$processed){
                    add_event($login, $result1, "groups_join");
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function groups_check_if_exists($group_name, &$error_text, &$error_flag){

        if (empty($group_name)){

            $error_text = "Pole nie może być puste";
            $error_flag = "has-error";
            return false;

        } else {

            if(!is_in_database($group_name, 'groups')){

                $error_text = "Grupa o podanej nazwie nie istnieje";
                $error_flag = "has-error";
                return false;

            }

            return true;

        }

    }

    function groups_check_password_correct($group_name, $password, &$error_text, &$error_flag){

        require("config/sql_connect.php");
        
        $password = md5($password);
        $sql = "SELECT id FROM groups WHERE group_name = ? and password = ?";
        $result = db_statement($sql, "ss", array(&$group_name, &$password));

        if (mysqli_num_rows($result) == 1) {
            return true;
        }
        else {
            $error_text = "Nieprawidłowe dane logowania do grupy.";
            $error_flag = "has-error";
            return false;
        }
    }

    function get_users_of_group($group_id){

        require("config/sql_connect.php");
        $sql = "SELECT email FROM users WHERE id IN (SELECT user_id FROM connections WHERE group_id = ?)";
        $result = db_statement($sql, "i", array(&$group_id));
        return $result;

    }

    function group_how_many_members($group_id){

        $sql = "SELECT COUNT(*) as members FROM connections WHERE group_id = ?";
        $result = db_statement($sql, "i", array(&$group_id));
        $row = mysqli_fetch_assoc($result);
        return $row['members'];

    }

    function group_print_members($group_id){

        $sql = "SELECT user_id, admin_rights FROM connections WHERE group_id = ?";
        $result = db_statement($sql, "i", array(&$group_id));

        if (mysqli_num_rows($result) > 0) {
            echo
            "<table class='table table-hover'>
                    <thead>
                      <tr>
                        <th>Użytkownik</th>
                        <th>Uprawnienia</th>
                      </tr>
                    </thead>
                    <tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                
                echo
                    "<tr>" .
                    "<td>" . users_get_name_from_id($row["user_id"]) . "</td>";
                if($row['admin_rights'] == 0) {
                    echo
                        "<td class='hidden'><div class=''>" .
                        "<input form='kick_users' type='checkbox' name='kickUsers[]' value='" . $row['user_id'] . "'>" .
                        "</div></td>" .
                        "<td class=''>
                            <button type='button' class='btn btn-danger btn-delete-kick'>Zaznacz użytkownika</button>
                         </td>" .
                        "</tr>";
                } else {
                    echo "<td>Administrator</td>".
                        "</tr>";
                }
            }
            echo
            "</table>
                    </tbody>
                    <form action='' method='POST' id='kick_users'>
                        <button type='submit' name='delete-submit' class='btn btn-col btn-block'>Usuń zaznaczonych użytkowników</button>
                    </form>";
        } else {
            echo
            "<div class='alert alert-danger'>
                Wystąpił błąd - nie można wyświetlić członków apteczki. Prosimy spróbować ponownie później.
            </div>";
        }
    }

?>