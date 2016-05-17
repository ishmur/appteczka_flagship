<?php
	session_start();
	
	require_once("include/functions.php");
	
	$errorFlag;
	
	$usrErrNotification;
	$usrErrMsg;
	$pswErrNotification;
	$pswErrMsg;
	
	$show_modal;
	
	if($_GET['logout']==1){
		session_destroy();
		session_start();
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//General validation of inputs
		$username = trim_input($_POST['login']);
		$password = trim_input($_POST['password']);

		//Validate username
		if(empty($username)){
			$show_modal = "style='display:block'";
			$usrErrNotification = "has-error";
			$usrErrMsg = "Pole nie może być puste.";
			$errorFlag = true;
			$username = NULL;
			$pswErrNotification = "has-error";
		}
		elseif(!preg_match("/^[A-Za-z0-9_]+$/", $username)){
			$show_modal = "style='display:block'";
			$usrErrNotification = "has-error";
			$usrErrMsg = "Nieprawidłowy login.";
			$errorFlag = true; 
			$username = NULL;
			$pswErrNotification = "has-error";
		}
	    
		//Validate password
		if(empty($password)){
			$show_modal = "style='display:block'";
			$pswErrNotification = "has-error";
			$pswErrMsg = "Pole nie może być puste.";
			$errorFlag = true;
		}
		elseif(!preg_match("/^[A-Za-z0-9_]+$/", $password)){
			$show_modal = "style='display:block'";
			$pswErrNotification = "has-error";
			$pswErrMsg = "Nieprawidłowe hasło.";
			$errorFlag = true; 
		}
		elseif(!check_login($login, $password)){
			$show_modal = "style='display:block'";
			$pswErrNotification = "has-error";
			$pswErrMsg = "Nieprawidłowe hasło.";
			$errorFlag = true; 
		}
		
		// Proceed if no errors
		if(!$errorFlag){
			$_SESSION['username'] = $username;
			$_SESSION['drugsOverdueModal'] = "show";
			header("Location: home.php");
			exit();
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Index</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/modal.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">

</head>
<body id="bodyTag">

<div class="container" id="contentContainer">

  <div class="row">

    <div class="col-sm-7">
      <div class="container-fluid inline-element-center">
        <i class="fa fa-user-md" style="font-size:300px"></i>
      </div>
    </div>

    <div class="col-sm-5">

      <div class="container-fluid">
        <div class="row">
          <div class="container-fluid jumbotron inline-element-center">
		  <h1 style="font-size:25px">
			<i class="fa fa-medkit" ></i> App.teczka<br /><br />
			System zarządzania <br />domową apteczką</h1>
          </div>
        </div>
      </div>

        <div class="row">
          <div class="container-fluid">

            <button type="button" class="btn btn-lg btn-block btn-col" id="btnLogin">Logowanie</button>

            <!-- Modal -->
            <div class="modal" role="dialog" <?php echo $show_modal ?> >
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                <div class="modal-header">
                  <button type="button" class="close">&times;</button>
                  <h4 style="color:white;"><span class="glyphicon glyphicon-home"></span> Logowanie</h4>
                </div>

                  <div class="modal-body">
                    <form action="" method="POST">
						<div class="form-group <?php echo $usrErrNotification ?>">
							<label for="usrname"><span class="glyphicon glyphicon-user"></span> Nazwa użytkownika</label>
							<p style="color:red"><?php echo $usrErrMsg ?></p>
							<input type="text" name="login" class="form-control" id="usrname" placeholder="Nazwa użytkownika" value=<?php echo "$username" ?>>
						</div>
						<div class="form-group <?php echo $pswErrNotification ?>">
							<label for="psw"><span class="glyphicon glyphicon-lock"></span> Hasło</label>
							<p style="color:red"><?php echo $pswErrMsg ?></p>
							<input type="text" name="password" class="form-control" id="psw" placeholder="Hasło">
						</div>
						<div class="checkbox">
							<label><input type="checkbox" value="" checked>Zapamiętaj mnie</label>
						</div>
						<button type="submit" class="btn btn-col btn-block"><span class="glyphicon glyphicon-off"></span> Zaloguj</button>
                    </form>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-default pull-left" id="modalCancelBtn">
						<span class="glyphicon glyphicon-remove"></span> Cofnij
					</button>
                    <p><a href="#">Zarejestruj się</a></p>
                    <p><a href="#">Zapomniałem hasła</a></p>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="container-fluid">
            <!-- This button is temporary until login functionality has been implemented -->
            <form action="" method="POST">
				<input type="hidden" name="login" value="admin">
				<input type="hidden" name="password" value="admin">
				<button type="submit" class="btn btn-lg btn-block btn-col" id="btnHome">Zaloguj jako admin</button>
			</form>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

<script src="js/index.js"></script>

</body>
</html>
