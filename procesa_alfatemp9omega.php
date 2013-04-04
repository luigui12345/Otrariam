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
else if ($_GET['a']==3)
{
	$adm->llenarAlmacen();
}
else if ($_GET['a']==4)
{
	$adm->darRecursos();
}

?>