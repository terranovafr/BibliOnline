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
		<title>BibliOnline - Cerca</title>
	</head>
	<body>		
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<div>
			<h2>Cerca</h2>
			<div id="cerca">
			<form name="cerca" method="post" action="cerca.php">
				<input type="text" name="pattern" placeholder="Cerca per titolo, autore, casa editrice.." autofocus>
				<input type="submit" name="cerca" value="Cerca">
			</form>
			</div>
			</div>
			<section id="risultati">
				<h4>Risultati</h4>
				<?php
					require_once "DBFunctions.php";
					if(isset($_POST['cerca']) && ($_POST['pattern'] != '')){
						listaLibri(cerca($_POST['pattern']));
					} else {
						if($_SESSION['modalita'] == 'B')
							echo "<div id='empty'>Usa la barra di ricerca per cercare un libro all'interno della tua biblioteca per<br> Titolo, Autore, Casa Editrice, Genere o Codice ISBN!</div>";
						elseif($_SESSION['modalita'] == 'U')
							echo "<div id='empty'>Usa la barra di ricerca per cercare un libro per <br> Titolo, Autore, Casa Editrice, Genere o Codice ISBN!</div>";
					}
				?>
			</section>
		</section>
	</body>
</html>