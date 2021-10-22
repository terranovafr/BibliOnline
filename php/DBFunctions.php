<?php
	require_once "./DBConnessione.php";
	require_once "./util.php";
	
	function listaLibri($result){	
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<div id='empty'>Nessun libro!</div>";
			return;
		}	
		
		echo '<ul class="listalibri">';
		while($row = $result->fetch_assoc()){
			echo '<li class="elementolibro">';
			//Copertina
			echo '<div class="elementocopertina"><a href="./libro.php?libro=' . nospecialchars($row['CodiceISBN']) . '">';
			echo '<img src="../img/uploads/books/';
			if(file_exists("../img/uploads/books/" . $row['CodiceISBN'] . ".png"))
				echo nospecialchars($row['CodiceISBN']) . ".png";
			elseif (file_exists("../img/uploads/books/" . $row['CodiceISBN'] . ".jpeg"))
				echo nospecialchars($row['CodiceISBN']) . ".jpeg";
			elseif (file_exists("../img/uploads/books/" . $row['CodiceISBN'] . ".gif"))
				echo nospecialchars($row['CodiceISBN']) . ".gif";
			elseif (file_exists("../img/uploads/books/" . $row['CodiceISBN'] . ".jpg"))
				echo nospecialchars($row['CodiceISBN']) . ".jpg";
			echo '" alt="Copertina"></a></div>';
		
			// Info Libri
			echo '<div class="infoelementolibro">';
			echo '<a href="./libro.php?libro=' . nospecialchars($row['CodiceISBN']) . '">' . nospecialchars($row['Titolo']) . '</a>';
			echo '<p>' . nospecialchars($row['NomeAutore']) . ' ' . nospecialchars($row['CognomeAutore']) . '</p>';
			echo '</div>';		 				
		}
		echo '</ul>';
	} 
	
	function Libro($libro){
		global $mysqli;
		$libro = $mysqli->real_escape_string($libro);
		// Prendo per ogni libro le sue informazioni, il numero delle copie e il numero delle copie rimanenti (nel caso di biblioteca)
		$query = "SELECT L.CodiceISBN, L.TITOLO,A.NOME,A.COGNOME,L.GENERE,L.CASAEDITRICE,L.ANNOPUBBLICAZIONE,L.LINGUA,L.NUMEROPAGINE,
					( SELECT COUNT(*)
					  FROM COPIA C
					  WHERE C.LIBRO='" . $libro ."' AND C.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) ."' ) AS NUMEROCOPIE,
					 ( SELECT COUNT(DISTINCT C.ID)
					  FROM COPIA C
					  WHERE C.LIBRO='" . $libro ."' AND C.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) ."'
							AND NOT EXISTS ( SELECT *
											 FROM PRESTITO PR
											 WHERE PR.LIBRO = C.LIBRO AND PR.COPIA = C.ID AND PR.BIBLIOTECA = C.BIBLIOTECA  
													AND PR.DATARICONSEGNAEFFETTIVA IS NULL) ) AS NUMEROCOPIERIMANENTI
				  FROM LIBRO L INNER JOIN PUBBLICAZIONE P ON P.LIBRO = L.CODICEISBN
					   INNER JOIN AUTORE A ON A.CODICE = P.AUTORE
				  WHERE L.CODICEISBN='" . $libro . "';";
		$result = $mysqli->query($query);
		return $result;
	}
	
	function mostraLibro($result){	
		global $mysqli;
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<div id='empty'>Ops!!Errore!</div>";
			return;
		}	
		$row = $result->fetch_assoc();
		
		// STAMPO TITOLO
		echo "<h2>" . nospecialchars($row['TITOLO']) . "</h2>";
				
		echo "<div class='contenitore'>
				<div class='libro'>
					<div class='copertina'>";
		// COPERTINA
		echo '<img src="../img/uploads/books/';
			if(file_exists("../img/uploads/books/" . $row['CodiceISBN'] . ".png"))
				echo nospecialchars($row['CodiceISBN']) . ".png";
			elseif (file_exists("../img/uploads/books/" . $row['CodiceISBN'] . ".jpeg"))
				echo nospecialchars($row['CodiceISBN']) . ".jpeg";
			elseif (file_exists("../img/uploads/books/" . $row['CodiceISBN'] . ".gif"))
				echo nospecialchars($row['CodiceISBN']) . ".gif";
			elseif (file_exists("../img/uploads/books/" . $row['CodiceISBN'] . ".jpg"))
				echo nospecialchars($row['CodiceISBN']) . ".jpg";
			echo '" alt="Copertina"></div>';
		echo "<div class='dorso'>";
		// DORSO
		echo "<img src='../img/uploads/books/dorso";
			if(file_exists("../img/uploads/books/dorso" . $row['CodiceISBN'] . ".png"))
				echo nospecialchars($row['CodiceISBN']) . ".png";
			elseif (file_exists("../img/uploads/books/dorso" . $row['CodiceISBN'] . ".jpeg"))
				echo nospecialchars($row['CodiceISBN']) . ".jpeg";
			elseif (file_exists("../img/uploads/books/dorso" . $row['CodiceISBN'] . ".gif"))
				echo nospecialchars($row['CodiceISBN']) . ".gif";
			elseif (file_exists("../img/uploads/books/dorso" . $row['CodiceISBN'] . ".jpg"))
				echo nospecialchars($row['CodiceISBN']) . ".jpg";
		echo "' alt='Dorso'>
					</div>
				</div> 
			</div>";
		// INFORMAZIONI SUL LIBRO
		echo "<div class='infolibro'>
				<div><label>Autore:</label> " . nospecialchars($row['NOME']) . " " . nospecialchars($row['COGNOME']) . "</div>
				<div><label>Casa Editrice:</label> " . nospecialchars($row['CASAEDITRICE']) . "</div>
				<div><label>Genere:</label> " . nospecialchars($row['GENERE']) . "</div>
				<div><label>Lingua:</label> " . nospecialchars($row['LINGUA']) . "</div>
				<div><label>Numero Pagine:</label> " . nospecialchars($row['NUMEROPAGINE']) . "</div>
				<div><label>Anno Pubblicazione:</label> " . nospecialchars($row['ANNOPUBBLICAZIONE']) . "</div>";
			if($_SESSION['modalita']=='B'){
				echo "<div><label>Numero Copie:</label> " . nospecialchars($row['NUMEROCOPIE']) . "</div>";
				echo "<div><label>Numero Copie Disponibili:</label> " . nospecialchars($row['NUMEROCOPIERIMANENTI']) . "</div>";
			}
			echo "</div>";
	}

	function DiRecente(){
		global $mysqli;
		// PRIMI 8 LIBRI AGGIUNTI DI RECENTE IN CITTA'
		$query = "SELECT L.CodiceISBN, L.Titolo, A.NOME AS NomeAutore, A.COGNOME AS CognomeAutore
		  FROM LIBRO L 
			   INNER JOIN
			   PUBBLICAZIONE PU ON PU.LIBRO = L.CODICEISBN
			   INNER JOIN
			   AUTORE A ON A.CODICE = PU.AUTORE
			   INNER JOIN
		( SELECT DISTINCT C.LIBRO
		  FROM COPIA C INNER JOIN BIBLIOTECA B ON C.BIBLIOTECA = B.CODICE
		WHERE B.CITTA = '" . $mysqli->real_escape_string($_SESSION['citta']) . "'
			ORDER BY C.DATAAGGIUNTA DESC
			LIMIT 8 ) AS C ON C.LIBRO = L.CODICEISBN
			GROUP BY L.CODICEISBN, L.TITOLO;"; 
		$result = $mysqli->query($query);
		return $result;
	}

	function BibliotecheDisponibili($libro){
		global $mysqli;
		$libro = $mysqli->real_escape_string($libro);
		// PRENDO LE BIBLIOTECHE CHE HANNO ALMENO UNA COPIA DI TALE LIBRO IN CITTA' E IN CORRIPONDENZA PRENDO UNA COPIA TRA QUELLE DISPONIBILI (QUELLA CON CODICE MINIMO)
		$query = "SELECT B.CODICE,B.NOME, B.INDIRIZZO,B.ACCOUNT, MIN(C.ID) AS ID
				  FROM BIBLIOTECA B
					INNER JOIN COPIA C ON C.BIBLIOTECA = B.CODICE
				  WHERE C.LIBRO = '" . $libro . "'
						AND B.CITTA = '" . $mysqli->real_escape_string($_SESSION['citta']) . "'
						AND NOT EXISTS (
							SELECT *
							FROM PRESTITO P
							WHERE P.COPIA = C.ID
								AND P.LIBRO = C.LIBRO
								AND P.BIBLIOTECA = C.BIBLIOTECA
								AND P.DATARICONSEGNAEFFETTIVA IS NULL )
				  GROUP BY B.CODICE,B.NOME, B.INDIRIZZO,B.ACCOUNT;";
		$result = $mysqli->query($query);
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<tr><td colspan='2'>Nessuna biblioteca nella tua citta' possiede attualmente una copia di questo libro!</td></tr>";
			return false;
		}	
		
		while($row = $result->fetch_assoc())
			echo "<tr><td><input type='radio' name='biblioteca' value='". nospecialchars($row['CODICE']) . "' required><input type='hidden' name='copia' value='" . nospecialchars($row['ID']) . "'><a href='info.php?account=" . nospecialchars(urlencode($row['ACCOUNT'])) . "'>" . nospecialchars($row['NOME']) . "</a></input></td><td>" . nospecialchars($row['INDIRIZZO']) . "</td></tr>";
		return true;
	}
	
	function Settings(){
		global $mysqli;
		// SE UTENTE
		if($_SESSION['modalita'] == 'U'){
			$query = "SELECT *
				  FROM UTENTE U
						INNER JOIN 
						ACCOUNT A ON A.NOMEUTENTE = U.ACCOUNT
				  WHERE U.ID ='" . $mysqli->real_escape_string($_SESSION['ID']) . "';";
		} elseif($_SESSION['modalita'] == 'B'){ 
		// SE BIBLIOTECA
			$query = "SELECT *
				  FROM BIBLIOTECA B INNER JOIN ACCOUNT A ON B.ACCOUNT = A.NOMEUTENTE
				  WHERE B.CODICE ='" . $mysqli->real_escape_string($_SESSION['ID']) . "';";
		}
		$result = $mysqli->query($query);
		
		$row = $result->fetch_assoc();
		echo "<div><label>Nome:</label><input type='text' name='nome' id='nome' value='" . htmlspecialchars($row['Nome'], ENT_QUOTES) . "' required></input></div>";
		if($_SESSION['modalita'] == 'U'){
			//SE UTENTE STAMPO COGNOME E MENU' A TENDINA CITTA' TRA QUELLE IN CUI ESISTE ALMENO UNA BIBLIOTECA ISCRITTA AL SERVIZIO
			echo "<div><label>Cognome:</label><input type='text' name='cognome' id='cognome' value='" . htmlspecialchars($row['Cognome'], ENT_QUOTES) . "' required></input></div>";
			
			echo "<div><label>Citta':</label><select name='citta' id='citta' required><option label=' ' disabled></option>";
			$querycitta = "SELECT DISTINCT B.CITTA
						   FROM BIBLIOTECA B
						   WHERE B.VALIDA=TRUE";
			$resultcitta = $mysqli->query($querycitta);
			while($rowcitta = $resultcitta->fetch_assoc()){
				echo "<option label='". nospecialchars($rowcitta['CITTA']) . "' value='" . nospecialchars($rowcitta['CITTA']) . "'";
				if($_SESSION['citta'] == $rowcitta['CITTA'])
				echo "selected value";
			echo ">". nospecialchars($rowcitta['CITTA']) . "</option>";		
			}
			echo "</select></div>"; 
			
			echo "<div><label>Data Nascita:</label><input type='text' id='datanascita' name='datanascita' value='" . nospecialchars($row['DataNascita']) . "' required></input></div>";
		} else { 
			echo "<div><label>Citta':</label><select name='citta' id='citta' required><option label=' ' disabled></option>";
			include "./cittaitaliane.php";
			foreach($cittaitaliane as $citta){
				echo "<option label='". $citta . "' value='" . $citta . "' ";
				if($_SESSION['citta'] == $citta)
					echo "selected value";
				echo ">". $citta . "</option>";
			}
			echo "</select></div>";
		}
		echo "<div><label>Telefono:</label><input type='text' name='telefono' id='telefono' value='" . nospecialchars($row['Telefono']) . "' required></input></div>";
		if($_SESSION['modalita'] == 'B') // SE BIBLIOTECA STAMPO INDIRIZZO
			echo "<div><label>Indirizzo:</label><input type='text' name='indirizzo' id='indirizzo' value='" . nospecialchars($row['Indirizzo']) . "' required></input></div>";
		echo "<div><label>Nome Utente:</label><input type='text' name='username' id='username' value='" . nospecialchars($row['NomeUtente']) . "' required></input></div>";
		echo "<div><label>Password:</label><input type='password' name='password' id='password' value='" . nospecialchars($row['Password']) . "' required></input></div>";
	}
	
	function Info($account){
		global $mysqli;
		$account = $mysqli->real_escape_string($account);
		//STAMPO IMMAGINE
		echo "<div><img src='../img/uploads/profile/";
		if(file_exists("../img/uploads/profile/" . $account . ".png"))
			echo nospecialchars($account) . ".png";
		elseif(file_exists("../img/uploads/profile/" . $account . ".jpg"))
			echo nospecialchars($account) . ".jpg";
		elseif(file_exists("../img/uploads/profile/" . $account . ".gif"))
			echo nospecialchars($account) . ".gif";
		elseif(file_exists("../img/uploads/profile/" . $account . ".jpeg"))
			echo nospecialchars($account) . ".jpeg";
		else
			echo "default.png";
		echo "' id='userphoto' alt='immagine'></div>";
		
		$query = "SELECT *
				  FROM UTENTE U
					   INNER JOIN
					   ACCOUNT A ON U.ACCOUNT = A.NOMEUTENTE
				  WHERE A.NOMEUTENTE ='" . $account . "';";
		$result = $mysqli->query($query);
		$num = mysqli_num_rows($result);
		
		if($num){ //se utente
			$row = $result->fetch_assoc();
			echo "<div id='userinfo'><div><label>Nome:  </label>" . nospecialchars($row['Nome']) . "</div>";
			echo "<div><label>Cognome:  </label>" . nospecialchars($row['Cognome']) . "</div>";
			echo "<div><label>Data Nascita:  </label>" . nospecialchars($row['DataNascita']) . "</div>";
			echo "<div><label>Telefono:  </label>" . nospecialchars($row['Telefono']) . "</div>";
			echo "<div><label>Nome Utente:  </label>" . nospecialchars($account) . "</div></div>";
		} else { //se biblioteca
			$query = "SELECT *
				  FROM ACCOUNT A
					   INNER JOIN
					   BIBLIOTECA B ON B.ACCOUNT = A.NOMEUTENTE
				  WHERE A.NOMEUTENTE ='" . $account . "';";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			echo "<div id='userinfo'><div><label>Nome:  </label>" . nospecialchars($row['Nome']) . "</div>";
			echo "<div><label>Indirizzo:  </label>" . nospecialchars($row['Indirizzo']) . " , " , $row['Citta'] . "</div>";
			echo "<div><label>Telefono:  </label>" . nospecialchars($row['Telefono']) . "</div>";
			echo "<div><label>Nome Utente:  </label>" . nospecialchars($account) . "</div></div>";	
		}
	}
	
	function DiAutori(){
		global $mysqli;
		// PRENDO 8 LIBRI NON LETTI DI AUTORI DI CUI SI E' GIA' PRESO LIBRI IN PRESTITO, DOVE VI SIA ALMENO UNA COPIA DISPONIBILE IN CITTA'
		$query = "SELECT L.CodiceISBN, L.Titolo, A.Nome as NomeAutore, A.Cognome AS CognomeAutore
				  FROM LIBRO L
					INNER JOIN
					PUBBLICAZIONE PU ON PU.LIBRO = L.CODICEISBN
					INNER JOIN
					( SELECT DISTINCT C.LIBRO
					  FROM COPIA C
						INNER JOIN
						BIBLIOTECA B ON C.BIBLIOTECA = B.CODICE
					  WHERE B.CITTA ='" . $mysqli->real_escape_string($_SESSION['citta']) . "'
					) AS C ON C.LIBRO = L.CODICEISBN
					INNER JOIN
					AUTORE A ON A.CODICE = PU.AUTORE
				WHERE EXISTS ( SELECT *
							FROM PRESTITO P
								INNER JOIN
								COPIA C2 ON ( P.COPIA = C2.ID AND P.LIBRO = C2.LIBRO AND P.BIBLIOTECA = C2.BIBLIOTECA )
								INNER JOIN
								LIBRO L2 ON C2.LIBRO = L2.CODICEISBN
								INNER JOIN
								PUBBLICAZIONE PU2 ON PU2.LIBRO = L2.CODICEISBN
								INNER JOIN
								AUTORE A2 ON A2.CODICE = PU2.AUTORE
							WHERE L2.CODICEISBN != L.CODICEISBN
							AND A.CODICE = A2.CODICE
							AND P.UTENTE ='" . $mysqli->real_escape_string($_SESSION['ID']) . "')
					AND NOT EXISTS ( SELECT *
									 FROM PRESTITO P
									 WHERE P.LIBRO = L.CODICEISBN AND P.UTENTE='" . $mysqli->real_escape_string($_SESSION['ID']) . "')
					LIMIT 8;";
		$result = $mysqli->query($query);
		return $result;
	}
	
	function GenerePreferito(){
		global $mysqli;
		// PRENDO IL GENERE DOVE VI SIANO PIU' LIBRI PRESI IN PRESTITO
		$query = "SELECT L.GENERE
				FROM (
				SELECT L.GENERE, COUNT(DISTINCT L.CODICEISBN) AS NUMERO
				 FROM PRESTITO P
					  INNER JOIN
					  COPIA C ON ( C.ID = P.COPIA AND C.LIBRO = P.LIBRO AND P.BIBLIOTECA = C.BIBLIOTECA )
					  INNER JOIN
					  LIBRO L ON C.LIBRO = L.CODICEISBN
				 WHERE P.UTENTE ='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
			     GROUP BY L.GENERE
				 ) AS L
				 WHERE L.NUMERO >= ALL ( SELECT COUNT(DISTINCT L.CODICEISBN) AS NUMERO
				 FROM PRESTITO P
					  INNER JOIN
					  COPIA C ON ( C.ID = P.COPIA AND C.LIBRO = P.LIBRO )
					  INNER JOIN
					  LIBRO L ON C.LIBRO = L.CODICEISBN
				 WHERE P.UTENTE ='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
			     GROUP BY L.GENERE ) LIMIT 1;"; 
		$result = $mysqli->query($query);
		if(!mysqli_num_rows($result))
			return;
		$row = $result->fetch_assoc();
		return nospecialchars($row['GENERE']);
	}
	
	function LibriGenere($genere){
		global $mysqli;
		// PRENDO 8 LIBRI DI UN DETERMINATO GENERE DOVE ESISTE ALMENO UNA COPIA NELLA PROPRIA CITTA'
		$query = "SELECT L.CodiceISBN, L.Titolo, A.NOME AS NomeAutore, A.COGNOME AS CognomeAutore
			FROM LIBRO L 
			   INNER JOIN
			   PUBBLICAZIONE P ON P.LIBRO = L.CODICEISBN
			   INNER JOIN
			   AUTORE A ON A.CODICE = P.AUTORE
			   INNER JOIN
			( SELECT DISTINCT C.LIBRO
			  FROM COPIA C 
				INNER JOIN 
				BIBLIOTECA B ON C.BIBLIOTECA = B.CODICE
			  WHERE B.CITTA = '" . $mysqli->real_escape_string($_SESSION['citta']) . "'
			) AS C ON C.LIBRO = L.CODICEISBN
		   WHERE L.GENERE ='" . $mysqli->real_escape_string($genere) . "'
			GROUP BY L.CODICEISBN, L.TITOLO
			LIMIT 8;"; 
		$result = $mysqli->query($query);
		return $result;
	}
	
	function IPiuPrestati(){
		global $mysqli;
		// PRENDO I PRIMI 8 LIBRI PIU' PRESTATI NELLA PROPRIA BIBLIOTECA
		$query = "SELECT L.CodiceISBN, L.Titolo, A.NOME AS NomeAutore, A.COGNOME AS CognomeAutore, COUNT(DISTINCT P.COPIA) AS NumeroPrestiti
					FROM PRESTITO P
						INNER JOIN
						COPIA C ON ( C.ID = P.COPIA AND C.LIBRO = P.LIBRO AND C.BIBLIOTECA = P.BIBLIOTECA )
						INNER JOIN
						LIBRO L ON L.CODICEISBN = C.LIBRO
						INNER JOIN
						PUBBLICAZIONE PR ON PR.LIBRO = L.CODICEISBN
						INNER JOIN
						AUTORE A ON A.CODICE = PR.AUTORE
					WHERE C.BIBLIOTECA = " . $mysqli->real_escape_string($_SESSION['ID']) . "
					GROUP BY L.CODICEISBN
					ORDER BY NUMEROPRESTITI DESC
					LIMIT 8;";
		$result = $mysqli->query($query);
		return $result;
	}
	
	function cerca($pattern){
		global $mysqli;
		$pattern = $mysqli->real_escape_string($pattern);
		if($_SESSION['modalita'] == 'U') // SE UTENTE CERCO LIBRI DELLE BIBLIOTECHE DELLA PROPRIA CITTA'
			$query = "SELECT DISTINCT L.CodiceISBN, L.Titolo, A.NOME AS NomeAutore, A.COGNOME AS CognomeAutore
				  FROM LIBRO L 
					   INNER JOIN
					   COPIA C ON C.LIBRO = L.CODICEISBN
					   INNER JOIN
					   BIBLIOTECA B ON C.BIBLIOTECA = B.CODICE
					   INNER JOIN
					   PUBBLICAZIONE P ON P.LIBRO = L.CODICEISBN
					   INNER JOIN
					   AUTORE A ON P.AUTORE = A.CODICE
				  WHERE B.CITTA = '" . $mysqli->real_escape_string($_SESSION['citta']) . "'
				  AND ( L.CODICEISBN LIKE '%" . $pattern . "%' OR L.TITOLO LIKE '%" . $pattern . "%' OR A.NOME LIKE '%" . $pattern . "%' OR A.COGNOME LIKE '%" . $pattern . "%'
						OR L.CASAEDITRICE LIKE '%" . $pattern . "%' OR L.GENERE LIKE '%" . $pattern . "%');";
		elseif($_SESSION['modalita'] == 'B') // SE BIBLIOTECA CERCO LIBRI DELLA STESSA BIBLIOTECA
			$query = "SELECT DISTINCT L.CodiceISBN, L.Titolo, A.NOME AS NomeAutore, A.COGNOME AS CognomeAutore
				  FROM LIBRO L 
					   INNER JOIN
					   COPIA C ON C.LIBRO = L.CODICEISBN
					   INNER JOIN
					   PUBBLICAZIONE P ON P.LIBRO = L.CODICEISBN
					   INNER JOIN
					   AUTORE A ON P.AUTORE = A.CODICE
				  WHERE C.BIBLIOTECA = '" . $mysqli->real_escape_string($_SESSION['ID']) . "'
				  AND ( L.CODICEISBN LIKE '%" . $pattern . "%' OR L.TITOLO LIKE '%" . $pattern . "%' OR A.NOME LIKE '%" . $pattern . "%' OR A.COGNOME LIKE '%" . $pattern . "%'
						OR L.CASAEDITRICE LIKE '%" . $pattern . "%' OR L.GENERE LIKE '%" . $pattern . "%');";
		$result = $mysqli->query($query);
		return $result;
	}
	
	function PrestitiUtenteInCorso(){
		global $mysqli;
		$query = "SELECT P.*, L.Titolo, L.CodiceISBN, B.Nome, B.Indirizzo, B.Account
				  FROM PRESTITO P
						INNER JOIN
						COPIA C ON ( P.COPIA = C.ID AND P.LIBRO = C.LIBRO AND P.BIBLIOTECA = C.BIBLIOTECA )
						INNER JOIN
						LIBRO L ON L.CODICEISBN = C.LIBRO
						INNER JOIN
						BIBLIOTECA B ON B.CODICE = C.BIBLIOTECA
				  WHERE P.Utente='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND DataRiconsegnaEffettiva IS NULL;";
		$result = $mysqli->query($query);
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<tr><td colspan='8'>Nessuna prestito in corso!</td></tr>";
			return;
		}	
		while($row = $result->fetch_assoc()){
			echo "<tr>";
			echo "<td><a href='libro.php?libro=" . nospecialchars($row['CodiceISBN']) . "'>" . nospecialchars($row['Titolo']) . "</a></td>";
			echo "<td><a href='info.php?account=" . nospecialchars(urlencode($row['Account'])) . "'>" . nospecialchars($row['Nome']) . "</a></td>";
			echo "<td>" . nospecialchars($row['DataRichiesta']) . "</td>";
			echo "<td>" . nospecialchars($row['DataMaxRitiro']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRitiro']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRiconsegnaPrevista']) . "</td>";
			if($row['DataRitiro']==''){
				echo "<td>" . nospecialchars($row['DataRiconsegnaEffettiva']) . "</td>";
				echo "<td><a href='elimina.php?prestito=" . nospecialchars($row['ID']) . "'>Annulla</a></td>";
			} else 
				echo "<td colspan='2'>" . nospecialchars($row['DataRiconsegnaEffettiva']) . "</td>";
			echo "</tr>";
		}
	}
	
	function PrestitiUtenteTerminati(){
		global $mysqli;
		$query = "SELECT P.*, L.Titolo, L.CodiceISBN, B.Nome, B.Indirizzo, B.Account
				  FROM PRESTITO P
						INNER JOIN
						COPIA C ON ( P.COPIA = C.ID AND P.LIBRO = C.LIBRO AND P.BIBLIOTECA = C.BIBLIOTECA )
						INNER JOIN
						LIBRO L ON L.CODICEISBN = C.LIBRO
						INNER JOIN
						BIBLIOTECA B ON B.CODICE = C.BIBLIOTECA
				  WHERE P.Utente='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND DataRiconsegnaEffettiva IS NOT NULL;";
		$result = $mysqli->query($query);
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<tr><td colspan='7'>Nessun prestito terminato!</td></tr>";
			return;
		}	
		while($row = $result->fetch_assoc()){
			echo "<tr>";
			echo "<td><a href='libro.php?libro=" . nospecialchars($row['CodiceISBN']) . "'>" . nospecialchars($row['Titolo']) . "</a></td>";
			echo "<td><a href='info.php?account=" . nospecialchars(urlencode($row['Account'])) . "'>"  . nospecialchars($row['Nome']) . "</a></td>";
			echo "<td>" . nospecialchars($row['DataRichiesta']) . "</td>";
			echo "<td>" . nospecialchars($row['DataMaxRitiro']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRitiro']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRiconsegnaPrevista']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRiconsegnaEffettiva']) . "</td>";
			echo "</tr>";
		}
	}
	
	function PrestitiBibliotecaInCorso(){
		global $mysqli;
		$query = "SELECT P.*, L.Titolo, L.CodiceISBN,U.ID AS CodUtente, U.Nome, U.Cognome, U.Account
				  FROM PRESTITO P
						INNER JOIN
						COPIA C ON ( P.COPIA = C.ID AND P.LIBRO = C.LIBRO AND P.BIBLIOTECA = C.BIBLIOTECA)
						INNER JOIN
						LIBRO L ON L.CODICEISBN = C.LIBRO
						INNER JOIN
						UTENTE U ON U.ID = P.UTENTE
				  WHERE P.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND DataRiconsegnaEffettiva IS NULL;";
		$result = $mysqli->query($query);
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<tr><td colspan='10'>Nessuna prestito in corso!</td></tr>";
			return;
		}	
		while($row = $result->fetch_assoc()){
			echo "<tr>";
			echo "<td><a href='libro.php?libro=" . nospecialchars($row['CodiceISBN']) . "'>" . nospecialchars($row['Titolo']) . "</a></td>";
			echo "<td>" . nospecialchars($row['Copia']) . "</td>";
			echo "<td><a href='info.php?account=" . nospecialchars(urlencode($row['Account'])) . "'>" . nospecialchars($row['Nome']) . " " . nospecialchars($row['Cognome']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRichiesta']) . "</td>";
			echo "<td>" . nospecialchars($row['DataMaxRitiro']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRitiro']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRiconsegnaPrevista']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRiconsegnaEffettiva']) . "</td>";
			if($row['DataRitiro']=='')
				echo "<td><a href='consegna.php?prestito=" . nospecialchars($row['ID']) . "&utente=" . nospecialchars($row['CodUtente']) . "'>Consegna</a></td>";
			else
				echo "<td colspan='2'><a href='riconsegna.php?prestito=" . nospecialchars($row['ID']) . "&utente=" . nospecialchars($row['CodUtente']) . "'>Termina</a></td>";
			if($row['DataRitiro']=='')
				echo "<td><a href='elimina.php?prestito=" . nospecialchars($row['ID']) . "'>Elimina</a></td>";
			echo "</tr>";
		}
	}
	
	function PrestitiBibliotecaTerminati(){
		global $mysqli;
		$query = "SELECT P.*, L.Titolo, L.CodiceISBN, U.Nome, U.Cognome, U.Account
				  FROM PRESTITO P
						INNER JOIN
						COPIA C ON ( P.COPIA = C.ID AND P.LIBRO = C.LIBRO AND P.BIBLIOTECA = C.BIBLIOTECA )
						INNER JOIN
						LIBRO L ON L.CODICEISBN = C.LIBRO
						INNER JOIN
						UTENTE U ON U.ID = P.UTENTE
				  WHERE P.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND DataRiconsegnaEffettiva IS NOT NULL;";
		$result = $mysqli->query($query);
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<tr><td colspan='8'>Nessuna prestito terminato!</td></tr>";
			return;
		}	
		while($row = $result->fetch_assoc()){
			echo "<tr>";
			echo "<td><a href='libro.php?libro=" . nospecialchars($row['CodiceISBN']) . "'>" . nospecialchars($row['Titolo']) . "</a></td>";
			echo "<td>" . nospecialchars($row['Copia']) . "</td>";
			echo "<td><a href='info.php?account=" . nospecialchars(urlencode($row['Account'])) . "'>" . nospecialchars($row['Nome']) . " " . nospecialchars($row['Cognome']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRichiesta']) . "</td>";
			echo "<td>" . nospecialchars($row['DataMaxRitiro']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRitiro']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRiconsegnaPrevista']) . "</td>";
			echo "<td>" . nospecialchars($row['DataRiconsegnaEffettiva']) . "</td>";
			echo "</tr>";
		}
	}
	
	function TessereDaConsegnare(){
		global $mysqli;
		$query = "SELECT U.ID,U.NOME, U.COGNOME, U.ACCOUNT, C.DATAMAXRITIRO
				  FROM CONSEGNA C
					   INNER JOIN
					   UTENTE U ON U.ID = C.TESSERA
				  WHERE C.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND C.DATARITIRO IS NULL;";
		$result = $mysqli->query($query);
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<tr><td colspan='4'>Nessuna tessera da consegnare!</td></tr>";
			return;
		}	
		while($row = $result->fetch_assoc()){
			echo "<tr>";
			echo "<td><a href='info.php?account=" . nospecialchars(urlencode($row['ACCOUNT'])) . "'>" . nospecialchars($row['NOME']) . " " . nospecialchars($row['COGNOME']) . "</td>";
			echo "<td><a href='../img/uploads/tessere/" . nospecialchars($row['ACCOUNT'])  . ".png'>Immagine</td>";
			echo "<td>" . nospecialchars($row['DATAMAXRITIRO']) . "</td>";
			echo "<td><a href='consegna.php?utente=" . nospecialchars($row['ID']) . "'>Consegna</a></td>"; 
			echo "</tr>";
		}
	}
	
	function TessereConsegnate(){
		global $mysqli;
		$query = "SELECT U.NOME, U.COGNOME, U.ACCOUNT, C.DATARITIRO
				  FROM CONSEGNA C
					   INNER JOIN
					   UTENTE U ON U.ID = C.TESSERA
				  WHERE C.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND C.DATARITIRO IS NOT NULL;";
		$result = $mysqli->query($query);
		$numero = mysqli_num_rows($result);		
		if($numero <= 0) { 
			echo "<tr><td colspan='3'>Nessuna tessera consegnata!</td></tr>";
			return;
		}	
		while($row = $result->fetch_assoc()){
			echo "<tr>";
			echo "<td><a href='info.php?account=" . nospecialchars(urlencode($row['ACCOUNT'])) . "'>" . nospecialchars($row['NOME']) . " " . nospecialchars($row['COGNOME']) . "</td>";
			echo "<td><a href='../img/uploads/tessere/" . nospecialchars($row['ACCOUNT'])  . ".png'>Immagine</td>";
			echo "<td>" . nospecialchars($row['DATARITIRO']) . "</td>"; 
			echo "</tr>";
		}
	}
	
	function Statistiche(){
		global $mysqli;
		
		// PRESTITI ODIERNI
		$query = "SELECT COUNT(DISTINCT P.ID) AS NUMERO
				  FROM PRESTITO P
						INNER JOIN
						COPIA C ON ( P.COPIA = C.ID AND P.LIBRO = C.LIBRO AND P.BIBLIOTECA = C.BIBLIOTECA )
				  WHERE C.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND DataRichiesta ='" . date("Y-m-d") . "';";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		echo "<div class='stat'>Prestiti Richiesti Oggi : " . nospecialchars($row['NUMERO']) . "</div>";
		
		// LIBRI CONSEGNATI OGGI
		$query = "SELECT COUNT(DISTINCT P.ID) AS NUMERO
				  FROM PRESTITO P
						INNER JOIN
						COPIA C ON ( P.COPIA = C.ID AND P.LIBRO = C.LIBRO AND P.BIBLIOTECA = C.BIBLIOTECA )
				  WHERE C.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND DataRitiro ='" . date("Y-m-d") . "';";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		echo "<div class='stat'>Libri Consegnati Oggi : " . nospecialchars($row['NUMERO']) . "</div>";
		
		// LIBRI RICONSEGNATI OGGI
		$query = "SELECT COUNT(DISTINCT P.ID) AS NUMERO
				  FROM PRESTITO P
						INNER JOIN
						COPIA C ON ( P.COPIA = C.ID AND P.LIBRO = C.LIBRO AND P.BIBLIOTECA = C.BIBLIOTECA )
				  WHERE C.BIBLIOTECA='" . $mysqli->real_escape_string($_SESSION['ID']) . "'
						AND DataRiconsegnaEffettiva ='" . date("Y-m-d") . "';";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		echo "<div class='stat'>Libri Riconsegnati Oggi : " . nospecialchars($row['NUMERO']) . "</div>";
	}
?>