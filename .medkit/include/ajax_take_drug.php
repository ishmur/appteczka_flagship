<?php

    require_once("functions.php");
    require_once("../config/sql_connect.php");

    $drugID = intval($_GET['id']);

    $sql = "SELECT name, amount, unit 
            FROM DrugsDB 
            WHERE id = ?";

    $result = db_statement($sql, "i", array(&$drugID));

    if (mysqli_num_rows($result) > 0) {

        echo
            "<table class='table table-hover'>
            <thead>
              <tr>
                <th>Nazwa leku</th>
                <th>Pozostała ilość leku</th>
                <th>Weź...</th>
              </tr>
            </thead>
            <tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo
                "<tr>" .
                    "<td>" . $row["name"] . "</td>" .
                    "<td>" . $row["amount"] . " " . $row["unit"] . "</td>" .
                    "<td>".
                        "<input form='take_drugs' type='hidden' name='drugs_take_name' value='".$row["name"]."'>".
                        "<input form='take_drugs' type='hidden' name='drugs_take_unit' value='".$row["unit"]."'>".
                        "<input form='take_drugs' type='hidden' name='drugs_take_id' value='".$drugID."'>".
                        "<input form='take_drugs' type='hidden' name='drugs_take_present' value='".$row["amount"]."'>".
                        "<input form='take_drugs' type='number' name='drugs_take_amount' min='0' max='".$row["amount"]."'>".
                    "</td>".
                "<tr>";
        }

        echo
            "</tbody>
             </table>";

        echo
            "<hr><ul><b>Przydatne jednostki miary:</b>
                <li>łyżeczka - 5 ml</li>
                <li>łyżka stołowa - 15 ml</li>
            </ul></p>";
    }

?>