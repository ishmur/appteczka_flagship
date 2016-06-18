<?php

    function validate_trim_input($input){
        $input = trim($input);
        $input = stripcslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    function validate_drug_name($var, &$error_text, &$error_flag){

        if (empty($var)){
            $error_text = "Pole nie może być puste.";
            $error_flag = "has-error";
            return false;
        }

        if (!preg_match("/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻ a-zA-Z0-9,.]*$/",$var)) {
            $error_text = "Nazwa leku może składać się wyłącznie z liter, cyfr, kropek, przecinków i spacji.";
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

?>