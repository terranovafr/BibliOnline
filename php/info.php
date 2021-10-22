<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
?>

<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8"> 
    	<meta name = "author" content = "Terranova Franco">
    	<meta name = "keywords" content = "BibliOnline">
   	 	<link rel="icon" href="../img/icon.ico" />
		<link rel="stylesheet" href="../css/BibliOnline.css" type="text/css" media="screen">
		<link rel="stylesheet" href="../css/BibliOnline_menu.css" type="text/css" media="screen">
		<title>Info</title>
	</head>
	<body>		
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<h2>Informazioni</h2>
			<div id="info">
				<?php
					require_once "DBFunctions.php";
					Info($_GET['account']);
				?>
			</div>
		</section>
	</body>
</html>