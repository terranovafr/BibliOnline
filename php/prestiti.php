<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
	
	if(!isset($_GET['tipo']))
	    $_GET['tipo'] = 'incorso';
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
		<title>Prestiti</title>
	</head>
	<body>		
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<?php
				if($_GET['tipo'] == 'incorso')
						echo "<h2>Prestiti In Corso</h2>";
				elseif($_GET['tipo'] == 'terminati')
						echo "<h2>Prestiti Terminati</h2>";
			?>
			<nav class="smallmenu">	
				<a <?php if($_GET['tipo']=='incorso')
						echo "class='selected'"; ?> href="prestiti.php?tipo=incorso"><img src='../img/icons/loading.png' alt='in corso'>Prestiti in corso</a>
				<a <?php if($_GET['tipo']=='terminati')
						echo "class='selected'"; ?> href="prestiti.php?tipo=terminati"><img src='../img/icons/tick.png' alt='terminati'>Prestiti terminati</a>
			</nav>
			<?php
				require_once "DBFunctions.php";
				if (isset($_GET['errore'])){
						echo '<div id="errore">';
						echo '<span>' . $_GET['errore'] . '</span>';
						echo '</div>';
				}
				if($_GET['tipo'] == 'incorso'){
					if($_SESSION['modalita'] == 'U'){
					echo "<table>
							<tr>
								<th>Libro</th>
								<th>Biblioteca</th>
								<th>Data Richiesta</th>
								<th>Data Max Ritiro</th>
								<th>Data Ritiro</th>
								<th>Data Riconsegna Prevista</th>
								<th>Data Riconsegna Effettiva</th>
								<th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
							</tr>";
						PrestitiUtenteInCorso();
						echo "</table>";
						} elseif($_SESSION['modalita'] == 'B') {
						echo "<table>
								<tr>
								<th>Libro</th>
								<th>Copia</th>
								<th>Utente</th>
								<th>Data Richiesta</th>
								<th>Data Max Ritiro</th>
								<th>Data Ritiro</th>
								<th>Data Riconsegna Prevista</th>
								<th>Data Riconsegna Effettiva</th>
								<th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
								<th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
							</tr>";
						PrestitiBibliotecaInCorso();
						}
					} elseif($_GET['tipo'] == 'terminati'){
						if($_SESSION['modalita'] == 'U'){
						echo "<table>
							<tr>
								<th>Libro</th>
								<th>Biblioteca</th>
								<th>Data Richiesta</th>
								<th>Data Max Ritiro</th>
								<th>Data Ritiro</th>
								<th>Data Riconsegna Prevista</th>
								<th>Data Riconsegna Effettiva</th>
							</tr>";
						PrestitiUtenteTerminati();
						echo "</table>";
						} elseif($_SESSION['modalita'] == 'B') {
						echo "<table class='infotabella'>
								<tr>
								<th>Libro</th>
								<th>Copia</th>
								<th>Utente</th>
								<th>Data Max Ritiro</th>
								<th>Data Richiesta</th>
								<th>Data Ritiro</th>
								<th>Data Riconsegna Prevista</th>
								<th>Data Riconsegna Effettiva</th>
							</tr>";
						PrestitiBibliotecaTerminati();
						}
					}
				?>
		</section>
	</body>
</html>