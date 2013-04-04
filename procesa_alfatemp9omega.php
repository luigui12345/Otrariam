<?php
include("class/class.php");
include("class/admin.php");
$adm=new Admin();
if ($_GET['a']==1)
{
	$adm->eliminarUsuario();
}
else if ($_GET['a']==2)
{
	$adm->addTropas();
}
?>