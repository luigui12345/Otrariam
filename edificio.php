<?php
include("class/class.php");
$ald=new Aldea();
$ald->comprobar_recursos('no');
$ciudad=$_SESSION['ju_ciudad'];

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo Datos::edificioPorSlot($_GET["s"]);?></title>


<!-- Estilos CSS -->
<link rel="stylesheet" type="text/css" href="estilos/aldea.css" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>

<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
-->

</head>
<body onLoad="actualiza_recursos()">




<div id="wrap">
<div id="wrap_center">


<div id="top">
    
    <div id="logo">
        
    </div>

    <div id="menu">
        <?php include("include/menu.php"); ?>
    </div>

</div>


<div id="bottom">

    <div id="left">
        <?php include("include/left.php");?>
    </div>

    <div id="right">

        <div id="recursos">
            <?php include("include/recursos.php"); ?>
        </div>

        <div id="wrap_aldea">


			<div id="wrap_centro">

			<?php
			if (Datos::edificioPorSlot($_GET['s'])=='mercado')
			{
				?>
				
				<div id="seccion_edificio">
				<div class="seccion_edificio" id="a_mercado1">Construir</div>
				<div class="seccion_edificio" id="a_mercado2">Comerciar</div>
				<div class="seccion_edificio" id="a_mercado3">Enviar recursos</div>
				<div class="seccion_edificio" id="a_mercado4">Ver ofertas</div>
				</div>


				<div id="intercambios" style="display:none;">
					Envíos:
					<div id="envios">

					</div>
					
					<div id="recibos">
					Recibos:

					</div>
				</div>
			<?php
			}


			$ald->muestra_edificio(Datos::edificioPorSlot($_GET['s']));
			?>

			</div><!--wrap_centro-->
			

	        <div id="info_aldea">
	            <?php include("include/produccion.php"); ?>
	        </div>


        </div><!--/#wrap_aldea-->

    </div><!--/#right-->


</div><!--/#bottom-->


</div>
</div><!--/#wrap-->



<?php
$ald->comprobar_recursos('no');
?>

</body>
</html>
