<?php
include("class/class.php");
include("class/mensajeria.php");
$men=new Mensajeria();
if ($_POST['accion']=="enviar")
{
	$men->enviar_mensaje();
}

if ($_POST['accion']=="responder")
{
	$men->responder_mensaje();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aldea</title>


<!-- Estilos CSS -->
<link rel="stylesheet" type="text/css" href="estilos/aldea.css" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>

<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
-->

</head>
<body>


</body>

</html>
