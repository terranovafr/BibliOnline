<?php
	session_start();
    include "./util.php";

    if (Accesso())
		redirect('./home.php');
?>
<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8"> 
    	<meta name = "author" content = "Terranova Franco">
    	<meta name = "keywords" content = "BibliOnline">
   	 	<link rel="icon" href="../img/icon.ico" />
		<link rel="stylesheet" href="../css/BibliOnline_login_registrazione.css" type="text/css" media="screen">
		<title>Registrazione</title>
	</head>
	<body>
		<section class="bookopen">
			<div id="logo">
				<a href="../index.php">
					<img src="../img/logo.png" alt="logo">
				</a>
			</div>
			<h3>Registrazione</h3>
			<div class="icon">
				<a href="registrazioneutente.php">
					<img src="../img/user.png" alt="Utente">
					Registrazione Utente
				</a>
			</div>
			<div class="icon">
				<a href="registrazionebiblioteca.php">
					<img src="../img/library.png" alt="Biblioteca">
					Registrazione Biblioteca
				</a>
			</div>
		</section>
	</body>
</html>
