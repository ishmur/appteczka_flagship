<?php
    function add_event($user, $group_id, $action, $drug_name = "", $amount = 0, $unit = ""){
        if($action === 'drugs_new'){
            $action = 2;
            $sql = "INSERT INTO user_actions_log (email, group_id, action, drug_name) VALUES (?,?,?,?)";
            $processed = db_statement($sql, "siis", array(&$user, &$group_id, &$action, &$drug_name));
        }
        else if($action === 'drugs_delete'){
            $action = 3;
            $sql = "INSERT INTO user_actions_log (email, group_id, action, drug_name) VALUES (?,?,?,?)";
            $processed = db_statement($sql, "siis", array(&$user, &$group_id, &$action, &$drug_name));
        }
    }
?>