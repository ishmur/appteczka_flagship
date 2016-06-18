<?php
    function add_event($user, $group_id, $action, $drug_name = "", $amount = 0, $unit = ""){

        switch($action) {
            case "drugs_new":
                $action = 2;
                $sql = "INSERT INTO user_actions_log (email, group_id, action, drug_name) VALUES (?,?,?,?)";
                $processed = db_statement($sql, "siis", array(&$user, &$group_id, &$action, &$drug_name));
                break;
            case "drugs_delete":
                $action = 3;
                $sql = "INSERT INTO user_actions_log (email, group_id, action, drug_name) VALUES (?,?,?,?)";
                $processed = db_statement($sql, "siis", array(&$user, &$group_id, &$action, &$drug_name));
                break;
            case "drugs_take":
                $action = 4;
                $sql = "INSERT INTO user_actions_log (email, group_id, action, drug_name, amount, unit) VALUES (?,?,?,?,?,?)";
                if ($amount != 0) {
                    $processed = db_statement($sql, "siisis", array(&$user, &$group_id, &$action, &$drug_name, &$amount, &$unit));
                }
                break;
            case "groups_leave":
                $action = 0;
                $sql = "INSERT INTO user_actions_log (email, group_id, action) VALUES (?,?,?)";
                $processed = db_statement($sql, "sii", array(&$user, &$group_id, &$action));
                break;
            case "groups_join":
                $action = 1;
                $sql = "INSERT INTO user_actions_log (email, group_id, action) VALUES (?,?,?)";
                $processed = db_statement($sql, "sii", array(&$user, &$group_id, &$action));
                break;
        }
    }

    function parse_feed($group_id, $username = ""){

        if ($username == "") {
            $sql = "SELECT * FROM user_actions_log WHERE group_id = ? ORDER BY created DESC";
            $result = db_statement($sql, "i", array(&$group_id));
        }
        else {
            $sql = "SELECT * FROM user_actions_log WHERE group_id = ? AND email = ? ORDER BY created DESC";
            $result = db_statement($sql, "is", array(&$group_id, &$username));
        }

        if (mysqli_num_rows($result) > 0) {

            echo
            "<table class='table table-hover'>
                <thead>
                  <tr>
                    <th>Wydarzenie</th>
                    <th>Kiedy</th>
                  </tr>
                </thead>
                <tbody>";

            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $msg = form_message($row);
                echo
                    "<tr>" .
                    "<td>" . $msg . "</td>" .
                    "<td>" . time_description($row["created"]) . "</td>"."</tr>";
            }
            echo "</tbody></table>";
        }
    }

    function form_message($db_row){
        $output_string = "Użytkownik <strong>".$db_row['email']. "</strong> ";
        switch($db_row['action']){
            case 0:
                $output_string = $output_string . "opuścił apteczkę.";
                break;
            case 1:
                $output_string = $output_string . "dołączył do apteczki.";
                break;
            case 2:
                $output_string = $output_string . "dodał lek ". $db_row['drug_name'] . " do apteczki.";
                break;
            case 3:
                $output_string = $output_string . "usunął lek ". $db_row['drug_name'] . " z apteczki.";
                break;
            case 4:
                $output_string = $output_string . "przyjął lek ". $db_row['drug_name'] . " w ilości " . $db_row['amount'] . " " . $db_row['unit'];
                break;
        }
        return $output_string;
    }

    function time_description($time_ago){

        $time_ago = strtotime($time_ago);
        $mysqldate = date( 'd.m.Y H:i', $time_ago );
        $cur_time   = time();
        $time_elapsed   = $cur_time - $time_ago;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60 );
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400 );

        if($days > 7){
            return $mysqldate;
        }
        else if($days < 3) {
            if ($seconds <= 60) {
                return "teraz";
            }

            if ($minutes <= 60) {
                if ($minutes == 1) {
                    return "minutę temu";
                } elseif ($minutes < 4 || ($minutes < 25 && $minutes > 21) || ($minutes < 35 && $minutes > 31)
                    || ($minutes < 45 && $minutes > 41) || ($minutes < 55 && $minutes > 51)
                ) {
                    return "$minutes minuty temu";
                } else {
                    return "$minutes minut temu";
                }
            }
            if ($hours <= 72) {
                if ($hours == 1) {
                    return "godzinę temu";
                } elseif ($hours < 4 || ($hours < 25 && $hours > 21) || ($hours < 35 && $hours > 31)
                    || ($minutes < 45 && $minutes > 41) || ($minutes < 55 && $minutes > 51) ||
                    ($minutes < 65 && $minutes > 61) || ($minutes == 72)
                ) {
                    return "$hours godziny temu";
                } else {
                    return "$hours godzin temu";
                }
            }
        }
        else{
            return "$days dni temu";
        }
        return "";
    }
?>