<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
		
	if(isset($_POST['cambia'])){
		require_once "./DBConnessione.php";
		
		// RECUPERO DATI
		$nome = $mysqli->real_escape_string($_POST['nome']);
		$citta = $mysqli->real_escape_string($_POST['citta']);
		$telefono = $mysqli->real_escape_string($_POST['telefono']);
		$username = $mysqli->real_escape_string($_POST['username']);
		$password = $mysqli->real_escape_string($_POST['password']);
				
		// CONTROLLO EVENTUALE ESISTENZA USERNAME MODIFICATO
		if($username != $_SESSION['username']){ 
			$query = "SELECT *
					FROM ACCOUNT
					WHERE NOMEUTENTE='" . $username . "';";
			$result = $mysqli->query($query);
			$num = mysqli_num_rows($result);
			if($num)
				redirect('./impostazioni.php?errore=' . "Username gia' presente! Inseriscine un altro!" );
		}
		
		// IMMAGINE DI PROFILO
		if(isset($_FILES["immagine"])){
			if($_FILES["immagine"]["name"] != ""){
				$dir = "../img/uploads/profile/";
				$nomeimmagine = basename($_FILES["immagine"]["name"]);
				$file = $dir . $nomeimmagine;
				$tipo = strtolower(pathinfo($file,PATHINFO_EXTENSION));
				$file = $dir . $username . "." . $tipo;
				
				// ELIMINO VECCHIA FOTO
				if(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".png"))
					unlink($dir . $_SESSION['username'] . ".png");
				elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".jpg"))
					unlink($dir . $_SESSION['username'] . ".jpg");
				elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".gif"))
					unlink($dir . $_SESSION['username'] . ".gif");
				elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".jpeg"))
					unlink($dir . $_SESSION['username'] . ".jpeg");
				
				if($tipo != "jpg" && $tipo != "png" && $tipo != "jpeg" && $tipo != "gif" ) 
					redirect('./impostazioni.php?errore=' . "Quella che hai caricato non e' un'immagine!" );
		
				if ($_FILES["immagine"]["size"] > 60000000)
					redirect('./impostazioni.php?errore=' . "File di dimensioni elevate! MAX 60MB!" );
		
				if (!move_uploaded_file($_FILES["immagine"]["tmp_name"], $file)) 
					redirect('./impostazioni.php?errore=' . "Problema nel caricamento dell'immagine, riprova caricandone un'altra!" );
			}
		}
		
		if($_SESSION['modalita'] == 'U'){
			// RECUPERO ALTRI DATI SE UTENTE
			$cognome = $mysqli->real_escape_string($_POST['cognome']);
			$datanascita = $mysqli->real_escape_string($_POST['datanascita']);	
			// AGGIORNAMENTO
			$query = "UPDATE UTENTE SET NOME='" . $nome . "',COGNOME='" . $cognome . "',DATANASCITA='" . $datanascita . "',CITTA='" . $citta . "'
					 ,TELEFONO ='" . $telefono . "',ACCOUNT='" . $username . "' WHERE ID='" . $_SESSION['ID'] . "'";
		} else {
			// RECUPERO ALTRI DATI SE BIBLIOTECA
			$indirizzo = $mysqli->real_escape_string($_POST['indirizzo']);
			// AGGIORNAMENTO
			$query = "UPDATE BIBLIOTECA SET NOME='" . $nome . "', INDIRIZZO='" . $indirizzo . "', CITTA='" . $citta . "', TELEFONO ='" . $telefono . "', ACCOUNT='" . $username ."' WHERE CODICE=" . $_SESSION['ID'] . ";";
		}
		$result = $mysqli->query($query);
		
		//RINOMINO FOTO
		$dir = "../img/uploads/profile/";
		if(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".png"))
					rename($dir . $_SESSION['username'] . ".png", $dir . $username . ".png");
		elseif (file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".jpg"))
					rename($dir . $_SESSION['username'] . ".jpg", $dir . $username . ".png");
		elseif (file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".gif"))
					rename($dir . $_SESSION['username'] . ".gif",$dir . $username . ".png");
		elseif (file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".jpeg"))
					rename($dir . $_SESSION['username'] . ".jpeg",$dir . $username . ".png");
		
		//RINOMINO TESSERA
		if($_SESSION['modalita'] == 'U'){
			$dir = "../img/uploads/tessere/";
			rename($dir . $_SESSION['username'] . ".png", $dir . $username . ".png");
		}
		
		// AGGIORNAMENTO ACCOUNT
		$query = "UPDATE ACCOUNT SET NOMEUTENTE='" . $username . "', PASSWORD='" . $password . "' WHERE NOMEUTENTE='" . $_SESSION['username'] . "'";
		$result = $mysqli->query($query);
		
		$_SESSION['username'] = $username;
		$_SESSION['citta'] = $citta;
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
		<title>Impostazioni</title>
	</head>
	<body>		
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<h2>Impostazioni</h2>
			<?php
				
			?>
			<form name="settings" method="post" action="impostazioni.php" enctype="multipart/form-data">
					<?php 
						echo "<div><img src='../img/uploads/profile/";
						if(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".png"))
							echo nospecialchars($_SESSION['username']) . ".png";
						elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".jpg"))
							echo nospecialchars($_SESSION['username']) . ".jpg";
						elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".gif"))
							echo nospecialchars($_SESSION['username']) . ".gif";
						elseif(file_exists("../img/uploads/profile/" . $_SESSION['username'] . ".jpeg"))
							echo nospecialchars($_SESSION['username']) . ".jpeg";
						else
							echo "default.png";
						echo "' id='userphoto' alt='immagine'></div>";
					?>					
					<div id="settings">
					<?php
						require_once "DBFunctions.php";
						Settings();
					?>			
					</div>
					<div><label>Carica una nuova immagine: </label></div>
					<div><input type="file" name="immagine"></div>
					<input type="submit" name="cambia" value="Cambia Impostazioni" onclick="return validaImpostazioni('<?php echo $_SESSION['modalita'];?>')">
				</form>
				<?php
					if (isset($_GET['errore'])){
							echo '<div id="errore">';
							echo '<span>' . $_GET['errore'] . '</span>';
							echo '</div>';
					} 
				?>
			</div>
		</section>
	</body>
</html>