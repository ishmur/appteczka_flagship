<?php
session_start();

require_once("include/functions.php");

if(!isset($_SESSION['username'])){
    header("Location: index.php?logout=1");
    exit();
}

if(isset($_POST['specif'])) {
    foreach ($_POST['specif'] as $specifID) {
        specif_delete_record($specifID);
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
    $specificationOverview = 'class="active"'; // set "active" class for current page
    $showDropdownSpecification = "show"; // set drugs side-menu item to be permanently visible
    $showSpecification = 'style="color:white"'; // change color of settings top-navbar icon
    $header = "Przegląd specyfikacji leków"; // set header string for current page
    include("include/navigation.php"); // load template html with top-navigation bar, side-navigation bar and header
?>

<br />

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3">

            <div class="col-md-8 col-md-offset-2">
                <div class="container-fluid">
                    <div class="col-md-12">
                        <form class="" method="POST">
                        <div class="form-group">
                            <label for="drugsSearch"><i class="fa fa-question-circle"></i> Szukaj specyfikacji...</label>
                            <input type="text" name="ean" class="form-control" id="ean" placeholder="Wpisz kod EAN">
                            <br />
                            <button type="submit" class="btn btn-col btn-block">Szukaj</button>
                        </div>
                        </form>
                        <div class="container-fluid">

                                <br /><h2>Wyniki wyszukiwania</h2><hr />
                                
                                    <?php
                                        if(!isset($_POST['ean'])) {
                                            if (isset($_GET['p'])) $pag_query = specif_pagination($_GET['p']);
                                            else $pag_query = specif_pagination();
                                            specif_print_table($pag_query);
                                        } else {
                                            specif_print_ean($_POST['ean']);
                                        }
                                    ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

</body>

</html>