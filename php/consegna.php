<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
	
	if(!($_SESSION['modalita'] == 'B'))
		redirect('./home.php');
	
	if(isset($_POST['consegna'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
		
		// RECUPERO PARAMETRI
		$utente = $mysqli->real_escape_string($_POST['utente']);
		$prestito = $mysqli->real_escape_string($_POST['prestito']);
		$codicebarre = $mysqli->real_escape_string($_POST['codicebarre']);
		
		if(isset($prestito) && $prestito != ''){
			//SE CONSEGNA LIBRO
			$query = "SELECT *
					  FROM TESSERA
					  WHERE CODICEBARRE ='" . $codicebarre . "' AND UTENTE='" . $utente . "';";
			$result = $mysqli->query($query);
			$numRow = mysqli_num_rows($result);
			
			if ($numRow == 1){
				// SE TESSERA CORRETTA AGGIORNO PRESTITO CON CONSEGNA
				$query = "UPDATE PRESTITO
					  SET DATARITIRO='". date("Y-m-d") ."',DATARICONSEGNAPREVISTA='" . date("Y-m-d", strtotime("+90 days")) . "'
					  WHERE ID='" . $prestito . "';";
				$result = $mysqli->query($query);
			} else redirect("./consegna.php?prestito=" . $prestito . "&utente=" . $utente . "&errore=Tessera non appartenente all'utente!");
			$mysqli->close();
			redirect('./prestiti.php');
		} else {
			// SE CONSEGNA TESSERA
			if($codicebarre== '')
				redirect('./tessere.php');
				
			// CONTROLLO SE GIA' CONSEGNATA
			$query = "SELECT CODICEBARRE FROM TESSERA WHERE UTENTE='" . $utente . "';";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			if($row['CODICEBARRE'] != NULL)
				redirect('./tessere.php');
			
			$query = "SELECT * FROM TESSERA WHERE CODICEBARRE='" . $codicebarre . "';";
			$result = $mysqli->query($query);
			if(!mysqli_num_rows($result)){
				//AGGIORNO EVENTUALMENTE
				$query = "UPDATE TESSERA SET CODICEBARRE='" . $codicebarre . "' WHERE UTENTE='" . $utente . "';";
				$result = $mysqli->query($query);
		
				$query = "UPDATE CONSEGNA SET DATARITIRO='" . date("Y-m-d") . "' WHERE TESSERA='" . $utente . "';";
				$result = $mysqli->query($query);
			}
			$mysqli->close();
			redirect('./tessere.php');
		}
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
		<title>Consegna - BibliOnline</title>
	</head>
	<body>		
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<h2>Consegna</h2>
			<div id="cerca">
			<form method="post" action="consegna.php">
				<input type="number" name="codicebarre" placeholder="Scannerizza il codice a barre" required autofocus>
				<input type="hidden" name="prestito" value="<?php if(isset($_GET['prestito']))
																	echo $_GET['prestito']; ?>">
				<input type="hidden" name="utente" value="<?php if(isset($_GET['utente']))
																	echo $_GET['utente']; ?>">
				<input type="submit" name="consegna" value="Consegna">
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