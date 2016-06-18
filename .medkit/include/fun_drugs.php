<?php

    function drugs_new_record($drugName, $drugPrice, $drugDate, $username, $groupID){

        require("config/sql_connect.php");

        $sql = "INSERT INTO DrugsDB (name, price, overdue, user_added, group_id, amount)
                    VALUES (?,?,?,?,?,10)";

        $processed = db_statement($sql, "sissi", array(&$drugName, &$drugPrice, &$drugDate, &$username, &$groupID));
        if(!$processed){
            add_event($username, $groupID, 'drugs_new', $drugName);
        }
    }

    function drugs_print_table($groupID){

        require("config/sql_connect.php");

        $sql = "SELECT id, name, price, amount, overdue 
                    FROM DrugsDB 
                    WHERE group_id = ?";

        $result = db_statement($sql, "i", array(&$groupID));

        if (mysqli_num_rows($result) > 0) {

            echo
                "<table class='table table-hover'>
                <thead>
                  <tr>
                    <th>Nazwa leku</th>
                    <th>Cena w złotówkach</th>
                    <th>Ilość</th>
                    <th>Data ważności</th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo
                    "<tr>".
                        "<td>" . $row["name"] . "</td>" .
                        "<td>" . $row["price"] . "</td>" .
                        "<td>" . $row["amount"] . "</td>" .
                        "<td>" . date("d-m-Y", strtotime($row["overdue"])). "</td>" .
                        "<td class=''>" .
                            "<button type='button' id='takeDrug-".$row["id"]."' class='btn btn-info btn-take'>Weź lek</button>".
                        "</td>".
                        "<td class='hidden'><div class=''>".
                            "<input form='edit_drugs' type='checkbox' name='drugs_edit[]' value='".$row['id']."'>".
                        "</div></td>".
                        "<td class=''>
                            <button type='button' class='btn btn-warning btn-edit'>Edytuj</button>
                        </td>".
                        "<td class='hidden'><div class=''>".
                            "<input form='delete_drugs' type='checkbox' name='drugs[]' value='".$row['id']."'>".
                        "</div></td>".
                        "<td class=''>" .
                            "<button type='button' class='btn btn-danger btn-delete'>Zaznacz</button>".
                        "</td>";
                    "</tr>";
            }

            echo
                    "</tbody>
                    </table>
                    <form action='' method='POST' id='delete_drugs'>
                        <button type='submit' name='delete-submit' class='btn btn-col btn-block'>Usuń zaznaczone lekarstwa</button>
                    </form>
                    <form action='' method='POST' id='edit_drugs'>
                    </form>";

        } else {

            echo
                "<p>Apteczka jest pusta.</p>" .
                "<a href='drugs_new.php'>Dodaj nowy lek</a>";

        }
    }

    function drug_name_from_id($drug_id){

        require("config/sql_connect.php");

        $sql = "SELECT name FROM DrugsDB
                    WHERE id = ?";

        $result = db_statement($sql, "i", array(&$drug_id));
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            return $row['name'];
        }
        else return false;
    }

    function drugs_take_drug($username, $groupID, $amount, $drugID, $amount_present){

        require("config/sql_connect.php");

        $drugName = drug_name_from_id($drugID);
        
        $sql = "UPDATE DrugsDB 
                SET amount = ?
                WHERE id = ?";

        $new_amount = $amount_present - $amount;

        $processed = db_statement($sql, "ii", array(&$new_amount, &$drugID));
        if(!$processed){
            add_event($username, $groupID, 'drugs_take', $drugName, $amount, "tabl.");
        }
    }

    function drugs_delete_record($username, $drugID, $groupID){

        require("config/sql_connect.php");

        $drugName = drug_name_from_id($drugID);

        $sql = "DELETE FROM DrugsDB 
                    WHERE id = ?
                    AND group_id = ?";

        $processed = db_statement($sql, "ii", array(&$drugID, &$groupID));
        if(!$processed){
            add_event($username, $groupID, 'drugs_delete', $drugName);
        }
    }

    function drugs_overdue_check_date($groupID){

        require("config/sql_connect.php");
        

        $sql = "SELECT id, name, overdue, amount
                    FROM DrugsDB 
                    WHERE group_id = ?
                    AND DATE(overdue) < CURRENT_DATE()";

        $result = db_statement($sql, "i", array(&$groupID));

        if (mysqli_num_rows($result) > 0) return true;
        else return false;

    }

    function drugs_overdue_print_table($groupID){

        require("config/sql_connect.php");
        

        $sql = "SELECT id, name, overdue, amount
                    FROM DrugsDB 
                    WHERE group_id = ?
                    AND DATE(overdue) < CURRENT_DATE()";

        $result = db_statement($sql, "i", array(&$groupID));

        if (mysqli_num_rows($result) > 0) {

            echo
                    "<table class='table table-hover'>
                    <thead>
                      <tr>
                        <th>Nazwa leku</th>
                        <th>Ilość</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {

                echo
                    "<tr>".
                        "<td>" . $row["name"] . "</td>" .
                        "<td>" . $row["amount"] . "</td>" .
                        "<td class='hidden'><div class=''>".
                            "<input form='delete_overdue' type='checkbox' name='overdue[]' value='".$row['id']."'>".
                        "</div></td>".
                        "<td class=''>
                            <button type='button' class='btn btn-danger btn-delete-overdue'>Zaznacz</button>
                         </td>";
                    "</tr>";

            }

            echo
                    "</table>
                    </tbody>
                    <form action='' method='POST' id='delete_overdue'>
                            <button type='submit' name='delete-submit' class='btn btn-col btn-block'>Usuń zaznaczone lekarstwa</button>
                    </form>";

        } else {

            echo
            "<p>Wszystkie leki znajdujące się w apteczce są przydatne do spożycia.</p>";

        }
    }

    function drugs_overdue_soon_print_table($groupID, $soonInt){

        require("config/sql_connect.php");

        $sql = "SELECT id, name, overdue, amount
                    FROM DrugsDB 
                    WHERE group_id = ?
                    AND DATE(overdue) < CURRENT_DATE() + INTERVAL ? day
                    AND DATE(overdue) > CURRENT_DATE()";

        $result = db_statement($sql, "ii", array(&$groupID, &$soonInt));

        if (mysqli_num_rows($result) > 0) {

            echo
                    "<table class='table table-hover'>
                    <thead>
                      <tr>
                        <th>Nazwa leku</th>
                        <th>Ilość</th>
                        <th>Data ważności</th>
                        <th>Pozostało dni</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {

                $dateNow = date_create(date('d-m-Y'));
                $dateOverdue = date_create(date("d-m-Y", strtotime($row["overdue"])));
                $dateDiffInterval = date_diff($dateNow, $dateOverdue);

                echo
                    "<tr>" .
                        "<td>" . $row["name"] . "</td>" .
                        "<td>" . $row["amount"] . "</td>" .
                        "<td>" . date_format($dateOverdue, "d-m-Y") . "</td>" .
                        "<td>" . $dateDiffInterval->format("%a") . // show result in days
                        "</td>" .
                        "<td class='hidden'><div class=''>".
                            "<input form='delete_soon' type='checkbox' name='overdueSoon[]' value='".$row['id']."'>".
                        "</div></td>".
                        "<td class=''>
                            <button type='button' class='btn btn-danger btn-delete-soon'>Zaznacz</button>
                         </td>".
                    "</tr>";

            }

            echo
                    "</table>
                    </tbody>
                    <form action='' method='POST' id='delete_soon'>
                        <button type='submit' name='delete-submit' class='btn btn-col btn-block'>Usuń zaznaczone lekarstwa</button>
                    </form>";

        } else {

            echo
            "<p>Okres ważności wszystkich pozostałych leków w apteczce jest dłuższy niż $soonInt dni.</p>";

        }
    }

?>