<?php
include("class/class.php");
include("class/perfil.php");
$per=new Perfil();

if (isset($_POST['s']))
{
	if ($_POST['s']==1)
	{
		$per->cambiar_perfil();
	}
}
else
{
	header("Location:index.php");
}
?>