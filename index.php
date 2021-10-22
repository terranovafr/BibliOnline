<?php
	session_start();
	include "./php/util.php";

    if (Accesso())
		redirect('./php/home.php');
?>
<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8"> 
    	<meta name = "author" content = "Terranova Franco">
    	<meta name = "keywords" content = "BibliOnline">
   	 	<link rel="shortcut icon" type="image/x-icon" href="./img/icon.ico" />
		<link rel="stylesheet" href="./css/BibliOnline_login_registrazione.css" type="text/css" media="screen">
		<script src="js/validation.js"></script>
		<title>BibliOnline</title>
	</head>
	<body>
		<section id="login">
			<div id="logo">
				<img src="./img/logo.png" alt="logo">
			</div>
			<h3>Login</h3>
			<form name="login" action="./php/login.php" method="post">
				<div>
					<label>Nome Utente</label>
					<input type="text" placeholder="Nome Utente" name="nomeutente" id="username" required autofocus>
				</div>
				<div>
					<label>Password</label>
					<input type="password" placeholder="Password" name="password" id="password" required>
				</div>	
				<div>
					<input type="submit" name="accedi" value="Accedi" onclick="return validaLogin();">
				</div>
			</form>
			<form name="registrati" action="./php/registrazione.php" method="post">
				<label>oppure</label>
				<div>
					<input type="submit" value="Registrati">
				</div>
			</form>
			<?php
				if (isset($_GET['errore'])){
					echo '<div id="errore">';
					echo '<span>' . nospecialchars($_GET['errore']) . '</span>';
					echo '</div>';
				}
			?>
			<footer>
				<div>
					<a href="./html/manuale.html" target="_blank">Manuale Utente</a>
				</div>
				<figure>
					Realizzato da Terranova Franco
				</figure>
			</footer>
		</section>
	</body>
</html>