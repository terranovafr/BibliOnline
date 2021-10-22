<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
		
	if(!($_SESSION['modalita'] == 'U'))
		redirect('./home.php');		
	
	if(isset($_POST['seleziona'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
		
		// RECUPERO DATI
		$biblioteca = $mysqli->real_escape_string($_POST['biblioteca']);
		$utente = $_SESSION['ID'];
			
		// CONTROLLO SE NON VI SIA GIA' QUALCHE RICHIESTA
		$query = "SELECT *
				  FROM CONSEGNA C
				  WHERE TESSERA ='" . $utente ."';";
		$result = $mysqli->query($query);
		$num = mysql_num_rows($result);
		if($num)
			redirect('./home.php');
		
		// INSERIMENTO TESSERA
		$query = "INSERT INTO TESSERA(UTENTE) VALUES('" . $utente . "');";
		$result = $mysqli->query($query);
		
		// INSERIMENTO DATI CONSEGNA TESSERA
		$query = "INSERT INTO CONSEGNA(BIBLIOTECA,TESSERA,DATAMAXRITIRO) VALUES('" . $biblioteca . "','" . $utente . "','" .  date("Y-m-d", strtotime("+30 days")) . "');";
		$result = $mysqli->query($query);
		
		redirect('./home.php');
	}
?>
<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8"> 
    	<meta name = "author" content = "Terranova Franco">
    	<meta name = "keywords" content = "BibliOnline">
   	 	<link rel="icon" href="../img/icon.ico" />
		<link rel="stylesheet" href="../css/BibliOnline_login_registrazione.css" type="text/css" media="screen">
		<title>Tessera</title>
	</head>
	<body>
		<section class="bookopen" id="sceglitessera">
			<div id="logo">
				<a href="../index.php">
					<img src="../img/logo.png" alt="logo">
				</a>
			</div>
			<h3>Tessera</h3>
			<form name="sceglibiblioteca" action="ritiratessera.php" method="post">
				<div class="left">
					<label>Scegli in quale delle biblioteche della tua citta' ritirare la tessera:</label>
						<?php
							require_once "./DBConnessione.php";
							if(!isset($_POST['seleziona'])){
								$query = "SELECT B.CODICE, B.NOME, B.INDIRIZZO
											FROM BIBLIOTECA B
											WHERE B.CITTA='" . $_SESSION['citta'] . "';";
								$result = $mysqli->query($query);
							
								while($row = $result->fetch_assoc())
									echo "<div class='biblioteca'><input type='radio' name='biblioteca' value='" . $row['CODICE'] . "' required><div>" . $row['NOME'] . " - " . $row['INDIRIZZO'] ."</div></div>" ;
							}
						?>
					<input type="submit" value="Seleziona Biblioteca" name="seleziona">
					<span>Avrai tempo un mese per provvedere a recuperarla!</span>
				</div>
				<div class="right">
					<div>
						<label>Retro</label>
						<img id="retrotessera" src="../img/uploads/tessere/<?php echo $_SESSION['username']; ?>.png" alt="retrotessera">
					</div>
					<div>
						<label>Fronte</label>
						<img id="frontetessera" src="../img/frontetessera.jpg" alt="frontetessera">
					</div>
				</div>
			</form>	
		</section>
	</body>
</html>
