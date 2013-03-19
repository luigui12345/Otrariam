<?php
function safe($safer) //Para validar texto normal
{
	$mysqli=DB::Get();
	$a=$mysqli->real_escape_string(htmlentities($safer,ENT_QUOTES));
	return $a;
}

function safeh($safer) //Para validar texto de mensajes
{
	$mysqli=Datos::mysqli();
	$a=$mysqli->real_escape_string(strip_tags($safer,"<br>,<hr>"));
	return $a;
}

function safe_edificio($safer) //Para validar edificio
{
	$mysqli=DB::Get();
	if ($safer!='leñador')
	{
		$a=$mysqli->real_escape_string(htmlentities($safer,ENT_QUOTES));
		$sql="select * from edificios_aldea where id_ciudad = ".$_SESSION['ju_ciudad']." and edificio = '$a' limit 1";
		$res=$mysqli->query($sql);
		if ($res->num_rows <=0)
		{
			header("Location:index.php");
			exit;
		}
		return $a;
	}
	else
	{
		return $safer;
	}
}

function safen($safer) //Para validar numero
{
	$mysqli=DB::Get();
	$a=$mysqli->real_escape_string(htmlentities($safer,ENT_QUOTES));
	if (!is_numeric($a))
	{
		header("Location:index.php");
		exit;
	}
	return $a;
}
?>