<?php
require_once("class/class.php");
require_once("class/alianza.php");
$ali=new Alianza();
$ald=new Aldea();
$ald->comprobar_recursos('no');
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alianza</title>


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
        
        <div id="wrap_alianza">

        	<div id="enlaces_alianza">
			<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=0">Inicio</a>
			<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=1">Invitar</a>
			<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=2">Expulsar</a>
			<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=3">Cargos</a>
			<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=4">Diplomacia</a>
			</div>

			<br/><br/>
			
			<?php
			$ali->mostrarAlianza();
			?>

        </div><!--/#reportes-->



        </div><!--/#wrap_aldea-->

    </div><!--/#right-->


</div><!--/#bottom-->


</div>
</div><!--/#wrap-->



</body>

</html>
