<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
		
	// ACCESSO LIMITATO AI SOLI UTENTI
	if(!($_SESSION['modalita'] == 'U'))
		redirect('./home.php');
	
	if(isset($_POST['prenota'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
		
		// RECUPERO PARAMETRI
		$libro = $mysqli->real_escape_string($_POST['codiceisbn']);
		$utente = $_SESSION['ID'];
		$copia = $mysqli->real_escape_string($_POST['copia']);
		$biblioteca = $mysqli->real_escape_string($_POST['biblioteca']);
		
		$query = "SELECT *
				  FROM PRESTITO 
				  WHERE UTENTE = '" . $utente . "' AND LIBRO = '" . $libro . "' AND DATARICONSEGNAEFFETTIVA IS NULL;";
		$result = $mysqli->query($query);
		$num = mysqli_num_rows($result);
		if($num)
			redirect('./prestiti.php?errore=' . "Hai gia' prenotato una copia di questo libro! Termina prima il tuo prestito!");
			
		
		$query = "INSERT INTO PRESTITO(Utente,Copia,Libro,Biblioteca,DataMaxRitiro,DataRichiesta) VALUES('" . $utente . "','" . $copia . "','" . $libro . "','" . $biblioteca . "','" . date("Y-m-d", strtotime("+30 days")) . "','" . date("Y-m-d") . "');";
		$result = $mysqli->query($query);
		
		
		
		$mysqli->close();
		
		redirect('./prestiti.php');
	}
?>
