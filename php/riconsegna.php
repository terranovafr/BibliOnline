<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
	
	// ACCESSO LIMITATO ALLE BIBLIOTECHE
	if(!($_SESSION['modalita'] == 'B'))
		redirect('./prestiti.php');
	
	if(isset($_POST['riconsegna'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
	
		// RECUPERO PARAMETRI
		$prestito = $mysqli->real_escape_string($_POST['prestito']);
		$tessera = $mysqli->real_escape_string($_POST['tessera']);
		$utente = $mysqli->real_escape_string($_POST['utente']);
	
		$query = "SELECT *
				  FROM TESSERA
				  WHERE CODICEBARRE ='" . $tessera . "' AND UTENTE='" . $utente . "';";
		$result = $mysqli->query($query);
		$numRow = mysqli_num_rows($result);
		
		if ($numRow == 1){
			// SE TESSERA CORRETTA AGGIORNA DATI PRESTITO
			$query = "UPDATE PRESTITO
					  SET DATARICONSEGNAEFFETTIVA='". date("Y-m-d") 
					  . "' WHERE ID='" . $prestito . "';";
			$result = $mysqli->query($query);
		} else redirect("./riconsegna.php?prestito=" . $prestito . "&utente=" . $utente . "&errore=Tessera non appartenente all'utente!");
		$mysqli->close();
		redirect('./prestiti.php');
	}
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
		<title>Riconsegna</title>
	</head>
	<body>		
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<h2>Riconsegna Libro</h2>
			<div id="cerca">
			 <form method="post" action="riconsegna.php">
				<input type="number" name="tessera" placeholder="Scannerizza il codice a barre" autofocus required>
				<input type="hidden" name="prestito" value="<?php echo $_GET['prestito']; ?>">
				<input type="hidden" name="utente" value="<?php echo $_GET['utente']; ?>">
				<input type="submit" name="riconsegna" value="Riconsegna">
			</form>
			</div>
			<?php
				if (isset($_GET['errore'])){
					echo '<div id="errore">';
					echo '<span>' . $_GET['errore'] . '</span>';
					echo '</div>';
				} 
			?>
		</section>
	</body>
</html>