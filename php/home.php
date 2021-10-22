<?php
	session_start();
    include "./util.php";

    if (!Accesso())
		redirect('./../index.php');
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
		<title>BibliOnline</title>
	</head>
	<body>	
		<?php
			include "./layout.php";
		?>
		<section class="white">
			<h2>Home</h2>
			<?php
				if($_SESSION['modalita'] =='B'){
				//Pulsanti
				echo "<nav class='smallmenu'>	
						<a href='aggiungilibro.php'><img src='../img/icons/addbook.png' alt='aggiungi libro'>Aggiungi un nuovo libro</a>
						<a href='aggiungicopia.php'><img src='../img/icons/addcopy.png' alt='aggiungi copia'>Aggiungi una nuova copia</a>
						<a href='eliminacopia.php'><img src='../img/icons/delete.png' alt='rimuovi copia'>Rimuovi una copia</a>
					</nav>";
				}
			?>
				<?php
					require_once "./DBFunctions.php";
					if($_SESSION['modalita'] == 'U'){
						//Nuovi Arrivati
						echo "<section><h3>Nuovi Arrivati nella tua citta'</h3>";
						$result = DiRecente();
						listaLibri($result);
						echo "</section>";
						//Di autori di cui hai già letto libri
						echo "<section><h3>Libri non letti di Autori di cui hai gia' preso in prestito libri</h3>";
						$result = DiAutori();
						listaLibri($result);
						echo "</section>";
						//Genere preferito
						$genere = GenerePreferito();
						if($genere != ""){
							echo "<section><h3>Libri del tuo genere preferito : " . $genere . "</h3>";
							$result = LibriGenere($genere);
							listaLibri($result);
							echo "</section>";
						}
					} elseif($_SESSION['modalita'] =='B'){
						//Statistiche
						echo "<div id='stat'>";
						Statistiche();
						echo "</div>";
						//I più prestati
						echo "<section><h3>I piu' prestati!</h3>";
						$result = IPiuPrestati();
						listaLibri($result);
						echo "</section>";	
					}
				?>
		</section>
	</body>
</html>