<?php

    function trim_input($input){
        $input = trim($input);
        $input = stripcslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    function db_statement(){

        require(__DIR__."/../config/sql_connect.php");

        $args = func_get_args();

        if(count($args) < 3){

            trigger_error("Not enough input arguments");
            return false;

        } else {

            $sql = $args[0];
            $types = $args[1];
            $params = $args[2];

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
            } else {
                $result = mysqli_stmt_get_result($stmt);
            }

            mysqli_stmt_close($stmt);
            mysqli_close($dbConnection);

            return $result;

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

    function correct_password($username, $password){
        require("config/sql_connect.php");

        $sql = "SELECT id FROM users WHERE email = ? and password = ?";
        $result = db_statement($sql, "ss", array(&$username, &$password));

        if (mysqli_num_rows($result) == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    function is_in_database($entity, $db){

        require("config/sql_connect.php");

        if ($db == 'users') {
            $sql = "SELECT id FROM users WHERE email = ?";
        }
        else if ($db == 'groups'){
            $sql = "SELECT id FROM groups WHERE group_name = ?";
        }

        $result = db_statement($sql, "s", array(&$entity));

        if (mysqli_num_rows($result) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    function register($username, $password, $option){

        if ($option == 'user') {
            $sql = "INSERT INTO users (email, password)
                    VALUES (?,?)";
        }

        else if($option == 'group'){
            $sql = "INSERT INTO groups (group_name, password)
                    VALUES (?,?)";
        }

        $processed = db_statement($sql, "ss", array(&$username, &$password));
        return ($processed == false);

    }

    

?>