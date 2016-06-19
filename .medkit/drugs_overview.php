<?php
	session_start();

	require_once("include/functions.php");
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php?logout=1");
		exit();
	}

	$groupID = $_SESSION["groupID"];
	$username = $_SESSION["username"];

	if(isset($_POST['search'])) {

		$drug_name = validate_trim_input($_POST['search']);

		if (!preg_match("/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻ a-zA-Z0-9,.\-\/]*$/", $drug_name)) {
			$error_name_text = "Nazwa leku może składać się wyłącznie z liter, cyfr, kropek, przecinków, spacji i znaków '/-'.";
			$error_name_flag = "has-error";
		}
	}

	if(isset($_POST['drugs'])) {
		foreach ($_POST['drugs'] as $drugID) {
			drugs_delete_record($username, $drugID, $groupID);
		}
		$_SESSION['deleted_drugs'] = true;
		header("Location: drugs_overview.php");
		exit();
	}

    if(isset($_POST["drugs_take_amount"])){
        $name = $_POST['drugs_take_name'];
        $unit = $_POST['drugs_take_unit'];
        $id = $_POST["drugs_take_id"];
        $amount_present = $_POST["drugs_take_present"];

        $amount = validate_trim_input($_POST["drugs_take_amount"]);
        $amount_valid = validate_numeric_amount($amount, $error_take_text, $error_take_flag);

        if ($amount_valid) {
            $result = drugs_take_drug($username, $groupID, $amount, $unit, $id, $amount_present);
            $_SESSION['taken_drug'] = $name;
            $_SESSION['taken_drug_amount'] = $amount . " " . $unit;
            if ($result) {
                $_SESSION['taken_drug_delete_prompt'] = true;
            }
            header("Location: drugs_overview.php");
            exit();
        }
    }

?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
  <title>Lista leków</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/modal.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">
</head>

<body id="bodyTag">

<?php 
	$drugsOverview = 'class="active"'; // set "active" class for current page
	$showDropdownDrugs = "show"; // set drugs side-menu item to be permanently visible
	$header = "Przegląd leków"; // set header string for current page
	include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br />

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3">

			<?php if(isset($_SESSION['new_drug'])){ ?>
				<div class="alert alert-success">
					Dodano nowy lek: <strong><? echo $_SESSION['new_drug']?></strong>!
				</div>
			<?php $_SESSION['new_drug'] = null; } ?>

			<?php if(isset($_SESSION['edit_drug'])){ ?>
				<div class="alert alert-success">
					Edytowano lek! Obecna nazwa leku to: <strong><? echo $_SESSION['edit_drug']?></strong>.
				</div>
			<?php $_SESSION['edit_drug'] = null; } ?>

			<?php if(isset($_SESSION['deleted_drugs'])){ ?>
				<div class="alert alert-success">
					Usunięto zaznaczone leki!
				</div>
			<?php $_SESSION['deleted_drugs'] = null; } ?>

            <?php if(isset($error_take_flag)){ ?>
                <div class="alert alert-danger">
                    Coś poszło nie tak... prosimy spróbować ponownie później.
                </div>
            <?php $error_take_flag = null; } ?>

			<?php if(isset($_SESSION['taken_drug'])){ ?>
				<div class="alert alert-success">
                    Wzięto lek <strong><? echo $_SESSION['taken_drug']?></strong> w ilości <strong><? echo $_SESSION['taken_drug_amount']?></strong>.
                    <?php if (isset($_SESSION['taken_drug_delete_prompt'])){ ?>
                        <br>Opakowanie jest puste!
                    <?php } ?>
				</div>
			<?php
               $_SESSION['taken_drug'] = null;
                $_SESSION['taken_drug_amount'] = null;
            } ?>

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
						<form class="" method="POST">
							<div class="form-group <? echo $error_name_flag; ?>">
								<label for="drugsSearch"><i class="fa fa-question-circle"></i> Szukaj leku...</label>
								<p style="color:red"><?php echo $error_name_text ?></p>
								<input type="text" name="search" class="form-control" id="drugsSearch" placeholder="Wpisz nazwę poszukiwanego leku" value="<? echo $_POST['search'] ?>">
							<br />
							<button type="submit" class="btn btn-col btn-block">Szukaj</button>
							</div>
						</form>
						<div class="container-fluid">
							
							  <br /><h2>Wyniki wyszukiwania</h2><hr />
							  
								
									<?php
										if(!isset($_GET['p'])) $_GET['p'] = 1;
										if(!isset($error_name_flag)) drugs_print_table($groupID, $_GET['p'], $_POST['search']);
										else drugs_print_table($groupID, $_GET['p']);
									?>

						</div>
					</div>
				</div>
			</div>

            <?php } ?>
			
		</div>
	</div>
	
</div>

<!-- Modal -->
<div class="modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->

		<div class="modal-content" >

			<div class="modal-header">
				<h4 style="color:white;"><i class="fa fa-hourglass-end"></i> Weź lek</h4>
			</div>

			<div class="modal-body" id="ajaxCall">
                <!-- table from AJAX call -->
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" id="modalCancelBtn">
					<i class="fa fa-ban"></i> Anuluj
				</button>
                <form action='' method='POST' id='take_drugs'>
                    <button type='submit' name='take-submit' class='btn btn-success pull-right' id="take_drugs_submit">
                        <i class="fa fa-share"></i> Akceptuj
                    </button>
                </form>
			</div>

		</div>
	</div>
</div>

<script src="js/drugs_overview.js"></script>

</body>

</html>