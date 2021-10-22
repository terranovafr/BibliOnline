<?php
	function Accesso(){		
		if(isset($_SESSION['username']))
			return true;
		else
			return false;
	}

	function redirect($page){
			header('Location: '. $page);
			exit;
	}

	function nospecialchars($stringa){
		$stringa = htmlspecialchars($stringa, ENT_HTML5, "UTF-8");
		if(!is_string($stringa))
			throw new RuntimeException("Stringa non valida!",-1);
		return $stringa;
	}
?>