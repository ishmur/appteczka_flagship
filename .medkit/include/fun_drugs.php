<?php

    function drugs_new_record($drugName, $drugPrice, $drugDate, $username, $groupID){

        require("config/sql_connect.php");

        $sql = "INSERT INTO DrugsDB (name, price, overdue, user_added, group_id)
                    VALUES (?,?,?,?,?)";

        $stmt = mysqli_prepare($dbConnection,$sql);
        if ($stmt === false) {
            trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
        }

        $bind = mysqli_stmt_bind_param($stmt, "sissi", $drugName, $drugPrice, $drugDate, $username, $groupID);
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

    function drugs_print_table($groupID){

        require("config/sql_connect.php");

        $sql = "SELECT id, name, price, amount, overdue, user_added 
                    FROM DrugsDB 
                    WHERE group_id = $groupID";

        $result = mysqli_query($dbConnection, $sql);

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

        mysqli_close($dbConnection);
    }

    function drugs_delete_record($drugID, $groupID){

        require("config/sql_connect.php");

        $sql = "DELETE FROM DrugsDB 
                    WHERE id = ?
                    AND group_id = ?";

        $stmt = mysqli_prepare($dbConnection,$sql);
        if ($stmt === false) {
            trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($dbConnection)), E_USER_ERROR);
        }

        $bind = mysqli_stmt_bind_param($stmt, "ii", $drugID, $groupID);
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

    function drugs_overdue_check_date($groupID){

        require("config/sql_connect.php");

        $sql = "SELECT id, name, overdue, amount
                    FROM DrugsDB 
                    WHERE group_id = $groupID
                    AND DATE(overdue) < CURRENT_DATE()";

        $result = mysqli_query($dbConnection, $sql);

        if (mysqli_num_rows($result) > 0){

            return true;

        }

        mysqli_close($dbConnection);

        return false;

    }

    function drugs_overdue_print_table($groupID){

        require("config/sql_connect.php");

        $sql = "SELECT id, name, overdue, amount
                    FROM DrugsDB 
                    WHERE group_id = $groupID
                    AND DATE(overdue) < CURRENT_DATE()";

        $result = mysqli_query($dbConnection, $sql);

        if (mysqli_num_rows($result) > 0) {

            echo
            "<form action='' method='POST'>
                    <table class='table table-hover'>
                    <thead>
                      <tr>
                        <th></th>
                        <th>Nazwa leku</th>
                        <th>Ilość</th>
                      </tr>
                    </thead>
                    <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {

                echo
                    "<tr>".
                    "<td class=''>" . "<input type='checkbox' name='overdue[]' value='".$row["id"]."'></td>" .
                    "<td>" . $row["name"] . "</td>" .
                    "<td>" . $row["amount"] . "</td>" .
                    "</tr>";

            }

            echo
            "</table>
                    </tbody>
                    <button class='btn btn-col btn-block'>Usuń zaznaczone lekarstwa</button>
                    </form>";

        } else {

            echo
            "<p>Wszystkie leki znajdujące się w apteczce są przydatne do spożycia.</p>";

        }

        mysqli_close($dbConnection);
        
    }

    function drugs_overdue_soon_print_table($groupID, $soonInt){

        require("config/sql_connect.php");

        $sql = "SELECT id, name, overdue, amount
                    FROM DrugsDB 
                    WHERE group_id = $groupID
                    AND DATE(overdue) < CURRENT_DATE() + INTERVAL $soonInt day
                    AND DATE(overdue) > CURRENT_DATE()";

        $result = mysqli_query($dbConnection, $sql);

        if (mysqli_num_rows($result) > 0) {

            echo
            "<form action='' method='POST'>
                    <table class='table table-hover'>
                    <thead>
                      <tr>
                        <th></th>
                        <th>Nazwa leku</th>
                        <th>Ilość</th>
                        <th>Data ważności</th>
                        <th>Pozostało dni</th>
                      </tr>
                    </thead>
                    <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {

                $dateNow = date_create(date('d-m-Y'));
                $dateOverdue = date_create(date("d-m-Y", strtotime($row["overdue"])));
                $dateDiffInterval = date_diff($dateNow, $dateOverdue);

                echo
                    "<tr>" .
                    "<td class=''>" . "<input type='checkbox' name='overdueSoon[]' value='" . $row["id"] . "'></td>" .
                    "<td>" . $row["name"] . "</td>" .
                    "<td>" . $row["amount"] . "</td>" .
                    "<td>" . date_format($dateOverdue, "d-m-Y") . "</td>" .
                    "<td>" . $dateDiffInterval->format("%a"); // show result in days
                "</td>" .
                "</tr>";


            }

            echo
            "</table>
                    </tbody>
                    <button class='btn btn-col btn-block'>Usuń zaznaczone lekarstwa</button>
                    </form>";

        } else {

            echo
            "<p>Okres ważności wszystkich pozostałych leków w apteczce jest dłuższy niż $soonInt dni.</p>";

        }

        mysqli_close($dbConnection);

    }

?>