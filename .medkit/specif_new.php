<?php
	session_start();

	require_once("include/functions.php");

	if(!isset($_SESSION['username'])){

		header("Location: index.php?logout=1");
		exit();
	}

	if(isset($_POST['specif-submit'])) {

        $specif_name = validate_trim_input($_POST['specif_name']);
        $specif_EAN = validate_trim_input($_POST['specif_EAN']);
        $specif_per_package = validate_trim_input($_POST['specif_per_package']);
        $specif_unit = validate_trim_input($_POST['specif_unit']);
        $specif_price = validate_trim_input($_POST['specif_price']);
        $specif_active = validate_trim_input($_POST['specif_active']);

        $name_valid = validate_drug_name($specif_name, $error_name_text, $error_name_flag);
        $ean_valid = validate_ean($specif_EAN, $error_ean_text, $error_ean_flag);
        $unit_valid = validate_drug_unit($specif_unit, $error_unit_text, $error_unit_flag);
        $amount_valid = validate_numeric_amount($specif_per_package, $error_per_package_text, $error_per_package_flag);
        $price_valid = validate_numeric_amount($specif_price, $error_price_text, $error_price_flag);
        $active_valid = validate_active($specif_active, $error_active_text, $error_active_flag);


        if ($name_valid && $ean_valid && $unit_valid && $amount_valid && $price_valid && $active_valid) {

            $result = specif_new_record($specif_name, $specif_EAN, $specif_per_package, $specif_unit, $specif_price, $specif_active);

            if ($result == "duplicate") {

                $error_ean_flag = "has-error";
                $error_ean_text = "Kod EAN musi być unikatowy (w bazie danych znajduje się już specyfik o danym kodzie).";

            } else {

                $_SESSION['new_specif'] = $specif_name;
                header("Location: specif_overview.php");
                exit();

            }

        }
    }

?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
  <title>Nowa specyfikacja</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php 
	$specificationNew = 'class="active"'; // set "active" class for current page
	$showDropdownSpecification = "show"; // set drugs side-menu item to be permanently visible
	$showSpecification = 'style="color:white"'; // change color of settings top-navbar icon
	$header = "Dodaj specyfikację leku"; // set header string for current page
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
							<div class="form-group <? echo $error_name_flag; ?>">
								<label for="specif_name"><i class="fa fa-tags"></i> Nazwa leku</label>
								<p style="color:red"><?php echo $error_name_text; ?></p>
								<input type="text" class="form-control" name="specif_name" placeholder="Wpisz nazwę nowego leku" required="required" value=<?php echo "$specif_name" ?>>
							</div>
							<div class="form-group <? echo $error_ean_flag; ?>">
								<label for="specif_EAN"><i class="fa fa-hashtag"></i> Kod EAN</label>
                                <p style="color:red"><?php echo $error_ean_text ?></p>
								<input type="text" class="form-control" name="specif_EAN" placeholder="Wpisz kod EAN" required="required" value=<?php echo "$specif_EAN" ?>>
							</div>
                            <div class="form-group <? echo $error_unit_flag; ?>">
								<label for="specif_unit">
									<i class="fa fa-pencil-square-o"></i> Rodzaj leku w opakowaniu</label>
								</label>
                                <p style="color:red"><?php echo $error_unit_text; ?></p>

								<select name="specif_unit" id="specif_unit" class="form-control" required>
									<?php if(!isset($specif_unit)) echo "<option value='' disabled selected>Proszę wybrać rodzaj leku w opakowaniu</option>" ?>
                                    <option value="szt." <?php specif_check_unit($specif_unit, "szt.") ?>>szt.</option>
                                    <option value="g" <?php specif_check_unit($specif_unit, "g") ?>>g</option>
                                    <option value="tabl." <?php specif_check_unit($specif_unit, "tabl.") ?>>tabl.</option>
                                    <option value="amp." <?php specif_check_unit($specif_unit, "amp.") ?>>amp.</option>
                                    <option value="sasz." <?php specif_check_unit($specif_unit, "sasz.") ?>>sasz.</option>
                                    <option value="fiol." <?php specif_check_unit($specif_unit, "fiol.") ?>>fiol.</option>
                                    <option value="but." <?php specif_check_unit($specif_unit, "but.") ?>>but.</option>
                                    <option value="kaps." <?php specif_check_unit($specif_unit, "kaps.") ?>>kaps.</option>
                                    <option value="amp.-strz." <?php specif_check_unit($specif_unit, "amp.-strz.") ?>>amp.-strz.</option>
                                    <option value="ml" <?php specif_check_unit($specif_unit, "ml") ?>>ml</option>
                                    <option value="op." <?php specif_check_unit($specif_unit, "op.") ?>>op.</option>
                                    <option value="j." <?php specif_check_unit($specif_unit, "j.") ?>>j.</option>
                                    <option value="inh." <?php specif_check_unit($specif_unit, "inh.") ?>>inh.</option>
                                    <option value="daw." <?php specif_check_unit($specif_unit, "daw.") ?>>daw.</option>
                                    <option value="wlew." <?php specif_check_unit($specif_unit, "wlew.") ?>>wlew.</option>
                                    <option value="plast." <?php specif_check_unit($specif_unit, "plast.") ?>>plast.</option>
                                    <option value="wkł." <?php specif_check_unit($specif_unit, "wkł.") ?>>wkł.</option>
                                    <option value="wstrz." <?php specif_check_unit($specif_unit, "wstrz.") ?>>wstrz.</option>
                                    <option value="zest." <?php specif_check_unit($specif_unit, "zest.") ?>>zest.</option>
								</select>
							</div>
                            <div class="form-group <? echo $error_per_package_flag; ?>">
								<label for="specif_per_package"><i class="fa fa-database"></i> Ilość leku w opakowaniu</label>
                                <p style="color:red"><?php echo $error_per_package_text; ?></p>
								<input type="number" min="0" step='0.01' class="form-control" name="specif_per_package" placeholder="Wpisz ilość leku w opakowaniu" required="required" value=<?php echo "$specif_per_package" ?>>
							</div>
                            <div class="form-group <? echo $error_price_flag; ?>">
								<label for="specif_price"><i class="fa fa-money"></i> Cena za opakowanie</label>
                                <p style="color:red"><?php echo $error_price_text; ?></p>
								<input type="number" min="0" step='0.01' class="form-control" name="specif_price" placeholder="Wpisz cenę" required="required" value=<?php echo "$specif_price" ?>>
							</div>
                            <div class="form-group <? echo $error_active_flag; ?>">
								<label for="specif_active"><i class="fa fa-flask"></i> Substancja czynna</label>
                                <p style="color:red"><?php echo $error_active_text; ?></p>
								<input type="text" class="form-control" name="specif_active" placeholder="Wpisz substancję czynną" required="required" value=<?php echo "$specif_active" ?>>
							</div>
							<br />
                            <div class="container-fluid">
                                <div class="col-sm-6">
                                    <a href="specif_overview.php"><button type="button" class="btn btn-danger col-xs-12">Wróć do listy specyfikacji</button></a>
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" name="specif-submit" class="btn btn-col btn-block">Dodaj nową specyfikację</button>
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
