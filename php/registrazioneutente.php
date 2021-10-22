<?php
	session_start();
    include "./util.php";

    if (Accesso())
		redirect('./home.php');

	if(isset($_POST['Registrati'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
	
		// RECUPERO DATI
		$username =  $mysqli->real_escape_string($_POST['username']);
		$password =  $mysqli->real_escape_string($_POST['password']);
		$nome = $mysqli->real_escape_string($_POST['nome']);
		$cognome = $mysqli->real_escape_string($_POST['cognome']);
		$datadinascita = $mysqli->real_escape_string($_POST['data']);
		$citta = $mysqli->real_escape_string($_POST['citta']);
		$telefono = $mysqli->real_escape_string($_POST['telefono']);
		
		// CONTROLLO NOME UTENTE
		$query = "SELECT *
				  FROM ACCOUNT
				  WHERE NOMEUTENTE='" . $username . "'";
		$result = $mysqli->query($query);
		$num = mysqli_num_rows($result);
		if ($num)
			redirect('./registrazioneutente.php?errore=' . "Nome Utente gia' presente! Scegline un altro!" );
		
		// IMMAGINE
		if(isset($_FILES["immagine"]["name"]) && $_FILES["immagine"]["name"] != ""){
			$dir = "../img/uploads/profile/";
			$nomeimmagine = basename($_FILES["immagine"]["name"]);
			$tipo = strtolower(pathinfo($nomeimmagine,PATHINFO_EXTENSION));
			$file = $dir . $username . "." . $tipo;
	
			if($tipo != "jpg" && $tipo != "png" && $tipo != "jpeg" && $tipo != "gif" ) 
					redirect('./registrazioneutente.php?errore=' . "Quella che hai caricato non e' un'immagine!" );
		
			if ($_FILES["immagine"]["size"] > 60000000)
				redirect('./registrazioneutente.php?errore=' . "File di dimensioni elevate! MAX 60MB!" );
		
			if (!move_uploaded_file($_FILES["immagine"]["tmp_name"], $file)) 
				redirect('./registrazioneutente.php?errore=' . "Problema nel caricamento dell'immagine, riprova caricandone un'altra!" );
		}
		
		// INSERIMENTO
		$query = "INSERT INTO ACCOUNT(NOMEUTENTE, PASSWORD) VALUES('" . $username . "','" . $password . "');";
		$result = $mysqli->query($query);
		
		$query = "INSERT INTO UTENTE(NOME,COGNOME,CITTA,DATANASCITA,TELEFONO,ACCOUNT) VALUES('" . $nome . "','" . $cognome . "','" . $citta . "','" . $datadinascita . "','" . $telefono . "','" . $username . "');";
		$result = $mysqli->query($query);
				
		// RECUPERO ID
		$query = "SELECT ID 
				  FROM UTENTE U 
					INNER JOIN 
					ACCOUNT A ON U.ACCOUNT = A.NOMEUTENTE 
				  WHERE A.NOMEUTENTE ='" . $username . "';";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
			
	
		$_SESSION['username'] = nospecialchars($username);
		$_SESSION['modalita'] = 'U';
		$_SESSION['ID'] = nospecialchars($row['ID']);
		$_SESSION['citta'] = nospecialchars($citta);

		// UPLOAD TESSERA

		$dir = '../img/uploads/tessere/'; 
		$img = $_POST['hidden'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = $dir . $username . ".png";
		$success = file_put_contents($file, $data);
			
		redirect('./ritiratessera.php');
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
		<script src="../js/validation.js"></script>
		<script src="../js/tessera.js"></script>
		<title>Registrazione Utente</title>
	</head>
	<body>
		<section class="bookopen">
			<div id="logo">
				<a href="../index.php">
					<img src="../img/logo.png" alt="logo">
				</a>
			</div>
			<h3>Registrazione Utente</h3>
			<form name="registrazioneutente" action="registrazioneutente.php" method="post" enctype="multipart/form-data">
				<div class="left">
					<div>
						<label>Nome</label>
						<input type="text" placeholder="Nome" name="nome" id="Nome" required autofocus>
					</div>
					<div>
						<label>Cognome</label>
						<input type="text" placeholder="Cognome" name="cognome" id="Cognome" required>
					</div>
					<div>
					<label>Citta'</label>
							<select name="citta" id="Citta" required>
								<option label=" " disabled selected value></option>
								<?php
									require_once "./DBConnessione.php";
									
									// CIITA' IN CUI VI E' ALMENO UNA BIBLIOTECA ISCRITTA AL SERVIZIO
									$query = "SELECT DISTINCT B.CITTA
											  FROM BIBLIOTECA B
											  WHERE B.VALIDA=TRUE;";
									$result = $mysqli->query($query);
									while($row = $result->fetch_assoc())
										echo "<option label='". nospecialchars($row['CITTA']) . "' value='" . nospecialchars($row['CITTA']) . "'>". nospecialchars($row['CITTA']) . "</option>";
								?>
							</select>
					</div>
					<div>
						<label>Data Di Nascita</label>
						<div><span class="format">Formato YYYY-MM-DD</span></div>
						<input type="text" id="DataNascita" name="data" placeholder="Data Di Nascita" required>
					</div>
					<div>
						<label>Telefono</label>
						<input type="text" placeholder="Telefono" name="telefono" id="Telefono" required>
					</div>
					<div>
						<label>Nome Utente</label>
						<input type="text" placeholder="Username" name="username" id="Username" required>
					</div>
				</div>
				<div class="right">
					<div>
						<label>Immagine di profilo</label>
						<input type="file" name="immagine" id="immagine">
					</div>
					<div>
						<label>Inserisci una Password :</label>
						<input type="password" placeholder="Password" name="password" id="Password" required>
					</div>	
					<div>
						<label>Conferma Password:</label>
						<input type="password" placeholder="Conferma Password" name="confermapassword" id="ConfermaPassword" required>
					</div>
					<div>
						<label>Tessera :</label>
						<div>
							<canvas id="canvas"></canvas>
						</div>
					</div>
					<input name="hidden" id="hidden" type="hidden">
					<input type="submit" value="Registrati" name="Registrati" id="button" onclick="return validaUtente();">
					<?php
						if (isset($_GET['errore'])){
							echo '<div id="errore">';
							echo '<span>' . nospecialchars($_GET['errore']) . '</span>';
							echo '</div>';
						} 
					?>
				</form>
			</div>	
		</section>
	</body>
</html>
