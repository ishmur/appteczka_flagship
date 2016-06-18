<?php
    session_start();

    require_once("include/functions.php");

    if(!isset($_SESSION['username'])){

        header("Location: index.php?logout=1");
        exit();

    }

    if(isset($_POST["specif_edit"])) {

        $specif = specif_get_info($_POST['specif_edit'][0]);

    }

    if(isset($_POST["edit-specification"])){

        $specif_name = $_POST['specif_name'];
        $specif_EAN = $_POST['specif_EAN'];
        $specif_per_package = $_POST['specif_per_package'];
        $specif_unit = $_POST['specif_unit'];
        $specif_price = $_POST['specif_price'];
        $specif_active = $_POST['specif_active'];
        $specif_id = $_POST['id_spec'];

        //Validate inputs - TBA

        $result = specif_update_record($specif_name, $specif_EAN, $specif_per_package, $specif_unit, $specif_price, $specif_active, $specif_id);

        if ($result == "duplicate"){

            $error_flag_ean = "has-error";
            $error_ean = "Kod EAN musi być unikatowy (w bazie znajduje się już specyfik o danym kodzie).";

            $specif['drug_name'] = $specif_name;
            $specif['ean'] = $specif_EAN;
            $specif['per_package'] = $specif_per_package;
            $specif['unit'] = $specif_unit;
            $specif['price_per_package'] = $specif_price;
            $specif['active'] = $specif_active;
            $specif['id_spec'] = $specif_id;

        } else {
            header("Location: specif_overview.php");
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
    $showDropdownSpecification = "show"; // set drugs side-menu item to be permanently visible
    $showSpecification = 'style="color:white"'; // change color of settings top-navbar icon
    $header = "Edytuj specyfikację leku"; // set header string for current page
    include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br />

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3">

            <div class="col-md-8 col-md-offset-2">
                <div class="container-fluid">
                    <div class="col-md-12">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="specif_name"><i class="fa fa-tags"></i> Nazwa leku</label>
                                <input type="text" class="form-control" name="specif_name" placeholder="Wpisz nową nazwę leku" required="required" value="<?php echo $specif['drug_name'] ?>">
                            </div>
                            <div class="form-group <? echo $error_flag_ean; ?>">
                                <label for="specif_EAN"><i class="fa fa-hashtag"></i> Kod EAN</label>
                                <p style="color:red"><?php echo $error_ean ?></p>
                                <input type="text" class="form-control" name="specif_EAN" placeholder="Wpisz nowy kod EAN" required="required" value="<?php echo $specif['ean'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="specif_unit">
                                    <i class="fa fa-pencil-square-o"></i> Rodzaj leku w opakowaniu</label>
                                </label><br>

                                <select name="specif_unit" id="specif_unit" class="form-control" required>
                                    <option value="szt." <?php specif_check_unit($specif['unit'], "szt.") ?>>szt.</option>
                                    <option value="g" <?php specif_check_unit($specif['unit'], "g") ?>>g</option>
                                    <option value="tabl." <?php specif_check_unit($specif['unit'], "tabl.") ?>>tabl.</option>
                                    <option value="amp." <?php specif_check_unit($specif['unit'], "amp.") ?>>amp.</option>
                                    <option value="sasz." <?php specif_check_unit($specif['unit'], "sasz.") ?>>sasz.</option>
                                    <option value="fiol." <?php specif_check_unit($specif['unit'], "fiol.") ?>>fiol.</option>
                                    <option value="but." <?php specif_check_unit($specif['unit'], "but.") ?>>but.</option>
                                    <option value="kaps." <?php specif_check_unit($specif['unit'], "kaps.") ?>>kaps.</option>
                                    <option value="amp.-strz." <?php specif_check_unit($specif['unit'], "amp.-strz.") ?>>amp.-strz.</option>
                                    <option value="ml" <?php specif_check_unit($specif['unit'], "ml") ?>>ml</option>
                                    <option value="op." <?php specif_check_unit($specif['unit'], "op.") ?>>op.</option>
                                    <option value="j." <?php specif_check_unit($specif['unit'], "j.") ?>>j.</option>
                                    <option value="inh." <?php specif_check_unit($specif['unit'], "inh.") ?>>inh.</option>
                                    <option value="daw." <?php specif_check_unit($specif['unit'], "daw.") ?>>daw.</option>
                                    <option value="wlew." <?php specif_check_unit($specif['unit'], "wlew.") ?>>wlew.</option>
                                    <option value="plast." <?php specif_check_unit($specif['unit'], "plast.") ?>>plast.</option>
                                    <option value="wkł." <?php specif_check_unit($specif['unit'], "wkł.") ?>>wkł.</option>
                                    <option value="wstrz." <?php specif_check_unit($specif['unit'], "wstrz.") ?>>wstrz.</option>
                                    <option value="zest." <?php specif_check_unit($specif['unit'], "zest.") ?>>zest.</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="specif_per_package"><i class="fa fa-database"></i> Ilość leku w opakowaniu</label>
                                <input type="number" min="1" class="form-control" name="specif_per_package" placeholder="Wpisz ilość leku w opakowaniu" required="required" value="<?php echo $specif['per_package'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="specif_price"><i class="fa fa-money"></i> Cena za opakowanie</label>
                                <input type="number" min="1" class="form-control" name="specif_price" placeholder="Wpisz cenę" required="required" value="<?php echo $specif['price_per_package'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="specif_active"><i class="fa fa-flask"></i> Substancja czynna</label>
                                <input type="text" class="form-control" name="specif_active" placeholder="Wpisz substancję czynną" required="required" value="<?php echo $specif['active'] ?>">
                            </div>
                            <br />
                            <input type="hidden" name="id_spec" value="<?php echo $specif['id_spec'] ?>">
                            <div class="container-fluid">
                                <div class="col-sm-6">
                                    <a href="specif_overview.php"><button type="button" class="btn btn-danger col-sm-12">Anuluj</button></a>
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" name="edit-specification" class="btn btn-col btn-block">Zatwierdź nową specyfikację</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>

</html>
