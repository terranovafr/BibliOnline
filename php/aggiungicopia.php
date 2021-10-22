<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
	
	// ACCESSO SOLO BIBLIOTECHE
	if(!($_SESSION['modalita'] == 'B'))
		redirect('./home.php');
	
	if(isset($_POST['aggiungi'])){
		//CONNESSIONE
		require_once "./DBConnessione.php";
		
		//RECUPERO DATI
		$copia = $mysqli->real_escape_string($_POST['copia']);
		$codiceisbn = $mysqli->real_escape_string($_POST['codiceisbn']);
		
		//CONTROLLO LIBRO
		$query = "SELECT *
				  FROM LIBRO L
				  WHERE L.CODICEISBN='" . $codiceisbn . "';";
		$result = $mysqli->query($query);
		$numRow = mysqli_num_rows($result);
		if (!$numRow)
			redirect('./aggiungicopia.php?errore=' . "Devi prima inserire le informazioni sul libro");
			
		//CONTROLLO COPIA
		$query = "SELECT *
				  FROM COPIA C
				  WHERE C.LIBRO='" . $codiceisbn . "' AND C.ID ='" . $copia . "' AND C.BIBLIOTECA='" . $_SESSION['ID'] . "';";
		$result = $mysqli->query($query);
		$numRow = mysqli_num_rows($result);
		if ($numRow)
			redirect('./aggiungicopia.php?errore=' . "Questo libro ha gia' la copia numero ". $copia . "!");
		
		//INSERISCO COPIA
		$query = "INSERT INTO COPIA VALUES('" . $copia . "','" . $codiceisbn . "','" . $_SESSION['ID'] .  "','" . DATE("Y-m-d") . "');";
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
		<title>Aggiungi Copia - BibliOnline</title>
	</head>
	<body>	
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<h2>Aggungi Copia</h2>
			<nav class="smallmenu">	
				<a href="aggiungilibro.php"><img src="../img/icons/addbook.png" alt="">Aggiungi un nuovo libro</a>
				<a href="aggiungicopia.php" class="selected"><img src="../img/icons/addcopy.png" alt="">Aggiungi una nuova copia</a>
				<a href="eliminacopia.php"><img src="../img/icons/delete.png" alt="">Rimuovi una copia</a>
			</nav>
			<form method="post" action="aggiungicopia.php" name="aggiungi">
			  <div class="compila">
				<div><label>Numero Copia: </label><input type="number" name="copia" placeholder="Numero Copia"></div>
				<div><label>Codice ISBN: </label><input type="number"  min="1000000000000" max="9999999999999" name="codiceisbn" placeholder="Scannerizza il Codice ISBN"></div>
			  </div>
				<div><input type="submit" name="aggiungi" value="Aggiungi Copia"></div>
			</form>
			<?php
				if (isset($_GET['errore'])){
					echo '<div id="errore">';
					echo '<span>' . $_GET['errore'] . '</span>';
					echo '</div>';
				} ?>
		</section>
	</body>
</html>