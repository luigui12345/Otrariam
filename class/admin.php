<?php

class Admin
{
	private $mysqli;
	private $t_actual;

	public function __construct()
	{
		$this->mysqli=DB::Get();
		$this->t_actual=strtotime(date('Y-m-d H:i:s'));
	}

	public function eliminarUsuario()
	{
		$id_usuario=Datos::id(safe($_POST['usuario']));
		if (Datos::existeUsuario($id_usuario)==0)
		{
			header("location:index.php");
		}
		
		$sql="select * from mapa where id_usuario=$id_usuario";
		$red=$this->mysqli->query($sql);
		while($reg=$red->fetch_array())
		{
			$sql="delete from edificios_aldea where id_ciudad=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from tropas where id_ciudad=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from ofertas where id_ciudad=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from tropas_refuerzos where id_ciudad_refuerza=".$reg['id_casilla']." or id_ciudad_reforzada=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from ataques where id_ciudad_atacante=".$reg['id_casilla']." or id_ciudad_atacada=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from cola_produccion where id_ciudad=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from eventos where id_ciudad=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from intercambios where id_ciudad_ofrece=".$reg['id_casilla']." or id_ciudad_busca=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from reportes_tropas where id_ciudad_atacante=".$reg['id_casilla']." or id_ciudad_atacada=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
			$sql="delete from vuelta_ataques where id_ciudad_atacante=".$reg['id_casilla']." or id_ciudad_atacada=".$reg['id_casilla'];
			$res=$this->mysqli->query($sql);
		}
		$sql="update mapa set nombre = 'Terreno Libre', tipo = 'Naturaleza', habitantes = 0,
		madera = 0, barro=0,hierro=0,cereal=0,last_update=0,capital='',id_usuario=0 where id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		$sql="delete from usuarios where id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		$sql="delete from cargos_alianzas where id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		$sql="delete from mensajes where id_emisor=$id_usuario or id_destinatario=$id_usuario";
		$res=$this->mysqli->query($sql);
		$sql="delete from miembros_alianzas where id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		echo "Eliminado con exito";
	}

	public function addTropas()
	{
		$id_ciudad=Datos::id_ciudad_nombre(Datos::safe($_POST['ciudad']));
		$sql="update tropas set tropa1=tropa1+".$_POST['tropa1']."
		,tropa2=tropa2+".$_POST['tropa2']."
		,tropa3=tropa3+".$_POST['tropa3']."
		,tropa4=tropa4+".$_POST['tropa4']."
		,tropa5=tropa5+".$_POST['tropa5']."
		,tropa6=tropa6+".$_POST['tropa6']."
		,tropa7=tropa7+".$_POST['tropa7']."
		,tropa8=tropa8+".$_POST['tropa8']."
		,tropa9=tropa9+".$_POST['tropa9']."
		,tropa10=tropa10+".$_POST['tropa10']." where id_ciudad=$id_ciudad";
		$res=$this->mysqli->query($sql);
		echo "Realizado con exito";
	}

	public function nivelEdificio()
	{

	}

	public function eliminarAlianza()
	{
		
	}
}

?>