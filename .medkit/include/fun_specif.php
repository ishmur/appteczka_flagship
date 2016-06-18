<?php

    function specif_new_record($specif_name, $specif_EAN, $specif_per_package, $specif_unit, $specif_price, $specif_active){

        require("config/sql_connect.php");
        

        $sql = "INSERT INTO drug_spec (drug_name, ean, per_package, unit, price_per_package, active, user_defined, price_per_unit)
                    VALUES (?,?,?,?,?,?,1,?)";

        $specif_price_per_unit = round($specif_price/$specif_per_package, 2);
        $processed = db_statement($sql, "ssdsdsd", array(&$specif_name, &$specif_EAN, &$specif_per_package, &$specif_unit, &$specif_price, &$specif_active, &$specif_price_per_unit));
        if($processed == "duplicate"){
            return $processed;
        }
    }

    function specif_update_record($specif_name, $specif_EAN, $specif_per_package, $specif_unit, $specif_price, $specif_active, $specif_id){

        require("config/sql_connect.php");
        

        $sql = "UPDATE drug_spec 
                SET drug_name = ?, ean = ?, per_package = ?, unit = ?, price_per_package = ?, active = ?, price_per_unit = ?
                WHERE id_spec = ?";
        
        $specif_price_per_unit = round($specif_price/$specif_per_package, 2);
        $processed = db_statement($sql, "ssdsdsdi", array(&$specif_name, &$specif_EAN, &$specif_per_package, &$specif_unit, &$specif_price, &$specif_active, &$specif_price_per_unit, &$specif_id));
        if($processed == "duplicate"){
            return $processed;
        }
    }

    function specif_pagination($page = 1){
        require("config/sql_connect.php");

        // sprawdzenie, ile rekordów trzeba "spaginować"
        $sql = "SELECT drug_name FROM drug_spec";
        $result = mysqli_query($dbConnection, $sql);
        $rows = mysqli_num_rows($result);
        $rows_per_page = 10; //ile rekordów na stronę
        $pages = intval(ceil($rows / $rows_per_page)); //ile stron
        //start, end - sąsiedztwo obecnie wybranej strony
        $start = $page - 2; //page = obecnie wybrana strona
        $end = $start + 4;
        //prev, next - poprzednia, kolejna strona względem obecnej
        $prev = $page - 1;
        $next = $page + 1;

        echo "<nav><ul class='pagination'>";
        // disable button <<, jeśli ZNAJDUJESZ SIĘ na pierwszej stronie
        if($page != 1) echo "<li><a href='specif_overview.php?p=$prev'>&laquo;</span></a></li><li><a href = 'specif_overview.php?p=1'>1   </a></li>";
        else echo "<li class='disabled'><a href='specif_overview.php?p=$prev'>&laquo;</span></a></li><li class='active'><a>1 </a></li>";

        // jeśli "sąsiedztwo" danej strony wychodzi poza zakres 2:pages-1, odpowiednio ogranicz
        if ($start <= 2) $start = 2;
        else echo "<li><a>...</a></li>";
        if ($end >= $pages) $end = $pages - 1;
        // wyświetl "sąsiedztwo" obecnej strony w pętli
        while ($start <= $end) {
            if ($start == $page) echo "<li class='active'><a>$start</a></li>"; //wybraną stronę zaznacz jako "aktywną"
            else echo "<li><a href = 'specif_overview.php?p=$start'>$start</a></li>";
            $start = $start + 1;
        }
        if ($end != $pages - 1) echo "<li><a>...</a></li>";

        //disable button >> jeśli ZNAJDUJESZ SIĘ na ostatniej stronie
        if ($page != $pages) echo "<li><a href = 'specif_overview.php?p=$pages'>$pages</a></li><li><a href='specif_overview.php?p=$next'>&raquo;</span></a></li>";
        else {
            if ($pages != 1) echo "<li class='active'><a>$pages</a></li>";
            echo "<li class='disabled'><a>&raquo;</span></a></li>";
        }

        //oblicz, od którego rekordu pokazywać
        $start_limit = ($rows_per_page * ($page - 1));

        //sformułowanie zapytania do SQL
        return "SELECT id_spec, drug_name, ean, per_package, unit, active, price_per_package, user_defined 
                FROM drug_spec 
                ORDER BY drug_name LIMIT " . $start_limit . "," . $rows_per_page;
    }

    function specif_print_table($sql){

        require("config/sql_connect.php");
        

        $userDefinedCounter = 0;

        $result = mysqli_query($dbConnection, $sql);

        if (mysqli_num_rows($result) > 0) {

            echo
                "<table class='table table-hover'>
                <thead>
                  <tr>
                    <th>Nazwa leku</th>
                    <th>Kod EAN</th>
                    <th>Ilość leku</th>
                    <th>Substancja czynna</th>
                    <th>Cena</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>";

            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                echo
                    "<tr>".

                    "<td>" . $row["drug_name"] . "</td>" .
                    "<td>" . $row["ean"] . "</td>" .
                    "<td>" . $row["per_package"] . " " . $row["unit"] . "</td>" .
                    "<td>" . $row["active"] . "</td>" .
                    "<td>" . $row["price_per_package"] . "</td>";

                if ($row["user_defined"]) {
                    $userDefinedCounter++;
                    echo
                        "<td class='hidden'><div class=''>".
                            "<input form='edit_specif' type='checkbox' name='specif_edit[]' value='".$row['id_spec']."'>".
                        "</div></td>".
                        "<td class=''>
                            <button type='button' class='btn btn-warning btn-edit'>Edytuj</button>
                        </td>".
                        "<td class='hidden'><div class=''>".
                            "<input form='delete_specif' type='checkbox' name='specif[]' value='".$row['id_spec']."'>".
                        "</div></td>".
                        "<td class=''>" .
                            "<button type='button' class='btn btn-danger btn-delete'>Zaznacz</button>".
                        "</td>";

                }
                    echo "</tr>";
            }

            echo
                "</tbody>
                </table>";

            if ($userDefinedCounter){
                echo
                        "<form action='' method='POST' id='delete_specif'>
                            <button type='submit' name='delete-submit' class='btn btn-col btn-block'>Usuń zaznaczone specyfikacje</button>
                        </form>
                        <form action='specif_edit.php' method='POST' id='edit_specif'>
                        </form>";
            }

        } else {

            echo
                "<p>Niezdefiniowano żadnej specyfikacji leku.</p>" .
                "<a href='specif_new.php'>Dodaj nową specyfikację</a>";

        }

        mysqli_close($dbConnection);
    }

    function specif_delete_record($specifID){

        require("config/sql_connect.php");
        

        $sql = "DELETE FROM drug_spec 
            WHERE id_spec = ?";
        
        $processed = db_statement($sql, "i", array(&$specifID));
    }

    function specif_get_info_from_ean($ean){

        require("config/sql_connect.php");

        $sql = "SELECT id_spec, drug_name, ean, per_package, unit, active, price_per_package, user_defined
                FROM drug_spec 
                WHERE ean = ?";

        $result = db_statement($sql, "s", array(&$ean));

        return $result;

    }

    function specif_get_info_from_id($specif_id){
    
        require("config/sql_connect.php");
    
    
        $specif = null;
    
        $sql = "SELECT  drug_name, ean, per_package, unit, price_per_package, active
                    FROM drug_spec
                    WHERE id_spec = ?";
    
        $result = db_statement($sql, "i", array(&$specif_id));

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $specif['drug_name'] = $row["drug_name"];
            $specif['ean'] = $row["ean"];
            $specif['per_package'] = $row["per_package"];
            $specif['unit'] = $row["unit"];
            $specif['price_per_package'] = $row["price_per_package"];
            $specif['active'] = $row["active"];
            $specif['id_spec'] = $specif_id;
        }
    
        return $specif;
    
    }

    function specif_print_ean($ean){

        $userDefinedCounter = 0;

        $result = specif_get_info_from_ean($ean);
        
            if (mysqli_num_rows($result) == 1) {

                $row = mysqli_fetch_assoc($result);
                echo
                    "<table class='table table-hover'>
                     <thead>
                       <tr>
                         <th>Nazwa leku</th>
                         <th>Kod EAN</th>
                         <th>Ilość leku</th>
                         <th>Substancja czynna</th>
                         <th>Cena</th>
                         <th></th>
                         <th></th>
                       </tr>
                     </thead>
                     <tbody>";
                echo
                    "<tr>".
                        "<td>" . $row["drug_name"] . "</td>" .
                        "<td>" . $row["ean"] . "</td>" .
                        "<td>" . $row["per_package"] . " " . $row["unit"] . "</td>" .
                        "<td>" . $row["active"] . "</td>" .
                        "<td>" . $row["price_per_package"] . "</td>";

                if ($row["user_defined"]) {
                    $userDefinedCounter++;
                    echo
                        "<input form='edit_specif' type='hidden' name='specif_edit[]' value='".$row['id_spec']."'>".
                        "<td class=''>" . "<input class='btn btn-warning' form='edit_specif' name='edit-submit' type='submit' value='Edytuj'></td>".
                        "<input form='delete_specif' type='hidden' name='specif[]' value='".$row['id_spec']."'>".
                        "<td class=''>" . "<input class='btn btn-danger' form='delete_specif' name='delete-submit' type='submit' value='Usuń'></td>";
                }

                echo
                        "</tr>
                        </tbody>
                        </table>";

                if ($userDefinedCounter){
                    echo
                        "<form action='' method='POST' id='delete_specif'>
                        </form>
                        <form action='specif_edit.php' method='POST' id='edit_specif'>
                        </form>";
                }

            } else {

                echo
                    "<p>W apteczce nie ma leku o wybranej specyfikacji.</p>" .
                    "<a href='specif_new.php'>Dodaj nową specyfikację</a>";

            }

    }

    function specif_check_unit($unit_array, $unit_string){

        if ($unit_array == $unit_string)
            echo "selected";
    }

?>
