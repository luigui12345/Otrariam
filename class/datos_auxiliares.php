<?php
class Datos
{
	private $mysqli;
	private $id_ciudad;

	public static function mysqli()
	{
		$mysqli=new mysqli(DB_HOST,SQL_USER,SQL_PASS,DB_NAME);
		$mysqli->set_charset(DB_CHARSET);
		return $mysqli;
	}

	public static function id($usuario) //Para obtener el id de un usuario
	{
		$mysqli=DB::Get();
		$sql="select id_usuario from usuarios where nombre = '$usuario' limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg["id_usuario"];
	}

	public static function usuario($id_usuario) //Para obtener el nombre de un usuario por su id
	{
		$mysqli=DB::Get();
		$sql="select nombre from usuarios where id_usuario = $id_usuario limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg["nombre"];
	}

	public static function id_ciudad($id_usuario) //Para obtener el id de la ciudad de un usuario
	{
		$mysqli=DB::Get();
		$sql="select id_casilla from mapa where id_usuario = $id_usuario and capital = 'si' limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg["id_casilla"];
	}

	public static function ciudad($id_ciudad) //Para obtener el nombre de una ciudad por su id
	{
		$mysqli=DB::Get();
		$sql="select nombre from mapa where id_casilla = $id_ciudad limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg["nombre"];
	}

	public static function propietario($id_ciudad)
	{
		$mysqli=DB::Get();
		$sql="select id_usuario from mapa where id_casilla = $id_ciudad limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg["id_usuario"];
	}

	public static function aldea($id_ciudad)
	{
		$mysqli=DB::Get();
		$sql="select nombre from mapa where id_casilla = $id_ciudad limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg["nombre"];
	}


	public static function tropa($tropa) //Nombre de una tropa segun su numero
	{
		$mysqli=DB::Get();
		$sql="select nombre from datos_tropas where tropa = '$tropa' limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg["nombre"];
	}
	
	public static function nTropas($id_ciudad) //Numeros de tropas de una ciudad
	{
		$mysqli=DB::Get();
		$sql="select * from tropas where id_ciudad = $id_ciudad limit 1";
		$res=$mysqli->query($sql);
		$rem=$res->fetch_array();
		$nTropas=0;
		for ($i=1;$i<11;$i++)
		{
			$nTropas=$nTropas+$rem['tropa'.$i];
		}
		$sql="select * from tropas_refuerzos where id_ciudad_reforzada = $id_ciudad";
		$res=$mysqli->query($sql);
		while($rem=$res->fetch_array())
		{
			for ($i=1;$i<11;$i++)
			{
				$nTropas=$nTropas+$rem['tropa'.$i];
			}
		}

		$sql="select * from ataques where id_ciudad_atacante = $id_ciudad";
		$res=$mysqli->query($sql);
		while($rem=$res->fetch_array())
		{
			for ($i=1;$i<11;$i++)
			{
				$nTropas=$nTropas+$rem['tropa'.$i];
			}
		}

		$sql="select * from vuelta_ataques where id_ciudad_atacante = $id_ciudad";
		$res=$mysqli->query($sql);
		while($rem=$res->fetch_array())
		{
			for ($i=1;$i<11;$i++)
			{
				$nTropas=$nTropas+$rem['tropa'.$i];
			}
		}
		
		return $nTropas;
	}

	public static function nivelEdificio($edificio,$id_ciudad) //Nivel del edificio de una ciudad
	{
		$mysqli=DB::Get();
		$sql="select nivel from edificios_aldea where edificio = '$edificio' and id_ciudad = $id_ciudad limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg['nivel'];
	}

	public static function produccionEdificio($edificio,$id_ciudad) //Nivel del edificio de una ciudad
	{
		$mysqli=DB::Get();
		$sql="select produccion from edificios_aldea where edificio = '$edificio' and id_ciudad = $id_ciudad limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg['produccion'];
	}

	public static function velocidadEjercito($id_ejercito,$objetivo)
	{
		$velocidad=5000;
		$mysqli=DB::Get();
		if ($objetivo=='ir')
		{
			$sql="select * from ataques where id_ataque=$id_ejercito";
			$res=$mysqli->query($sql);
		}
		if ($objetivo=='volver')
		{
			$sql="select * from vuelta_ataques where id_vuelta=$id_ejercito";
			$res=$mysqli->query($sql);
		}
		$reg=$res->fetch_array();

		for ($y=1;$y<=10;$y++)
		{
			if ($reg['tropa'.$y]>0)
			{
				$sql="select velocidad from datos_tropas where tropa = 'tropa$y'";
				$res=$mysqli->query($sql);
				$red=$res->fetch_array();
				
				if ($red['velocidad']<$velocidad || $velocidad == 0)
				{
					$velocidad=$red['velocidad'];
				}
			}
			else
			{

			}
		}
		return $velocidad;
	}

	public static function nombreAlianza($id)
	{
		$mysqli=DB::Get();
		$sql="select nombre from alianzas where id_alianza=$id limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg['nombre'];
	}

	public static function idAlianza($nombre)
	{
		$mysqli=DB::Get();
		$sql="select id_alianza from alianzas where nombre='$nombre' limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg['id_alianza'];
	}

	public static function recursosSuficientes($id,$recursos)
	{
		$mysqli=DB::Get();
		$sql="select madera,barro,hierro,cereal from mapa where id_casilla=$id limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		$recursos=explode('-', $recursos);
		if ($reg['madera']<$recursos[0] ||$reg['barro']<$recursos[1] || $reg['hierro']<$recursos[2] ||$reg['cereal']<$recursos[3])
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}

	public static function edificioPorSlot($slot)
	{
		$mysqli=DB::Get();
		$id_ciudad=$_SESSION['ju_ciudad'];
		$sql="select edificio from edificios_aldea where id_ciudad = $id_ciudad and slot = $slot limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg['edificio'];
	}

	public static function slotPorEdificio($edificio)
	{
		$mysqli=DB::Get();
		$id_ciudad=$_SESSION['ju_ciudad'];
		$sql="select slot from edificios_aldea where id_ciudad = $id_ciudad and edificio = '$edificio' limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg['slot'];
	}

	public static function cargoUsuario($id_usuario)
	{
		$mysqli=DB::Get();
		$sql="select nombre from cargos_alianzas where id_usuario=$id_usuario limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return $reg['nombre'];
	}

	public static function nombreAlianzaUsuario($id_usuario)
	{
		$mysqli=DB::Get();
		$sql="select id_alianza from miembros_alianzas where id_usuario=$id_usuario limit 1";
		$res=$mysqli->query($sql);
		$reg=$res->fetch_array();
		return Datos::nombreAlianza($reg['id_alianza']);
	}

	public static function idAlianzaUsuario($id_usuario)
	{
		$mysqli=DB::Get();
		$sql="select id_alianza from miembros_alianzas where id_usuario=$id_usuario and estado=1 limit 1";
		$res=$mysqli->query($sql);
		if ($res->num_rows>0)
		{
			$reg=$res->fetch_array();
			return $reg['id_alianza'];
		}
	}

	public static function enlaceAlianza($id_alianza)
	{
		$mysqli=DB::Get();
		$sql="select id_alianza from alianzas where id_alianza=$id_alianza limit 1";
		$res=$mysqli->query($sql);
		if ($res->num_rows>0)
		{
			$reg=$res->fetch_array();
			return '<a href="alianza.php?i='.$reg["id_alianza"].'">'.Datos::nombreAlianza($reg["id_alianza"]).'</a>';
		}
	}
}

?>