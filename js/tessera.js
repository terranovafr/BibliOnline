window.onload = function() {
	  var canvas = document.getElementById("canvas");
      var nome = document.getElementById("Nome");
      var cognome = document.getElementById("Cognome");
      var account = document.getElementById("Username");
	  var citta = document.getElementById("Citta");
      var ctx = canvas.getContext("2d");

      var wallpaper = canvas.getContext("2d");
      var background = new Image();
      background.src = "../img/retrotessera.jpg";
	  
      background.onload = function() {
        wallpaper.drawImage(background, 0, 0, canvas.width, canvas.height);
      }

      document.getElementById('immagine').onchange = function(e) {
          var img = new Image();
          img.onload = draw;
          img.src = URL.createObjectURL(this.files[0]);
      };
	  
	  function draw() {
        var ctx = canvas.getContext('2d');
		ctx.fillStyle = 'white';
		ctx.fillRect(206, 18, 85, 64);
        ctx.drawImage(this,206,18,85,64);
      }
	  
      nome.onkeyup = function(e) {
          ctx.font = '14px Helvetica';
		  ctx.fillStyle = 'white';
		  ctx.fillRect(17, 12, 180, 18);
		  ctx.fillStyle = 'black';
          ctx.fillText(nome.value,17,25,180,30);
      };
	  
      cognome.onkeyup = function(e) {
        ctx.font = '14px Helvetica';
		ctx.fillStyle = 'white';
		ctx.fillRect(17, 39, 180, 16);
		ctx.fillStyle = 'black';
        ctx.fillText(cognome.value,17,52,180,30);
      };
	  
      account.onkeyup = function(e) {
        ctx.font = '14px Helvetica';
		ctx.fillStyle = 'white';
		ctx.fillRect(17, 66, 180, 16);
		ctx.fillStyle = 'black';
        ctx.fillText(account.value,17,76,180,30);
      };
	  
      citta.onchange = function(e) {
        ctx.font = '13px Arial';
		ctx.fillStyle = 'white';
		ctx.fillRect(40, 105, 65, 18);
		ctx.fillStyle = 'black';
        ctx.fillText(citta.value,40,117,60,30);
      };
	  
	  function uploadTessera() {
		var dataURL = canvas.toDataURL("image/png");
		document.getElementById("hidden").value = dataURL;
		var fd = new FormData(document.forms["registrazioneutente"]);
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'registrazioneutente.php', true);
		xhr.send(fd);
	}
	
	document.getElementById("button").addEventListener("click", uploadTessera);
}