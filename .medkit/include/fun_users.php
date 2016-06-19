<?php

    function users_get_last_group_id($username){

        require("config/sql_connect.php");

        $sql = "SELECT show_group_id
                FROM users
                WHERE email = ?";

        $result = db_statement($sql, "s", array(&$username));


        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $groupID = $row["show_group_id"];
        }

        

        return $groupID;

    }

    function change_password($username, $password){

        require("config/sql_connect.php");

        $sql = "UPDATE users
                    SET password = ?
                    WHERE email = ?";

        $processed = db_statement($sql, "ss", array(&$password, &$username));
        return $processed;
    }

?>
