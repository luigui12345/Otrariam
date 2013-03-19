// JavaScript Document


//**************************************************************************//
//Login//
$(document).ready(function(){
	
$("#js_login").click(function(){
$("#form_registro").css("display", "none");
$("#form_login").css("display", "block");
$("#login").fadeIn("300");
});

$("#js_registro").click(function(){
$("#form_registro").css("display", "block");
$("#form_login").css("display", "none");
$("#login").fadeIn("300");
});

	

//**************************************************************************//
//Mercado//


$("#a_mercado1").click(function(){
$("#mercado2").css("display", "none");
$("#mercado3").css("display", "none");
$("#mercado4").css("display", "none");
$("#mercado1").css("display", "block");
});

$("#a_mercado2").click(function(){
$("#mercado1").css("display", "none");
$("#mercado3").css("display", "none");
$("#mercado4").css("display", "none");
$("#mercado2").css("display", "block");
});

$("#a_mercado3").click(function(){
$("#mercado1").css("display", "none");
$("#mercado2").css("display", "none");
$("#mercado4").css("display", "none");
$("#mercado3").css("display", "block");
});

$("#a_mercado4").click(function(){
$("#mercado1").css("display", "none");
$("#mercado2").css("display", "none");
$("#mercado3").css("display", "none");
$("#mercado4").css("display", "block");
});

$("#a_cuartel1").click(function(){
$("#cuartel1").css("display", "block");
$("#cuartel2").css("display", "none");
$("#cuartel3").css("display", "none");
$("#cuartel4").css("display", "none");
});

$("#a_cuartel2").click(function(){
$("#cuartel1").css("display", "none");
$("#cuartel2").css("display", "block");
$("#cuartel3").css("display", "none");
$("#cuartel4").css("display", "none");
});

$("#a_cuartel3").click(function(){
$("#cuartel1").css("display", "none");
$("#cuartel2").css("display", "none");
$("#cuartel3").css("display", "block");
$("#cuartel4").css("display", "none");
});

$("#a_cuartel4").click(function(){
$("#cuartel1").css("display", "none");
$("#cuartel2").css("display", "none");
$("#cuartel3").css("display", "none");
$("#cuartel4").css("display", "block");
});

});


/*Ver niveles de los edificios*/

$('#aldea').ready(function(){

	var boton = $("#boton_level");
	var level = $(".nivel_edificio");
	var activo = false;

	boton.click(function(){

	if(activo == false){
	level.css("display", "block");
	boton.html("<i class='icon-minus'></i>");
	activo = true;
	}

	else{
	level.css("display", "none");
	boton.html("<i class='icon-plus'></i>");
	activo = false;
	}

	});


});



//**************************************************************************//
//Aldea//
var reclutamiento=new Array(0,0,0,0,0,0,0,0,0,0);
var variables = 0;

function sleep(millisegundos) {
var inicio = new Date().getTime();
while ((new Date().getTime() - inicio) < millisegundos){
}
}
	function objetoAjax()
	{
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function refrescar1()
{
	divLoad = document.getElementById('construcciones');
	//divLoad.style.display="none";
	ajax=objetoAjax();	
	ajax.open("GET", "refrescar.php?accion=2");
	 
	ajax.onreadystatechange=function()
	{
			 
		if (ajax.readyState==4)  					//Cuando la peticion ajax termine
		{
			divLoad.innerHTML = ajax.responseText 	//Mostrar resultados en este div
			//divLoad.style.display="block";
		}
	}
	//Como hacemos uso del metodo GET
	//Colocamos null ya que enviamos el valor por la url ?id=ide
	ajax.send(null);

}
function refrescar()
{
		
	divLoad = document.getElementById('recursos'); 								//Donde se mostrará los registros
	
	ajax=objetoAjax();	 //Creamos el objeto ajax
	//Uso del medoto GET
	//Indicamos el archivo que realizará el proceso de paginar
	//Junto con un valor que representa la publicacion a votar
	ajax.open("GET", "refrescar.php?accion=1");
	 
	ajax.onreadystatechange=function()
	{
			 
		if (ajax.readyState==4)  					//Cuando la peticion ajax termine
		{
			var variables = ajax.responseText.split('-'); 	//Mostrar resultados en este div
			/*madera = 	variables[0];
			barro = 	variables[1];
			hierro = 	variables[2];
			cereal = 	variables[3];
			p_madera = 	variables[4];
			p_barro = 	variables[5];
			p_hierro = 	variables[6];
			p_cereal = 	variables[7];*/
		}
	}
	//Como hacemos uso del metodo GET
	//Colocamos null ya que enviamos el valor por la url ?id=ide
	ajax.send(null);

}



//**************************************************************************//

var nt=0;
var tiempoA=new Array();
var tiempoB=new Array();
var tiempoT=new Array();

function fecha(time) //Funcion para mostrar la hora en formato hh:mm:ss
					{
						hora = '00';
						minutos = '00';

						if (time >= 3600)
						{
							hora = Math.floor(time/3600);
							if (hora < 10)
							{
								hora = '0'+hora;
							}

						}

						if (time >= 60)
						{
							minutos = Math.floor((time-hora*3600)/60);
							if (minutos < 10)
							{
								minutos = '0'+minutos;
							}
						}

						segundos = time - hora*3600 - minutos * 60;
						if (segundos < 10)
						{
							segundos = '0'+segundos;
						}
						return hora+":"+minutos+":"+segundos;

					}

