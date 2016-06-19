<?php

    define("E_DUPLICATE_KEY", 1062);

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

                // check for EAN duplicate entry
                if (mysqli_errno($dbConnection) == E_DUPLICATE_KEY){
                    $error = "duplicate";
                    mysqli_stmt_close($stmt);
                    mysqli_close($dbConnection);
                    return $error;
                } else {
                    trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
                    return false;
                }

            } else {
                $result = mysqli_stmt_get_result($stmt);
            }

            mysqli_stmt_close($stmt);
            mysqli_close($dbConnection);

            return $result;

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

    function paginate($sql, $href, $rows_per_page = 10, $page = 1, $array_of_arg = array()){
        require("config/sql_connect.php");

        if(count($array_of_arg) == 0) $result = mysqli_query($dbConnection, $sql);
        else {
            $types = $array_of_arg[0];
            $ref_array = $array_of_arg[1];
            $result = db_statement($sql, $types, $ref_array);
        }
        // sprawdzenie, ile rekordów trzeba "spaginować"
        $rows = mysqli_num_rows($result);
        $pages = intval(ceil($rows / $rows_per_page)); //ile stron
        //start, end - sąsiedztwo obecnie wybranej strony
        if(($page < 1 || $page > $pages) && ($pages > 1)){
            echo "Wybrano stronę spoza zakresu. Wyświetlana jest pierwsza strona";
            $page = 1;
        }
        $start = $page - 2; //page = obecnie wybrana strona
        $end = $start + 4;
        //prev, next - poprzednia, kolejna strona względem obecnej
        $prev = $page - 1;
        $next = $page + 1;
        
        if ($pages > 1) {
            echo "<row><div class='col-md-8'><ul class='pagination pagination-sm'>";
            // disable button <<, jeśli ZNAJDUJESZ SIĘ na pierwszej stronie
            if ($page != 1) echo "<li><a href='$href?p=$prev'>&laquo;</span></a></li><li><a href = '$href?p=1'>1   </a></li>";
            else echo "<li class='disabled'><a href='$href?p=$prev'>&laquo;</span></a></li><li class='active'><a>1 </a></li>";

            // jeśli "sąsiedztwo" danej strony wychodzi poza zakres 2:pages-1, odpowiednio ogranicz
            if ($start <= 2) $start = 2;
            else echo "<li><a>...</a></li>";
            if ($end >= $pages) $end = $pages - 1;
            // wyświetl "sąsiedztwo" obecnej strony w pętli
            while ($start <= $end) {
                if ($start == $page) echo "<li class='active'><a>$start</a></li>"; //wybraną stronę zaznacz jako "aktywną"
                else echo "<li><a href = '$href?p=$start'>$start</a></li>";
                $start = $start + 1;
            }
            if ($end != $pages - 1) echo "<li><a>...</a></li>";

            //disable button >> jeśli ZNAJDUJESZ SIĘ na ostatniej stronie
            if ($page != $pages) echo "<li><a href = '$href?p=$pages'>$pages</a></li><li><a href='$href?p=$next'>&raquo;</span></a></li>";
            else {
                if ($pages != 1) echo "<li class='active'><a>$pages</a></li>";
                echo "<li class='disabled'><a>&raquo;</span></a></li>";
            }
            echo "</ul></div>";


            echo "<form><div class='col-md-4'>
                        <div class='input-group' style='padding-top: 20px;><form class='' method='GET'>
                        <input type='number' min='1' max='$pages' step = '1' name='p' class='form-control input-sm' style='width:90px;'  placeholder='Strona'>
                        <span class='input-group-btn' style='width:0;'>
                        <button type='submit' class='btn btn-sm btn-primary'>>>></button></span>
                        </div></div></row></form>";
        }
        //oblicz, od którego rekordu pokazywać
        $start_limit = ($rows_per_page * ($page - 1));

        //sformułowanie zapytania do SQL
        return $sql . " LIMIT " . $start_limit . "," . $rows_per_page;
    }
?>