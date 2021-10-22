<?php
	// HEADER
	echo "<header>
			<img id='logo' src='../img/header.png' alt='header'>
		</header>";
	
	// MENU
	echo "<nav id='menu'>";
		
	echo "<a ";
	if(basename($_SERVER['PHP_SELF']) == "home.php")
		echo "class='selected'";
	echo " href='./home.php'><img src='../img/icons/home.png' alt='home'>Home</a>";
	
	echo "<a ";
	if(basename($_SERVER['PHP_SELF']) == "prestiti.php")
		echo "class='selected'";
	echo " href='./prestiti.php'><img src='../img/icons/prestiti.png' alt='prestiti'>Prestiti</a>";
	
	if($_SESSION['modalita'] == 'B'){
		echo "<a ";
		if(basename($_SERVER['PHP_SELF']) == "tessere.php")
		echo "class='selected'";
		echo " href='./tessere.php?tipo=daconsegnare'><img src='../img/icons/tessere.png' alt='tessere'>Tessere</a>";
	}
	
	echo "<a ";
	if(basename($_SERVER['PHP_SELF']) == "cerca.php")
		echo "class='selected'";
	echo " href='./cerca.php'><img src='../img/icons/cerca.png' alt='cerca'>Cerca</a>";
	
	echo "<a ";
	if(basename($_SERVER['PHP_SELF']) == "impostazioni.php")
		echo "class='selected'";
	echo " href='./impostazioni.php'><img src='../img/icons/impostazioni.png' alt='impostazioni'>Impostazioni</a>";
	
	echo "<a href='./logout.php'><img src='../img/icons/logout.png' alt='logout'>Logout</a>";
	
	echo "</nav>";
	// INFO UTENTE
	echo "<div id='user'><img src='../img/uploads/profile/";
	if(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".png"))
		echo $_SESSION['username'] . ".png";
	elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".jpg"))
		echo $_SESSION['username'] . ".jpg";
	elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".gif"))
		echo $_SESSION['username'] . ".gif";
	elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".jpeg"))
		echo $_SESSION['username'] . ".jpeg";
	else
		echo "default.png";
	echo "' id='usericon' alt='usericon'><div id='name'>" . $_SESSION['username'] . "</div></div>";
?>