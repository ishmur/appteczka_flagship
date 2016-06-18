<?php
    session_start();

    require_once("include/functions.php");

    if(!isset($_SESSION['username'])){
        header("Location: index.php?logout=1");
        exit();
    }

    $username = $_SESSION['username'];
    $groupID = $_SESSION["groupID"];

    if(isset($_POST["drugs_edit"])) {

        $drug = drugs_get_info_from_id($_POST['drugs_edit'][0]);
        $drug_name = $drug['name'];
        $drug_unit = $drug['unit'];
        $drug_amount = $drug['amount'];
        $drug_price = $drug['price'];
        $drug_date = $drug['overdue'];
        $drug_id = $drug['id'];

    }

    if(!isset($drug)){

        header("Location: drugs_overview.php");
        exit();

    }

    if(isset($_POST['ean'])){
        $specif_ean = validate_trim_input($_POST['ean']);

        $ean_valid = validate_ean($specif_ean, $error_ean_text, $error_ean_flag);

        if ($ean_valid) {

            $result = specif_get_info_from_ean($specif_ean);
            if (mysqli_num_rows($result) == 1) {

                $row = mysqli_fetch_assoc($result);
                $drug_name = $row["drug_name"];
                $drug_unit = $row["unit"];
                $drug_amount = $row["per_package"];
                $drug_price = $row["price_per_package"];

            } else {

                $error_ean_flag = "has-error";
                $error_ean_text = "Nie znaleziono specyfikacji o podanym kodzie EAN.";
                $error_ean_prompt = true;

            }

        }

    }

    if(isset($_POST['edit-drug'])){

        $drug_name = validate_trim_input($_POST['drug_name']);
        $drug_unit = validate_trim_input($_POST['drug_unit']);
        $drug_amount = validate_trim_input($_POST['drug_amount']);
        $drug_price = validate_trim_input($_POST['drug_price']);
        $drug_date = validate_trim_input($_POST['drug_date']);
        $drug_id = $_POST['id'];

        $name_valid = validate_drug_name($drug_name, $error_name_text, $error_name_flag);
        $unit_valid = validate_drug_unit($drug_unit, $error_unit_text, $error_unit_flag);
        $amount_valid = validate_numeric_amount($drug_amount, $error_amount_text, $error_amount_flag);
        $price_valid = validate_numeric_amount($drug_price, $error_price_text, $error_price_flag);
        $date_valid = validate_date($drug_date, $error_date_text, $error_date_flag);
        $groupID_valid = !empty($groupID);

        if ($name_valid && $unit_valid && $amount_valid && $price_valid && $date_valid && $groupID_valid) {

            drugs_update_record($drug_name, $drug_unit, $drug_amount, $drug_price, $drug_date, $drug_id);
            $_SESSION['edit_drug'] = $drug_name;

            header("Location: drugs_overview.php");
            exit();

        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php
    $showDropdownDrugs = "show"; // set drugs side-menu item to be permanently visible
    $header = "Edytuj lek"; // set header string for current page
    include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br />

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3">

            <?php if(empty($groupID)) { ?>

                <div class="col-md-8 col-md-offset-2">
                    <div class="container-fluid">
                        <div class="col-md-12 inline-element-center">
                            <h1 style="color:red">Nie należysz do żadnej grupy - proszę wybrać grupę.</h1><br>
                            <a href="group_choose.php"><button type="button" class="btn btn-danger col-xs-12">Wybierz grupę</button></a>
                        </div>
                    </div>
                </div>

            <?php } else { ?>

                <div class="col-md-8 col-md-offset-2">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <form action="" method="POST">

                                <h2>Wypełnij formularz u dołu strony przy użyciu informacji ze specyfikacji leku o podanym numerze EAN:</h2><hr>

                                <div class="form-group">
                                    <div class="form-group <? echo $error_ean_flag; ?>">
                                        <p style="color:red"><?php echo $error_ean_text ?></p>
                                        <input type="text" name="ean" class="form-control" id="ean" placeholder="Wpisz kod EAN" value=<?php echo "$specif_ean" ?>>
                                        <br />
                                        <?php if(!isset($error_ean_prompt)) { ?>
                                            <button type="submit" class="btn btn-col btn-block">Pobierz dane</button>
                                        <?php } else { ?>
                                            <div class="container-fluid">
                                                <div class="col-sm-6">
                                                    <a href="specif_new.php"><button type="button" class="btn btn-warning col-xs-12">Dodaj nową specyfikację</button></a>
                                                </div>
                                                <div class="col-sm-6">
                                                    <button type="submit" class="btn btn-col btn-block col-xs-12">Pobierz dane</button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <br><br><br>

                    <div class="container-fluid">
                        <div class="col-md-12">
                            <form action="" method="POST">

                                <h2>Lub ręcznie wypełnij poniższy formularz:</h2><hr>

                                <div class="form-group <? echo $error_name_flag; ?>">
                                    <label for="drug_name"><i class="fa fa-life-ring"></i> Nazwa leku</label>
                                    <p style="color:red"><?php echo $error_name_text; ?></p>
                                    <input type="text" class="form-control" name="drug_name" placeholder="Wpisz nazwę nowego leku" required="required" value='<?php echo $drug_name ?>'>
                                </div>
                                <div class="form-group <? echo $error_unit_flag; ?>">
                                    <label for="drug_unit">
                                        <i class="fa fa-pencil-square-o"></i> Rodzaj leku w opakowaniu</label>
                                    </label>
                                    <p style="color:red"><?php echo $error_unit_text; ?></p>

                                    <select name="drug_unit" id="drug_unit" class="form-control" required>
                                        <?php if(!isset($drug_unit)) echo "<option value='' disabled selected>Proszę wybrać rodzaj leku w opakowaniu</option>" ?>
                                        <option value="szt." <?php specif_check_unit($drug_unit, "szt.") ?>>szt.</option>
                                        <option value="g" <?php specif_check_unit($drug_unit, "g") ?>>g</option>
                                        <option value="tabl." <?php specif_check_unit($drug_unit, "tabl.") ?>>tabl.</option>
                                        <option value="amp." <?php specif_check_unit($drug_unit, "amp.") ?>>amp.</option>
                                        <option value="sasz." <?php specif_check_unit($drug_unit, "sasz.") ?>>sasz.</option>
                                        <option value="fiol." <?php specif_check_unit($drug_unit, "fiol.") ?>>fiol.</option>
                                        <option value="but." <?php specif_check_unit($drug_unit, "but.") ?>>but.</option>
                                        <option value="kaps." <?php specif_check_unit($drug_unit, "kaps.") ?>>kaps.</option>
                                        <option value="amp.-strz." <?php specif_check_unit($drug_unit, "amp.-strz.") ?>>amp.-strz.</option>
                                        <option value="ml" <?php specif_check_unit($drug_unit, "ml") ?>>ml</option>
                                        <option value="op." <?php specif_check_unit($drug_unit, "op.") ?>>op.</option>
                                        <option value="j." <?php specif_check_unit($drug_unit, "j.") ?>>j.</option>
                                        <option value="inh." <?php specif_check_unit($drug_unit, "inh.") ?>>inh.</option>
                                        <option value="daw." <?php specif_check_unit($drug_unit, "daw.") ?>>daw.</option>
                                        <option value="wlew." <?php specif_check_unit($drug_unit, "wlew.") ?>>wlew.</option>
                                        <option value="plast." <?php specif_check_unit($drug_unit, "plast.") ?>>plast.</option>
                                        <option value="wkł." <?php specif_check_unit($drug_unit, "wkł.") ?>>wkł.</option>
                                        <option value="wstrz." <?php specif_check_unit($drug_unit, "wstrz.") ?>>wstrz.</option>
                                        <option value="zest." <?php specif_check_unit($drug_unit, "zest.") ?>>zest.</option>
                                    </select>
                                </div>
                                <div class="form-group <? echo $error_amount_flag; ?>">
                                    <label for="drug_amount"><i class="fa fa-database"></i> Ilość leku w opakowaniu</label>
                                    <p style="color:red"><?php echo $error_amount_text; ?></p>
                                    <input type="number" min="0" step='0.01' class="form-control" name="drug_amount" placeholder="Wpisz ilość leku w opakowaniu" required="required" value=<?php echo "$drug_amount" ?>>
                                </div>
                                <div class="form-group <? echo $error_price_flag; ?>">
                                    <label for="drug_price"><i class="fa fa-money"></i> Cena za opakowanie</label>
                                    <p style="color:red"><?php echo $error_price_text; ?></p>
                                    <input type="number" min="0" step='0.01' class="form-control" name="drug_price" placeholder="Wpisz cenę" required="required" value=<?php echo "$drug_price" ?>>
                                </div>
                                <div class="form-group <? echo $error_date_flag; ?>">
                                    <label for="drug_date"><i class="fa fa-hourglass-end"></i> Data ważności leku</label>
                                    <p style="color:red"><?php echo $error_date_text; ?></p>
                                    <input type="date" class="form-control" name="drug_date" required value=<?php echo "$drug_date" ?>>
                                </div>
                                <br />
                                <input type="hidden" name="id" value="<?php echo $drug_id ?>">
                                <div class="container-fluid">
                                    <div class="col-sm-6">
                                        <a href="drugs_overview.php"><button type="button" class="btn btn-danger col-xs-12">Anuluj</button></a>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="submit" name="edit-drug" class="btn btn-col btn-block col-xs-12">Zatwierdź zmiany</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>
    </div>
</div>



</body>

</html>
