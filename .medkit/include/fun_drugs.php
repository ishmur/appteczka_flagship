<?php
function drugs_new_record($drug_name, $drug_unit, $drug_amount, $drug_price, $drug_price_per_unit, $drug_date, $username, $groupID){
    require("config/sql_connect.php");
    $sql = "INSERT INTO DrugsDB (name, unit, amount, price, price_per_unit, overdue, user_added, group_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $processed = db_statement($sql, "ssiddssi", array(&$drug_name, &$drug_unit, &$drug_amount, &$drug_price, &$drug_price_per_unit, &$drug_date, &$username, &$groupID));
    if(!$processed){
        add_event($username, $groupID, 'drugs_new', $drug_name);
    }
}
function drugs_update_record($drug_name, $drug_unit, $drug_amount, $drug_price, $drug_date, $drug_id){
    require("config/sql_connect.php");
    $sql = "UPDATE DrugsDB 
                SET name = ?, unit = ?, amount = ?, price = ?, overdue = ?
                WHERE id = ?";
    $processed = db_statement($sql, "ssidsi", array(&$drug_name, &$drug_unit, &$drug_amount, &$drug_price, &$drug_date, &$drug_id));
    if(!$processed){
        // jasne XD sam se zrób XD
    }
}
function drugs_get_info_from_id($drug_id){
    require("config/sql_connect.php");
    $drug = null;
    $sql = "SELECT  name, unit, amount, price, overdue, user_added, group_id
                FROM DrugsDB
                WHERE id = ?";
    $result = db_statement($sql, "i", array(&$drug_id));
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $drug['name'] = $row["name"];
        $drug['unit'] = $row["unit"];
        $drug['amount'] = $row["amount"];
        $drug['price'] = $row["price"];
        $drug['overdue'] = $row["overdue"];
        $drug['id'] = $drug_id;
    }
    return $drug;
}
function drugs_print_table($groupID, $page){
    require("config/sql_connect.php");
    $sql = "SELECT id, name, price, amount, overdue, unit 
                    FROM DrugsDB 
                    WHERE group_id = ?";
    $href = "drugs_overview.php";
    $sql_pag = paginate($sql, $href, 10, $page, array('i', array(&$groupID)));
    $result = db_statement($sql_pag, "i", array(&$groupID));
    if (mysqli_num_rows($result) > 0) {
        echo
        "<table class='table table-hover'>
                <thead>
                  <tr>
                    <th>Nazwa leku</th>
                    <th>Cena</th>
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
                "<td>" . $row["amount"] . " " . $row["unit"] . "</td>" .
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
                    <form action='drugs_edit.php' method='POST' id='edit_drugs'>
                    </form>";
    } else {
        echo
            "<div class='col-sm-4 col-sm-offset-4 inline-element-center'>".
            "<h4>Apteczka jest pusta.</h4>" .
            "<a href='drugs_new.php'><button type='button' class='btn btn-warning col-xs-12'>Dodaj nowy lek</button></a>".
            "</div>";
    }
}
function drug_name_from_id($drug_id){
    require("config/sql_connect.php");
    $sql = "SELECT name 
                FROM DrugsDB
                WHERE id = ?";
    $result = db_statement($sql, "i", array(&$drug_id));
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        return $row['name'];
    }
    else return false;
}
function drugs_take_drug($username, $groupID, $amount, $unit, $drugID, $amount_present){
    require("config/sql_connect.php");
    $drugName = drug_name_from_id($drugID);

    $sql = "UPDATE DrugsDB 
                SET amount = ?
                WHERE id = ?";
    $new_amount = $amount_present - $amount;
    $processed = db_statement($sql, "ii", array(&$new_amount, &$drugID));
    if(!$processed){
        add_event($username, $groupID, 'drugs_take', $drugName, $amount, $unit);
    }
    if ($new_amount == 0){
        drugs_delete_record($username, $drugID, $groupID);
        return true;
    }
    return false;
}
function drugs_delete_record($username, $drugID, $groupID){
    require("config/sql_connect.php");
    $drugName = drug_name_from_id($drugID);
    $sql = "SELECT amount, price_per_unit FROM DrugsDB
                    WHERE id = ?
                    AND group_id = ?";
    $result = db_statement($sql, "ii", array(&$drugID, &$groupID));
    $row = mysqli_fetch_assoc($result);
    $money_lost = $row['amount'] * $row['price_per_unit'];


    $sql = "DELETE FROM DrugsDB 
                    WHERE id = ?
                    AND group_id = ?";
    $processed = db_statement($sql, "ii", array(&$drugID, &$groupID));
    if(!$processed){
        add_event($username, $groupID, 'drugs_delete', $drugName, 0, "", $money_lost);
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
function drugs_overdue_print_table($groupID, $page){
    require("config/sql_connect.php");

    $sql = "SELECT id, name, overdue, amount, unit
                    FROM DrugsDB 
                    WHERE group_id = ?
                    AND DATE(overdue) < CURRENT_DATE()";
    $href = "drugs_overdue.php";
    $sql_pag = paginate($sql, $href, 10, $page, array("i", array(&$groupID)));
    $result = db_statement($sql_pag, "i", array(&$groupID));
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
                "<td>" . $row["amount"] . " " . $row["unit"] . "</td>" .
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
function drugs_overdue_soon_print_table($groupID, $soonInt, $page){
    require("config/sql_connect.php");
    $sql = "SELECT id, name, overdue, amount, unit
                    FROM DrugsDB 
                    WHERE group_id = ?
                    AND DATE(overdue) < CURRENT_DATE() + INTERVAL ? day
                    AND DATE(overdue) > CURRENT_DATE() - INTERVAL 1 day";
    $href = "drugs_soon.php";
    $sql_pag = paginate($sql, $href, 10, $page, array("ii", array(&$groupID, &$soonInt)));
    $result = db_statement($sql_pag, "ii", array(&$groupID, &$soonInt));
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
                "<td>" . $row["amount"] . " " . $row["unit"] . "</td>" .
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