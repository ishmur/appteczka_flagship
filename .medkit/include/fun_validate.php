<?php

    function validate_trim_input($input){
        $input = trim($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    function validate_drug_name($var, &$error_text, &$error_flag){

        if (empty($var)){
            $error_text = "Pole nie może być puste.";
            $error_flag = "has-error";
            return false;
        }

        if (!preg_match("/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻ a-zA-Z0-9,.\/]*$/",$var)) {
            $error_text = "Nazwa leku może składać się wyłącznie z liter, cyfr, kropek, przecinków, spacji i znaku '/'.";
            $error_flag = "has-error";
            return false;
        }

        return true;

    }

    function validate_drug_unit($var, &$error_text, &$error_flag){

        if (empty($var)){
            $error_text = "Pole nie może być puste.";
            $error_flag = "has-error";
            return false;
        }

        return true;

    }

    function validate_numeric_amount($var, &$error_text, &$error_flag){

        if (empty($var)){
            $error_text = "Pole nie może być puste.";
            $error_flag = "has-error";
            return false;
        }

        if (!preg_match("/^[0-9,.]*$/",$var)) {
            $error_text = "Proszę podać liczbę.";
            $error_flag = "has-error";
            return false;
        }

        return true;

    }

    function validate_date($var, &$error_text, &$error_flag){

        if (empty($var)){
            $error_text = "Pole nie może być puste.";
            $error_flag = "has-error";
            return false;
        }

        $date_array = explode('-', $var);

        if (!(count($date_array) == 3)) {

            $error_text = "Data musi być w formacie: 'rok-miesiąc-dzień'.";
            $error_flag = "has-error";
            return false;
        }

        if (!checkdate($date_array[1], $date_array[2], $date_array[0])) { //checkdate tests for MM-DD-YYYY format
            $error_text = "Nieprawidłowa data.";
            $error_flag = "has-error";
            return false;
        }

        return true;

    }

    function validate_ean($var, &$error_text, &$error_flag){

        if (empty($var)){
            $error_text = "Pole nie może być puste.";
            $error_flag = "has-error";
            return false;
        }

        if (!preg_match("/^[0-9]*$/",$var)) {
            $error_text = "Kod EAN składa się wyłącznie z cyfr.";
            $error_flag = "has-error";
            return false;
        }

        return true;

    }

    function validate_active($var, &$error_text, &$error_flag){

        if (empty($var)){
            $error_text = "Pole nie może być puste.";
            $error_flag = "has-error";
            return false;
        }

        if (!preg_match("/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻa-zA-Z ]*$/",$var)) {
            $error_text = "Nieprawidłowa nazwa substancji czynnej leku.";
            $error_flag = "has-error";
            return false;
        }

        return true;

    }

    function validate_group_name($var, &$error_text, &$error_flag){

        if (empty($var)){

            $error_text = "Grupa musi mieć nazwę.";
            $error_flag = "has-error";
            return false;

        } else {

            if (!preg_match("/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻ a-zA-Z0-9]*$/",$var)) {
                $error_text = "Nazwa grupy może się składać wyłącznie z cyfr i liter.";
                $error_flag = "has-error";
                return false;
            }

            if(is_in_database($var, 'groups')){
                $error_text = "Grupa o podanej nazwie już istnieje.";
                $error_flag = "has-error";
                return false;
            }

            return true;
        }
    }

    function validate_password_fields($password, $password_check, &$error_text, &$error_flag) {

        if ($password != $password_check){

            $error_text = "Wartości obu pól haseł muszą być identyczne";
            $error_flag = "has-error";
            return false;

        } else {

            if (empty($password)) {

                $error_text = "Pole nie może być puste";
                $error_flag = "has-error";
                return false;

            }

            if (!preg_match("/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻa-zA-Z0-9@_]*$/",$password)) {
                $error_text = "Hasło może składać się wyłącznie z liter, cyfr, znaku podkreślenia lub @.";
                $error_flag = "has-error";
                return false;
            }

            return true;

        }

    }

    function validate_new_email($var, &$error_text, &$error_flag){

        if (filter_var($var, FILTER_VALIDATE_EMAIL)) {

            if (is_in_database($var, 'users')) {

                $error_text = "Podany adres email jest już przypisany do konta.";
                $error_flag = "has-error";
                return false;

            } else {

                return true;

            }

        } else {

            if (empty($var)) {

                $error_text = "Pole nie może być puste.";
                $error_flag = "has-error";
                return false;

            } else {

                $error_text = "Nieprawidłowy adres email.";
                $error_flag = "has-error";
                return false;

            }

        }

    }

    function validate_email($var, &$error_text, &$error_flag){

        if (filter_var($var, FILTER_VALIDATE_EMAIL)) {

            return true;

        } else {

            if (empty($var)){

                $error_text = "Pole nie może być puste.";
                $error_flag = "has-error";
                return false;

            } else {

                $error_text = "Nieprawidłowy adres email.";
                $error_flag = "has-error";
                return false;

            }

        }

    }

    function validate_password($password, &$error_text, &$error_flag) {

        if (empty($password)) {

            $error_text = "Pole nie może być puste";
            $error_flag = "has-error";
            return false;

        }

        if (!preg_match("/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻa-zA-Z0-9@_]*$/",$password)) {
            $error_text = "Hasło może składać się wyłącznie z liter, cyfr, znaku podkreślenia lub @.";
            $error_flag = "has-error";
            return false;
        }

        return true;



    }


?>