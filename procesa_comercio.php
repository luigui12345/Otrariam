<?php
include("class/class.php");
$mer=new Mercado('no');

if ($_POST['accion']=='ofertar')
{
	$mer->ofertar();
}
if ($_POST['accion']=='enviar')
{
	$mer->enviar();
}

?>