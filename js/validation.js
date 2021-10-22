var TestoRegExp = /^[A-Za-zטשאעי][a-zA-Z'טשאעי ]*$/;
var TelefonoRegExp = /^(([+]|00)39)?((3[1-6][0-9]))(\d{7})$/;
var TelefonoIsoRegExp = /\+[0-9]{1,3}-[0-9()+\-]{1,30}/;
var UsernameRegExp = /^\S*$/;

function lunghezza(testo, min, max){
   if(testo.length >= min && testo.length <= max) 
		return true;
   else 
		return false;  	
}

function dataValida(data){
	var DataRegExp = /^\d{4}-\d{2}-\d{2}$/; 
	if (!data.match(DataRegExp)) {
		alert("Il formato della data richiesto e' YYYY-MM-DD")
		return false;
	}
 
	var split = data.split('-');

	year = +split[0];
	month = +split[1];
	day = +split[2];

	if (year < 1900) {
		alert("Anno non valido!");
		return false;
	}
	
	if (month < 1 || month > 12) {
		alert("Il mese deve essere compreso tra 1 e 12");
		return false;
	}
	if (day < 1 || day > 31) {
		alert("Il giorno deve essere compreso tra 1 e 31");
		return false;
	}
	if ((month==4 || month==6 || month==9 || month==11) && day==31) {
		alert("Il mese di "+month+" non ha 31 giorni!")
		return false;
	}
	if (month == 2) { 
		var bisestile = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		if (day>29 || (day==29 && !bisestile)) {
			alert("Febbraio " + year + " non ha " + day + " giorni!");
			return false;
		}
	}
 return true;
}

function validaUtente(){
	var Telefono = document.getElementById("Telefono").value;
	var Nome = document.getElementById("Nome").value;
	var Cognome = document.getElementById("Cognome").value;
	var Password = document.getElementById("Password").value;
	var ConfPassword = document.getElementById("ConfermaPassword").value;
	var NomeUtente = document.getElementById("Username").value;
	var DataNascita = document.getElementById("DataNascita").value;
	
	if(!dataValida(DataNascita)){
		return false;
	}
	
	if(Password != ConfPassword){
		alert('Le password non corrispondono!');
		return false;
	}
	
	if(!lunghezza(Password,5,20)){
		alert('La password deve avere da 5 a 20 caratteri!');
		return false;
	}
	
	if(!lunghezza(NomeUtente,5,20)){
		alert('Il nome utente deve avere da 5 a 20 caratteri!');
		return false;
	}
	
	if(!Telefono.match(TelefonoRegExp) && !Telefono.match(TelefonoIsoRegExp)) {
		alert('Numero di telefono non valido! Riprova!');
		return false;
	}
	
	if(!Nome.match(TestoRegExp)) {
			alert('Nome non valido! Riprova!');
			return false;
	}
	
	if(!NomeUtente.match(UsernameRegExp)) {
			alert("Nome utente non valido! Non e' possibile aggiungere spazi! Riprova!");
			return false;
	}
	
	if(!Cognome.match(TestoRegExp)) {
			alert('Cognome non valido! Riprova!');
			return false;
	}	
}

function validaBiblioteca(){
	var Telefono = document.getElementById("Telefono").value;
	var Nome = document.getElementById("Nome").value;
	var Password = document.getElementById("Password").value;
	var ConfPassword = document.getElementById("ConfermaPassword").value;
	var NomeUtente = document.getElementById("username").value;
	
	if(Password != ConfPassword){
		alert('Le password non corrispondono!');
		return false;
	}
	
	if(!Telefono.match(TelefonoRegExp) && !Telefono.match(TelefonoIsoRegExp)) {
		alert('Numero di telefono non valido! Riprova!');
		return false;
	}
	
	if(!Nome.match(TestoRegExp)) {
			alert('Nome non valido! Riprova!');
			return false;
	}
	
	if(!NomeUtente.match(UsernameRegExp)) {
			alert("Nome utente non valido! Non e' possibile aggiungere spazi! Riprova!");
			return false;
	}
	
	if(!lunghezza(Password,5,20)){
		alert('La password deve avere da 5 a 20 caratteri!');
		return false;
	}
	
	if(!lunghezza(NomeUtente,5,20)){
		alert('Il nome utente deve avere da 5 a 20 caratteri!');
		return false;
	}
}

function validaLibro(){
	var Nome = document.getElementById("nome").value;
	var Cognome = document.getElementById("cognome").value;
	var Genere = document.getElementById("genere").value;
	var Lingua = document.getElementById("lingua").value;

	if(!Nome.match(TestoRegExp) || !Cognome.match(TestoRegExp) || !Genere.match(TestoRegExp) || !Lingua.match(TestoRegExp)){
		alert("Qualcosa e' andato storto! Hai usato qualche numero decimale dove non dovevi!");
		return false;
	}
}

function validaImpostazioni(modalita){
	var Telefono = document.getElementById("telefono").value;
	var Nome = document.getElementById("nome").value;
	var Citta = document.getElementById("citta").value;
	var NomeUtente = document.getElementById("username").value;
	var Password = document.getElementById("password").value;

	
	if(!Telefono.match(TelefonoRegExp) && !Telefono.match(TelefonoIsoRegExp)) {
		alert('Numero di telefono non valido! Riprova!');
		return false;
	}
	if(!Nome.match(TestoRegExp)) {
			alert('Nome non valido,ammessi solo caratteri alfabetici! Riprova!');
			return false;
	}
	if(!Citta.match(TestoRegExp)) {
			alert("Citta' non valida,ammessi solo caratteri alfabetici! Riprova!");
			return false;
	}
	
	if(!NomeUtente.match(UsernameRegExp)) {
			alert("Nome utente non valido! Non e' possibile aggiungere spazi! Riprova!");
			return false;
	}
	
	if(!lunghezza(Password,5,20)){
		alert('La password deve avere da 5 a 20 caratteri!');
		return false;
	}
	
	if(!lunghezza(NomeUtente,5,20)){
		alert('Il nome utente deve avere da 5 a 20 caratteri!');
		return false;
	}
	
	if(modalita == 'U'){
		var Cognome = document.getElementById("cognome").value;
		var DataNascita = document.getElementById("datanascita").value;
		
		if(!Cognome.match(TestoRegExp)) {
			alert('Cognome non valido,ammessi solo caratteri alfabetici! Riprova!');
			return false;
		}
		
		if(!dataValida(DataNascita))
			return false;
	}
}

function validaLogin(){
	var NomeUtente = document.getElementById("username").value;
	var Password = document.getElementById("password").value;

	if(!NomeUtente.match(UsernameRegExp)) {
			alert("Nome utente non valido! Non e' possibile aggiungere spazi! Riprova!");
			return false;
	}
	
	if(!lunghezza(Password,5,20)){
		alert('La password deve avere da 5 a 20 caratteri!');
		return false;
	}
	
	if(!lunghezza(NomeUtente,5,20)){
		alert('Il nome utente deve avere da 5 a 20 caratteri!');
		return false;
	}
}