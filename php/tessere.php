<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
	
	if(!($_SESSION['modalita']=='B'))
		redirect('./home.php');
	
	if(!isset($_GET['tipo']))
		$_GET['tipo'] = 'daconsegnare';
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
		<title>Tessere - BibliOnline</title>
	</head>
	<body>		
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<?php
				if($_GET['tipo'] == 'daconsegnare')
						echo "<h2>Tessere da consegnare</h2>";
				elseif($_GET['tipo'] == 'consegnate')
						echo "<h2>Tessere consegnate</h2>";
			?>
			<nav class="smallmenu">	
				<a <?php if($_GET['tipo']=='daconsegnare')
						echo "class='selected'"; ?> href="tessere.php?tipo=daconsegnare"><img src='../img/icons/loading.png' alt='da consegnare'>Tessere da consegnare</a>
				<a <?php if($_GET['tipo']=='consegnate')
						echo "class='selected'"; ?> href="tessere.php?tipo=consegnate"><img src='../img/icons/tick.png' alt='consegnate'>Tessere consegnate</a>
			</nav>
			<?php
				require_once "DBFunctions.php";
				if($_GET['tipo']=='daconsegnare'){
					echo "<table id='tabellatessere'>
						<tr>
							<th>Utente</th>
							<th>Tessera</th>
							<th>Data Max Ritiro</th>
							<th>Consegna</th>
						</tr>";
					echo TessereDaConsegnare() . "</table>";
				} elseif($_GET['tipo']=='consegnate'){
					echo "<table id='tabellatessere'>
							<tr>
								<th>Utente</th>
								<th>Tessera</th>
								<th>Data Ritiro</th>
							</tr>";
					echo TessereConsegnate() . "</table>";
				}
			?>
		</section>
	</body>
</html>