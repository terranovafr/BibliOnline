<?php
	session_start();
    include "./util.php";

    if (!($_SESSION['modalita'] == 'A'))
		redirect('./../index.php');
	
	if(isset($_POST['conferma'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
		
		$biblioteche =  $_POST['biblioteca'];	
		foreach($biblioteche as $biblioteca) {
			$query = "UPDATE BIBLIOTECA SET VALIDA=1 WHERE CODICE='" . $biblioteca . "'";
			$result = $mysqli->query($query);
		}
	}
	if(isset($_POST['elimina'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
		
		$biblioteche =  $_POST['biblioteca'];	
		foreach($biblioteche as $biblioteca) {
			$query = "SELECT ACCOUNT FROM BIBLIOTECA WHERE CODICE='" . $biblioteca . "'";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			
			$query = "DELETE FROM BIBLIOTECA WHERE CODICE='" . $biblioteca . "'";
			$result = $mysqli->query($query);
			
			$query = "DELETE FROM ACCOUNT WHERE NOMEUTENTE='" . $row['ACCOUNT'] . "';";
			$result = $mysqli->query($query);
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
		<title>BibliOnline Admin</title>
	</head>
	<body>	
		<section class="white" id="admin">
			<h2>Conferma Biblioteche</h2>
			<form method="POST" action="confermabiblioteca.php" name="confermabiblioteca">
			<?php
				// CONNESSIONE
				require_once "./DBConnessione.php";
				
				//BIBLIOTECHE NON ANCORA VALIDATE
				$query = "SELECT *
						  FROM BIBLIOTECA
						  WHERE VALIDA=0";
				$result = $mysqli->query($query);
				if(mysqli_num_rows($result)){
					while($row = $result->fetch_assoc()){
						echo "<div><input type='checkbox' name='biblioteca[]' value='" . $row['Codice'] . "'>" . $row['Nome'] . ", " . $row['Indirizzo'] . ", " . $row['Citta'] . ", " . $row['Telefono'] ."</div>";
					}
					echo "<input type='submit' name='conferma' value='Conferma'>";
					echo "<input type='submit' name='elimina' value='Elimina'>";
				}
				else echo "<div id='empty'>Nessuna biblioteca da validare al momento!</div>"
			?>
			</form>
			<a href="./logout.php">Logout</a>
		</section>
	</body>
</html>