<?php
	session_start();
    include "./util.php";

    if (Accesso())
		redirect('./home.php');
    
	if(isset($_POST['Registrati'])){
		// CONNESSIONE
		require_once "./DBconnessione.php";
		
		// RECUPERO DATI
		$username =  $mysqli->real_escape_string($_POST['username']);
		$password =  $mysqli->real_escape_string($_POST['password']);
		$nome =  $mysqli->real_escape_string($_POST['nome']);
		$indirizzo =  $mysqli->real_escape_string($_POST['indirizzo']);
		$citta =  $mysqli->real_escape_string($_POST['citta']);
		$telefono =  $mysqli->real_escape_string($_POST['telefono']);
		
		// CONTROLLO NOME UTENTE
		$query = "SELECT *
				  FROM ACCOUNT
				  WHERE NOMEUTENTE='" . $username . "'";
		$result = $mysqli->query($query);
		$numrow = mysqli_num_rows($result);
		if ($numrow)
			redirect('registrazionebiblioteca.php?errore=' . "Nome Utente gia' presente! Scegline un altro!" );	
		
		// IMMAGINE
		if(isset($_FILES["immagine"]["name"]) && $_FILES["immagine"]["name"] != ""){
			$dir = "../img/uploads/profile/";
			$nomeimmagine = basename($_FILES["immagine"]["name"]);
			$tipo = strtolower(pathinfo($nomeimmagine,PATHINFO_EXTENSION));
			$file = $dir . $username . "." . $tipo;
	
			if($tipo != "jpg" && $tipo != "png" && $tipo != "jpeg" && $tipo != "gif" ) 
				redirect('./registrazionebiblioteca.php?errore=' . "Quella che hai caricato non e' un'immagine!" );
		
			if ($_FILES["immagine"]["size"] > 60000000)
				redirect('./registrazionebiblioteca.php?errore=' . "File di dimensioni elevate! MAX 60MB!" );
		
			if (!move_uploaded_file($_FILES["immagine"]["tmp_name"], $file)) 
				redirect('./registrazionebiblioteca.php?errore=' . "Problema nel caricamento dell'immagine, riprova caricandone un'altra!" );
		}
		
		// INSERIMENTO
		$query = "INSERT INTO ACCOUNT(NOMEUTENTE, PASSWORD) VALUES('" . $username . "','" . $password . "');";
		$result = $mysqli->query($query);
		
		$query = "INSERT INTO BIBLIOTECA(NOME,CITTA,INDIRIZZO,TELEFONO,ACCOUNT,VALIDA) VALUES('" . $nome . "','" . $citta . "','" . $indirizzo . "','" . $telefono . "','" . $username . "',FALSE);";
		$result = $mysqli->query($query);
		
		$mysqli->close();
			
		redirect('./registrazionebiblioteca.php?conferma=YES');
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
		<title>Registrazione</title>
	</head>
	<body>
		<section class="bookopen">
			<div id="logo">
				<a href="../index.php">
					<img src="../img/logo.png" alt="logo">
				</a>
			</div>
			<h3>Registrazione Biblioteca</h3>
			<form name="registrazionebiblioteca" action="registrazionebiblioteca.php" method="post" enctype="multipart/form-data">
				<div class="left">
					<div>
						<label>Nome Biblioteca</label>
						<input type="text" placeholder="Nome" name="nome" id="Nome" required autofocus>
					</div>
					<div>
						<label>Citta'</label>
						<select name="citta" id="Citta" required>
								<option label=" " disabled selected value></option>
								<?php
									include "./cittaitaliane.php";
									foreach($cittaitaliane as $citta)
										echo "<option label='". $citta . "' value='" . $citta . "'>". $citta . "</option>";
								?>
							</select>
					</div>
					<div>
						<label>Indirizzo</label>
						<input type="text" placeholder="Indirizzo" name="indirizzo" id="Indirizzo" required>
					</div>
					<div>
						<label>Telefono</label>
						<input type="text" placeholder="Telefono" name="telefono" id="Telefono" required>
					</div>
					<div>
						<label>Nome Utente</label>
						<input type="text" placeholder="Username"id="username" name="username" required>
					</div>
				</div>
				<div class="right">
					<div>
						<label>Immagine di profilo</label>
						<input type="file" name="immagine">
					</div>
					<div>
						<label>Inserisci una Password :</label>
						<input type="password" placeholder="Password" id="Password" name="password" required>
					</div>	
					<div>
						<label>Conferma Password:</label>
						<input type="password" placeholder="Conferma Password" id="ConfermaPassword" name="password" required>
					</div>
					<input type="submit" value="Registrati" name="Registrati" onclick="return validaBiblioteca()">
					<?php
						if (isset($_GET['errore'])){
							echo '<div id="errore">';
							echo '<span>' . $_GET['errore'] . '</span>';
							echo '</div>';
							}
						elseif (isset($_GET['conferma']) && $_GET['conferma'] == 'YES'){
							echo "<div id='conferma'>";
							echo "<span>Attendi adesso la conferma da parte dell'amministratore! Prova ad accedere nelle prossime ore al servizio!</span>";
							echo "</div>";
							}	?>
				</div>
			</form>
		</section>
	</body>
</html>