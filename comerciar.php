<?php
require_once('./FirePHPCore/FirePHP.class.php');
require_once("class/init.php");
require_once ('class/seguridad.php');
require_once('class/datos_auxiliares.php');
require_once("class/mercado.php");
$mer=new Mercado();
$mer->aceptar_oferta();
?>