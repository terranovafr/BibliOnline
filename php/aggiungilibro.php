<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
	
	// ACCESSO SOLO ALLE BIBLIOTECHE
	if(!($_SESSION['modalita'] == 'B'))
		redirect('./home.php');
	
	if(isset($_POST['inserisci'])){
		//CONNESSIONE
		require_once "./DBConnessione.php";
	
		//RECUPERO DATI
		$codiceisbn = $mysqli->real_escape_string($_POST['codiceisbn']);
		$titolo =  $mysqli->real_escape_string($_POST['titolo']);
		$nomeautore = $mysqli->real_escape_string($_POST['nomeautore']);
		$cognomeautore = $mysqli->real_escape_string($_POST['cognomeautore']);
		$casaeditrice = $mysqli->real_escape_string($_POST['casaeditrice']);
		$genere = $mysqli->real_escape_string($_POST['genere']);
		$annoproduzione = $mysqli->real_escape_string($_POST['anno']);
		$lingua = $mysqli->real_escape_string($_POST['lingua']);
		$numeropagine = $mysqli->real_escape_string($_POST['numeropagine']);
		
		// CONTROLLO EVENTUALE ESISTENZA LIBRO
		$query = "SELECT *
				  FROM LIBRO L
				  WHERE L.CODICEISBN='" . $codiceisbn . "';";
		$result = $mysqli->query($query);
		$numRow = mysqli_num_rows($result);
		if ($numRow)
			redirect('./aggiungilibro.php?errore=' . "Libro gia' esistente nel database, basta caricare una copia!" );
		
		if(isset($_FILES["copertina"]) && isset($_FILES["dorso"])){
			if($_FILES["copertina"]["name"] != "" && $_FILES["dorso"]["name"] != ""){
				$dir = "../img/uploads/books/";
				//CONTROLLO COPERTINA
				$nomeimmagine = basename($_FILES["copertina"]["name"]);
				$file = $dir . $nomeimmagine;
				$tipo = strtolower(pathinfo($file,PATHINFO_EXTENSION));
				$file = $dir . $codiceisbn . "." . $tipo;
	
				if($tipo != "jpg" && $tipo != "png" && $tipo != "jpeg" && $tipo != "gif" ) 
					redirect('./aggiungilibro.php?errore=' . "Quella che hai caricato non e' un'immagine!" );
		
				if ($_FILES["copertina"]["size"] > 60000000)
					redirect('./aggiungilibro.php?errore=' . "File di dimensioni elevate! MAX 60MB!" );
		
				if (!move_uploaded_file($_FILES["copertina"]["tmp_name"], $file)) 
					redirect('./aggiungilibro.php?errore=' . "Problema nel caricamento dell'immagine, riprova caricandone un'altra!" );
					
				//CONTROLLO DORSO
				$nomeimmagine = basename($_FILES["dorso"]["name"]);
				$file = $dir . $nomeimmagine;
				$tipo = strtolower(pathinfo($file,PATHINFO_EXTENSION));
				$file = $dir . "dorso" . $codiceisbn . "." . $tipo;
	
				if($tipo != "jpg" && $tipo != "png" && $tipo != "jpeg" && $tipo != "gif" ) 
					redirect('./aggiungilibro.php?errore=' . "Quella che hai caricato non e' un'immagine!" );
		
				if ($_FILES["dorso"]["size"] > 60000000)
					redirect('./aggiungilibro.php?errore=' . "File di dimensioni elevate! MAX 60MB!" );
		
				if (!move_uploaded_file($_FILES["dorso"]["tmp_name"], $file)) 
					redirect('./aggiungilibro.php?errore=' . "Problema nel caricamento dell'immagine, riprova caricandone un'altra!" );
			}
		}
		
		// CONTROLLO AUTORE
		$query = "SELECT *
				  FROM AUTORE A
				  WHERE A.NOME='" . $nomeautore . "' AND A.COGNOME='" . $cognomeautore . "';";
		$result = $mysqli->query($query);
		$numRow = mysqli_num_rows($result);
		if (!$numRow){	
			// INSERIMENTO AUTORE
			$query = "INSERT INTO AUTORE(NOME,COGNOME) VALUES('" . $nomeautore . "','" . $cognomeautore . "');";
			$result = $mysqli->query($query);
		}
		
		// INSERIMENTO INFO LIBRO
		$query = "INSERT INTO LIBRO VALUES('" . $codiceisbn . "','" . $titolo . "','" . $casaeditrice . "','" . $genere . "','" . $annoproduzione . "','" . $lingua . "','" . $numeropagine . "');";
		$result = $mysqli->query($query);
		
		// RECUPERO CODICE AUTORE
		$query = "SELECT A.CODICE
				  FROM AUTORE A
				  WHERE A.NOME='" . $nomeautore . "' AND A.COGNOME='" . $cognomeautore . "';";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		
		// INSERIMENTO PUBBLICAZIONE
		$query = "INSERT INTO PUBBLICAZIONE VALUES('" . $row['CODICE'] . "','" . $codiceisbn . "');";
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
		<script src="../js/validation.js"></script>
		<title>Aggiungi Libro - BibliOnline</title>
	</head>
	<body>	
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<h2>Aggiungi Libro</h2>
			<nav class="smallmenu">	
				<a href="aggiungilibro.php" class="selected"><img src="../img/icons/addbook.png" alt="">Aggiungi un nuovo libro</a>
				<a href="aggiungicopia.php"><img src="../img/icons/addcopy.png" alt="">Aggiungi una nuova copia</a>
				<a href="eliminacopia.php"><img src="../img/icons/delete.png" alt="">Rimuovi una copia</a>
			</nav>
			<form method="post" name="aggiungi" action="aggiungilibro.php" enctype="multipart/form-data">
			  <div class="compila">
				<div><label>Titolo: </label><input type="text" name="titolo" id="titolo" placeholder="Titolo" required></div>
				<div><label>Nome Autore: </label><input type="text" name="nomeautore" id="nome" placeholder="Nome Autore" required></div>
				<div><label>Cognome Autore: </label><input type="text" name="cognomeautore" id="cognome" placeholder="Cognome Autore" required></div>
				<div><label>Casa Editrice: </label><input type="text" name="casaeditrice" id="casaeditrice" placeholder="Casa Editrice"></div>
				<div><label>Genere: </label><input type="text" name="genere" id="genere" placeholder="Genere"></div>
				<div><label>Anno Produzione: </label><input type="number" min="1000" max="2019" name="anno" placeholder="AnnoProduzione"></div>
				<div><label>Lingua: </label><input type="text" name="lingua" id="lingua" placeholder="Lingua"></div>
				<div><label>Numero Pagine: </label><input type="number" name="numeropagine" placeholder="Numero Pagine"></div>
				<div class="immagine"><label>Immagine Copertina: </label><div><input type="file" name="copertina" required></div>
				<label>Immagine Dorso: </label><div><input type="file" name="dorso" required></div></div>
				<div><label>Codice ISBN: </label><input type="number" min="1000000000000" max="9999999999999" name="codiceisbn" placeholder="Scannerizza il Codice ISBN" required></div>
			  </div>
			<div><input type="submit" name="inserisci" value="Inserisci Libro" onclick="return validaLibro();"></div>
				<?php
					if (isset($_GET['errore'])){
							echo '<div id="errore">';
							echo '<span>' . $_GET['errore'] . '</span>';
							echo '</div>';
					} ?>
			</form>
		</section>
	</body>
</html>