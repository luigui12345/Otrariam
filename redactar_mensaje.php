<?php
include("class/class.php");
include("class/mensajeria.php");
$ald=new Aldea();
$ald->comprobar_recursos('no');
$men=new Mensajeria();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mensajería</title>


<!-- Estilos CSS -->
<link rel="stylesheet" type="text/css" href="estilos/aldea.css" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>

<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
-->

</head>
<body>



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

        <div id="wrap_aldea">
        
        <div id="wrap_centro">
 
			<form name="enviar_mensaje" method="post" action="procesa_mensaje.php" class="form_enviar">
				
				<h2>Enviar un mensaje</h2>

				<?php
				if (isset($_GET['usuario']))
				{
					?>
					<input type="text" value="<?php echo $_GET['usuario'];?>" name="destinatario" required class="input_enviar" placeholder="Destinatario"/><br/>
					<?php
				}
				else
				{
					?>
					<input type="text" name="destinatario" required  class="input_enviar" placeholder="Destinatario"/><br/>
					<?php
				}
				?>
				<input type="text" name="asunto" required  class="input_enviar" placeholder="Asunto"/><br/>
				<textarea name="mensaje"  class="textarea_enviar"></textarea><br />

				<input type="hidden" value="enviar" name="accion"/>
				<input type="submit" value="Enviar" class="boton" />

			</form>

        </div><!--/#wrap_centro-->


        </div><!--/#wrap_aldea-->

    </div><!--/#right-->


</div><!--/#bottom-->


</div>
</div><!--/#wrap-->



</body>

</html>