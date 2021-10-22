<?php
	session_start();
    include "./util.php";

    if (Accesso())
		redirect('./home.php');
	
	if(isset($_POST['accedi'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
		
		// RECUPERO DATI
		$username = $mysqli->real_escape_string($_POST['nomeutente']);
		$password = $mysqli->real_escape_string($_POST['password']);
		
		// CONTROLLO CREDENZIALI
		$query = "SELECT * 
				  FROM ACCOUNT 
				  WHERE NOMEUTENTE='" . $username . "' AND PASSWORD='" . $password . "'";

		$result = $mysqli->query($query);
		
		$num = mysqli_num_rows($result);
			
		// SE ACCOUNT ESISTENTE
		if($num == 1){
		
			$query = "SELECT * 
					  FROM UTENTE U
					  WHERE U.ACCOUNT='" . $username . "'";

			$result = $mysqli->query($query);
		
			$num = mysqli_num_rows($result);
		
			if ($num == 1){
				// RECUPERO INFORMAZIONI UTENTE
				$modalita = "U";
				$query = "SELECT ID,CITTA
						  FROM UTENTE 
						  WHERE ACCOUNT = '" . $username . "';";
				$result = $mysqli->query($query);
				$row = $result->fetch_assoc();
				$_SESSION['username'] = nospecialchars($username);
				$_SESSION['ID'] = nospecialchars($row['ID']);
				$_SESSION['citta'] = nospecialchars($row['CITTA']);
				$_SESSION['modalita'] = nospecialchars($modalita);
				redirect('./home.php');
			}
			
			$query = "SELECT * 
					  FROM BIBLIOTECA B
					  WHERE B.ACCOUNT='" . $username . "'";

			$result = $mysqli->query($query);
		
			$num = mysqli_num_rows($result);
			
			if ($num == 1){
				//RECUPERO INFORMAZIONI BIBLIOTECA
				$modalita = "B";
				$query = "SELECT CODICE,CITTA, VALIDA
						  FROM BIBLIOTECA 
						  WHERE ACCOUNT = '" . $username . "';";
				$result = $mysqli->query($query);
				$row = $result->fetch_assoc();
				
				// SE NON ANCORA VALIDATA
				if($modalita == "B" && $row['VALIDA'] == FALSE)
					redirect('./../index.php?errore=' . "La tua biblioteca non e' stata ancora validata!" );
					
				$_SESSION['username'] = nospecialchars($username);
				$_SESSION['ID'] = nospecialchars($row['CODICE']);
				$_SESSION['citta'] = nospecialchars($row['CITTA']);
				$_SESSION['modalita'] = nospecialchars($modalita);
				redirect('./home.php');
			}
			
			$query = "SELECT * 
					  FROM AMMINISTRATORE A
					  WHERE A.ACCOUNT='" . $username . "'";
			$result = $mysqli->query($query);
			$num = mysqli_num_rows($result);
			if ($num == 1){
			// SE AMMINISTRATORE
				$_SESSION['modalita'] = 'A';
				redirect('./confermabiblioteca.php');
			}
		} else redirect('./../index.php?errore=' . "Nome Utente o Password Errati!" );
	}
?>