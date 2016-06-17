<?php

    function specif_new_record($specif_name, $specif_EAN, $specif_per_package, $specif_price, $specif_active){

        require("config/sql_connect.php");

        $sql = "INSERT INTO drug_spec (drug_name, ean, per_package, price_per_package, active, user_defined, price_per_unit)
                    VALUES (?,?,?,?,?,1,?)";

        $stmt = mysqli_prepare($dbConnection,$sql);
        if ($stmt === false) {
            trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
        }

        $specif_price_per_unit = round($specif_price/$specif_per_package, 2);

        $bind = mysqli_stmt_bind_param($stmt, "ssddsd", $specif_name, $specif_EAN, $specif_per_package, $specif_price, $specif_active, $specif_price_per_unit);
        if ($bind === false) {
            trigger_error('Bind param failed!', E_USER_ERROR);
        }

        $exec = mysqli_stmt_execute($stmt);
        if ($exec === false) {
            trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbConnection);

    }

    function specif_pagination($page = 1){
        //JEST TO KLASYCZNY PRZYKLAD KODU SPAGHETTI
        //DO POPRAWY
        //ALE DZIALA XD
        require("config/sql_connect.php");
        $sql = "SELECT drug_name, ean, per_package, unit, active, price_per_package FROM drug_spec";
        $result = mysqli_query($dbConnection, $sql);
        $rows = mysqli_num_rows($result);
        $rows_per_page = 30;
        $pages = intval(ceil($rows / $rows_per_page));
        $start = $page - 2;
        $prev = $page - 1;
        $next = $page + 1;
        echo "<nav><ul class='pagination'>";
        if($page != 1) echo "<li><a href='specif_overview.php?p=$prev'>&laquo;</span></a></li><li><a href = 'specif_overview.php?p=1'>1   </a></li>";
        else echo "<li class='disabled'><a href='specif_overview.php?p=$prev'>&laquo;</span></a></li><li class='active'><a>1 </a></li>";
        $end = $start + 4;
        if ($start <= 2) $start = 2;
        else echo "<li><a>...</a></li>";
        if ($end >= $pages) $end = $pages - 1;
        while ($start <= $end) {
            if ($start == $page) echo "<li class='active'><a>$start </a></li>";
            else echo "<li><a href = 'specif_overview.php?p=$start'>$start   </a></li>";
            $start = $start + 1;
        }
        if ($end != $pages - 1) echo "<li><a>...</a></li>";
        if ($page != $pages) echo "<li><a href = 'specif_overview.php?p=$pages'>$pages  </a></li><li><a href='specif_overview.php?p=$next'>&raquo;</span></a></li>";
        else echo "<li class='active'><a>$pages</a></li><li class='disabled'><a>&raquo;</span></a></li>";
        $start_limit = ($rows_per_page * ($page - 1));


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
                        "<input form='edit_specif' type='hidden' name='specif_edit[]' value='".$row['id_spec']."'>".
                        "<td class=''>" . "<input form='edit_specif' name='edit-submit' type='submit' value='Edytuj'></td>".
                        "<td class=''>" . "<input form='delete_specif' type='checkbox' name='specif[]' value='".$row["id_spec"]."'></td>";
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

        $stmt = mysqli_prepare($dbConnection,$sql);
        if ($stmt === false) {
            trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
        }

        $bind = mysqli_stmt_bind_param($stmt, "i", $specifID);
        if ($bind === false) {
            trigger_error('Bind param failed!', E_USER_ERROR);
        }

        $exec = mysqli_stmt_execute($stmt);
        if ($exec === false) {
            trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($stmt)), E_USER_ERROR);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbConnection);

    }

    function specif_print_ean($ean){
        require("config/sql_connect.php");

        $userDefinedCounter = 0;

        $sql = "SELECT id_spec, drug_name, ean, per_package, unit, active, price_per_package, user_defined
                    FROM drug_spec 
                    WHERE ean = ?";

        $stmt = mysqli_prepare($dbConnection, $sql);
        if ($stmt === false) {
            trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
        }

        $bind = mysqli_stmt_bind_param($stmt, "s", $ean);
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
                        "<td class=''>" . "<input form='edit_specif' name='edit-submit' type='submit' value='Edytuj'></td>".
                        "<input form='delete_specif' type='hidden' name='specif[]' value='".$row['id_spec']."'>".
                        "<td class=''>" . "<input form='delete_specif' name='delete-submit' type='submit' value='Usuń'></td>";
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

        mysqli_stmt_close($stmt);
        mysqli_close($dbConnection);

    }

    function specif_get_info($specif_id){

        require("config/sql_connect.php");

        $specif = null;

        $sql = "SELECT  drug_name, ean, per_package, price_per_package, active
                FROM drug_spec
                WHERE id_spec = ?";

        $stmt = mysqli_prepare($dbConnection,$sql);
        if ($stmt === false) {
            trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
        }

        $bind = mysqli_stmt_bind_param($stmt, "i", $specif_id);
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
                $specif['drug_name'] = $row["drug_name"];
                $specif['ean'] = $row["ean"];
                $specif['per_package'] = $row["per_package"];
                $specif['price_per_package'] = $row["price_per_package"];
                $specif['active'] = $row["active"];
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbConnection);

        return $specif;

    }

?>
