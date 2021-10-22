<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
		
	if(!isset($_GET['libro']))
		redirect('./home.php');
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
		<link rel="stylesheet" href="../css/BibliOnline_libro.css" type="text/css" media="screen">
		<title>BibliOnline</title>
	</head>
	<body>	
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<?php 
				// CONNESSIONE
				require_once "DBFunctions.php";
				
				// LIBRO
				mostraLibro(Libro($_GET['libro'])); 
				
				// SE UTENTE DAI LA POSSIBILITA' DI PRENOTARE
				if($_SESSION['modalita'] == 'U'){
					echo "<form name='prenota' action='./prenota.php' method='post'>
							<label>Biblioteche disponibili :</label>
							<table>
								<tr>
									<th>Nome Biblioteca</th>
									<th>Indirizzo</th>
								</tr>";
								if(BibliotecheDisponibili($_GET['libro']))
									echo " </table><input type='hidden' name='codiceisbn' value='" . $_GET['libro'] . "'>
											<input type='submit' name='prenota' value='Prenota'>";
					echo "</form>";
				}
			?>
		</section>
	</body>
</html>