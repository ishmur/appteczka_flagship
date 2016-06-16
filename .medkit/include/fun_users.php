<?php

    function users_get_last_group_id($username){

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

?>
