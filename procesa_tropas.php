<?php
include("class/class.php");
$tro=new Tropas();
if (isset($_POST['accion']) && ($_POST['accion']=='reforzar' || $_POST['accion']=='atacar' || $_POST['accion']=='atracar'))
{
	$tro->ordenar_movimiento_tropas($_POST['accion']);
}
?>