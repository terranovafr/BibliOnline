<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
	
	if(!($_SESSION['modalita'] == 'B'))
		redirect('./home.php');
	
	if(isset($_POST['elimina'])){
		//CONNESSIONE
		require_once "./DBConnessione.php";
		
		//RECUPERO DATI
		$copia = $mysqli->real_escape_string($_POST['copia']);
		$codiceisbn = $mysqli->real_escape_string($_POST['codiceisbn']);
		
		//ELIMINO PRESTITI RELATIVI AL LIBRO
		$query = "DELETE FROM PRESTITO WHERE COPIA='" . $copia . "' AND LIBRO='" . $codiceisbn . "' AND BIBLIOTECA ='" . $_SESSION['ID'] . "';";
		$result = $mysqli->query($query);
		
		
		//ELIMINO COPIA
		$query = "DELETE FROM COPIA WHERE ID='" . $copia . "' AND LIBRO='" . $codiceisbn . "' AND BIBLIOTECA ='" . $_SESSION['ID'] . "';";
		$result = $mysqli->query($query);
		$mysqli->close();
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
		<title>Elimina Copia</title>
	</head>
	<body>	
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<h2>Elimina Copia</h2>
			<nav class="smallmenu">	
				<a href="aggiungilibro.php"><img src="../img/icons/addbook.png" alt="">Aggiungi un nuovo libro</a>
				<a href="aggiungicopia.php"><img src="../img/icons/addcopy.png" alt="">Aggiungi una nuova copia</a>
				<a href="eliminacopia.php" class="selected"><img src="../img/icons/delete.png" alt="">Rimuovi una copia</a>
			</nav>
			<form method="post" name="eliminacopia.php">
			  <div class="compila">
				<div><label>Numero Copia: </label><input type="number" name="copia" placeholder="Numero Copia"></div>
				<div><label>Codice ISBN: </label><input type="number" min="1000000000000" max="9999999999999" name="codiceisbn" placeholder="Scannerizza il Codice ISBN"></div>
			  </div>
			  <div><input type="submit" name="elimina" value="Elimina Copia"></div>
			</form>
		</section>
	</body>
</html>