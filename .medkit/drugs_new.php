<?php
	session_start();

	require_once("include/functions.php");

	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

    if(isset($_POST['ean'])){
        $specif_ean = $_POST['ean'];

        $result = specif_get_info_from_ean($specif_ean);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $drug_name = $row["drug_name"];
            $drug_unit = $row["unit"];
            $drug_amount = $row["per_package"];
            $drug_price = $row["price_per_package"];
        }

    }

	if(isset($_POST['drug-submit'])){
		$drug_name = $_POST['drug_name'];
		$drug_unit = $_POST['drug_unit'];
        $drug_amount = $_POST['drug_amount'];
		$drug_price = $_POST['drug_price'];
		$drug_date = $_POST['drug_date'];
		$username = $_SESSION['username'];
		$groupID = $_SESSION["groupID"];

		//Validate inputs - TBA

		drugs_new_record($drug_name, $drug_unit, $drug_amount, $drug_price, $drug_date, $username, $groupID);

		header("Location: drugs_new.php");
		exit();
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
	$drugsNew = 'class="active"'; // set "active" class for current page
	$showDropdownDrugs = "show"; // set drugs side-menu item to be permanently visible
	$header = "Dodaj nowy lek"; // set header string for current page
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

                            <h2>Pobierz dane ze specyfikacji leku o podanym numerze EAN i wypełnij formularz u dołu strony.</h2><hr>

                            <div class="form-group">
                                <div class="form-group">
                                    <input type="text" name="ean" class="form-control" id="ean" placeholder="Wpisz kod EAN" value=<?php echo "$specif_ean" ?>>
                                    <br />
                                    <button type="submit" class="btn btn-col btn-block">Pobierz dane</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

<br><br><br>

				<div class="container-fluid">
					<div class="col-md-12">
						<form action="" method="POST">

                            <h2>Lub ręcznie wypełnij poniższy formularz.</h2><hr>

							<div class="form-group">
								<label for="drug_name"><i class="fa fa-life-ring"></i> Nazwa leku</label>
								<input type="text" class="form-control" name="drug_name" placeholder="Wpisz nazwę nowego leku" required="required" value=<?php echo "$drug_name" ?>>
							</div>
							<div class="form-group">
								<label for="drug_unit">
									<i class="fa fa-pencil-square-o"></i> Rodzaj leku w opakowaniu</label>
								</label><br>

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
							<div class="form-group">
								<label for="drug_amount"><i class="fa fa-database"></i> Ilość leku w opakowaniu</label>
								<input type="number" min="0" step='0.01' class="form-control" name="drug_amount" placeholder="Wpisz ilość leku w opakowaniu" required="required" value=<?php echo "$drug_amount" ?>>
							</div>
							<div class="form-group">
								<label for="drug_price"><i class="fa fa-money"></i> Cena za opakowanie</label>
								<input type="number" min="0" step='0.01' class="form-control" name="drug_price" placeholder="Wpisz cenę" required="required" value=<?php echo "$drug_price" ?>>
							</div>
							<div class="form-group">
								<label for="drug_date"><i class="fa fa-hourglass-end"></i> Data ważności leku</label>
								<input type="date" class="form-control" name="drug_date" required value=<?php echo "$drug_date" ?>>
							</div>
							<br />

							<button type="submit" class="btn btn-col btn-block" name="drug-submit">Dodaj nowy lek</button>

						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>



</body>

</html>
