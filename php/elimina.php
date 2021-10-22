<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
	
	if(isset($_GET['prestito'])){
		// CONNESSIONE
		require_once "./DBConnessione.php";
		
		
		$prestito = $mysqli->real_escape_string($_GET['prestito']);
		
		// ELIMINO PRESTITO
		$query = "DELETE FROM PRESTITO WHERE ID='" . $prestito . "';";
		$result = $mysqli->query($query);
		$mysqli->close();
		
		redirect('./prestiti.php');
	}
?>
