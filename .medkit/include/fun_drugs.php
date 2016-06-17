<?php

    function drugs_new_record($drugName, $drugPrice, $drugDate, $username, $groupID){

        require("config/sql_connect.php");

        $sql = "INSERT INTO DrugsDB (name, price, overdue, user_added, group_id)
                    VALUES (?,?,?,?,?)";

        $processed = db_statement($sql, "sissi", array(&$drugName, &$drugPrice, &$drugDate, &$username, &$groupID));
    }

    function drugs_print_table($groupID){

        require("config/sql_connect.php");

        $sql = "SELECT id, name, price, amount, overdue, user_added 
                    FROM DrugsDB 
                    WHERE group_id = ?";

        $result = db_statement($sql, "i", array(&$groupID));

        if (mysqli_num_rows($result) > 0) {

            echo
            "<form action='' method='POST'>
                <table class='table table-hover'>
                <thead>
                  <tr>
                    <th></th>
                    <th>Nazwa leku</th>
                    <th>Cena w złotówkach</th>
                    <th>Ilość</th>
                    <th>Data ważności</th>
                    <th>Kto dodał</th>
                  </tr>
                </thead>
                <tbody>";

            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                echo
                    "<tr>".
                    "<td class=''>" . "<input type='checkbox' name='drugs[]' value='".$row["id"]."'></td>" .
                    "<td>" . $row["name"] . "</td>" .
                    "<td>" . $row["price"] . "</td>" .
                    "<td>" . $row["amount"] . "</td>" .
                    "<td>" . date("d-m-Y", strtotime($row["overdue"])). "</td>" .
                    "<td>" . $row["user_added"] . "</td>" .
                    "</tr>";
            }

            echo
            "</tbody>
                    </table>
                    <button type='submit' class='btn btn-col btn-block'>Usuń zaznaczone leki</button>
                    </form>";

        } else {

            echo
                "<p>Apteczka jest pusta.</p>" .
                "<a href='drugs_new.php'>Dodaj nowy lek</a>";

        }
    }

    function drugs_delete_record($drugID, $groupID){

        require("config/sql_connect.php");
        

        $sql = "DELETE FROM DrugsDB 
                    WHERE id = ?
                    AND group_id = ?";

        $processed = db_statement($sql, "ii", array(&$drugID, &$groupID));
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