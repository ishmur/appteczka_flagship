<!--
Variables for setting current page (set variable before inlcuding the file):
$activity = Historia aktywności
$drugsOverview = Przegląd leków
$drugsNew = Dodaj nowy lek
$drugsOverdue = Leki przeterminowane
$statistics = Statystyki
$settings = Ustawienia
-->

<nav class="navbar navbar-inverse visible-xs">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
	  <a <?php echo $showSettings ?> href="settings.php" class="navbar-button" ><i class="fa fa-cogs"></i></a>
	  <a href="index.php?logout=1" class="navbar-button"><i class="fa fa-sign-out"></i></a>
      <p class="navbar-brand inline-element-center">
		<i class="fa fa-medkit" style="font-size:20px"></i>	App.teczka
	  </p>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li <?php echo $activity ?> ><a href="home.php">Ostatnia aktywność</a></li>
        <li <?php echo $drugsOverview ?> ><a href="drugs_overview.php">Przegląd leków</a></li>
        <li <?php echo $drugsNew ?> ><a href="drugs_new.php">Dodaj nowy lek</a></li>
		<li <?php echo $drugsSpecification ?> ><a href="drugs_specification.php">Dodaj specyfikację leku</a></li>
        <li <?php echo $drugsOverdue ?> ><a href="drugs_overdue.php">Lista leków przeterminowanych</a></li>
		<li <?php echo $statistics ?> ><a href="statistics.php">Statystyki</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
	<div class="row">
	
		<div class="col-sm-3 sidenav hidden-xs" id="sideNav">
			<h3><i class="fa fa-medkit" style="font-size:30px"></i> App.teczka<hr /></h3>
			<ul class="nav nav-pills nav-stacked">
				<li <?php echo $activity ?> ><a href="home.php">Ostatnia aktywność</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle">Stan apteczki</a>
					<div class="dropdown-content col-sm-offset-2 <?php echo $showDropdown ?>">
						<a <?php echo $drugsOverview ?> href="drugs_overview.php">Przegląd leków</a>
						<a <?php echo $drugsNew ?> href="drugs_new.php">Dodaj nowy lek</a>
						<a <?php echo $drugsSpecification ?> href="drugs_specification.php">Dodaj specyfikację leku</a>
						<a <?php echo $drugsOverdue ?> href="drugs_overdue.php">Lista leków przeterminowanych</a>
					</div>
				</li>
				<li <?php echo $statistics ?> ><a href="statistics.php">Statystyki</a></li>
				<li <?php echo $settings ?> ><a href="settings.php">Ustawienia użytkownika</a></li>
				<li><a href="index.php?logout=1">Wyloguj</a></li>
			</ul><br>
		</div>
		

		<div class="col-sm-9 col-sm-offset-3" style="padding:0">
			<div class="jumbotron text-center">
				<h1><?php echo $header ?></h1> 
				<h2><br \><?php echo "Zalogowany jako: " . $_SESSION['username'] ?></h2>
			</div>
		</div>
		
	</div>
</div>
